<?php
/**
 * Controlador kitchen_sync
 * Actualiza el estado de pedidos y sincroniza pantalla de cocina
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/order.php';

// Permitir CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

$orderModel = new Order();

switch ($method) {
    case 'GET':
        // Obtener órdenes activas para la cocina
        getActiveOrders($orderModel);
        break;
        
    case 'POST':
    case 'PUT':
        // Actualizar estado de una orden
        updateOrderStatus($orderModel, $input);
        break;
        
    default:
        jsonResponse(false, 'Método no permitido');
}

/**
 * Obtener órdenes activas
 */
function getActiveOrders($orderModel) {
    try {
        $orders = $orderModel->getActiveOrders();
        
        // Formatear respuesta con tiempo transcurrido
        $formattedOrders = array_map(function($order) {
            $createdTime = strtotime($order['created_at']);
            $now = time();
            $elapsed = $now - $createdTime;
            
            return [
                'id' => (int)$order['id'],
                'table_number' => $order['table_number'],
                'customer_name' => $order['customer_name'],
                'items_summary' => $order['items_summary'],
                'status' => $order['status'],
                'order_type' => $order['order_type'],
                'total' => (float)$order['total'],
                'created_at' => $order['created_at'],
                'elapsed_minutes' => floor($elapsed / 60),
                'is_delayed' => $elapsed > 1800 // Más de 30 minutos
            ];
        }, $orders);
        
        jsonResponse(true, 'Órdenes obtenidas', $formattedOrders);
        
    } catch (Exception $e) {
        jsonResponse(false, 'Error al obtener órdenes: ' . $e->getMessage());
    }
}

/**
 * Actualizar estado de orden
 */
function updateOrderStatus($orderModel, $input) {
    // Validar datos
    if (empty($input['order_id']) || empty($input['status'])) {
        jsonResponse(false, 'order_id y status son requeridos');
    }
    
    $orderId = $input['order_id'];
    $status = $input['status'];
    
    // Validar estados permitidos
    $allowedStatuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
    if (!in_array($status, $allowedStatuses)) {
        jsonResponse(false, 'Estado no válido');
    }
    
    try {
        $success = $orderModel->updateStatus($orderId, $status);
        
        if ($success) {
            jsonResponse(true, "Estado actualizado a: $status", [
                'order_id' => $orderId,
                'new_status' => $status
            ]);
        } else {
            jsonResponse(false, 'Error al actualizar el estado');
        }
        
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}
?>
