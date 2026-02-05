<?php
/**
 * Controlador place_order
 * Recibe y procesa pedidos desde la Tablet
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/order.php';
require_once __DIR__ . '/../models/product.php';

// Permitir CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    jsonResponse(false, 'Método no permitido');
}

// Obtener datos del pedido
$input = json_decode(file_get_contents('php://input'), true);

// Validar datos requeridos
if (empty($input['items']) || !is_array($input['items'])) {
    jsonResponse(false, 'El pedido debe contener al menos un producto');
}

try {
    $orderModel = new Order();
    $productModel = new Product();
    
    // Validar y calcular total
    $total = 0;
    $orderItems = [];
    
    foreach ($input['items'] as $item) {
        // Validar producto
        $product = $productModel->getById($item['product_id']);
        
        if (!$product) {
            jsonResponse(false, "Producto ID {$item['product_id']} no encontrado");
        }
        
        if (!$product['available']) {
            jsonResponse(false, "El producto '{$product['name']}' no está disponible");
        }
        
        // Calcular subtotal
        $quantity = (int)$item['quantity'];
        $price = (float)$product['price'];
        $subtotal = $quantity * $price;
        $total += $subtotal;
        
        $orderItems[] = [
            'product_id' => $product['id'],
            'product_name' => $product['name'],
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal
        ];
    }
    
    // Preparar datos de la orden
    $orderData = [
        'table_number' => $input['table_number'] ?? null,
        'customer_name' => $input['customer_name'] ?? 'Cliente',
        'total' => $total,
        'status' => 'pending',
        'order_type' => $input['order_type'] ?? 'dine_in', // dine_in, takeout, delivery
        'notes' => $input['notes'] ?? ''
    ];
    
    // Crear orden
    $orderId = $orderModel->create($orderData, $orderItems);
    
    if ($orderId) {
        jsonResponse(true, 'Pedido registrado exitosamente', [
            'order_id' => $orderId,
            'total' => $total,
            'items_count' => count($orderItems),
            'estimated_time' => '20-30 minutos'
        ]);
    } else {
        jsonResponse(false, 'Error al registrar el pedido');
    }
    
} catch (Exception $e) {
    jsonResponse(false, 'Error al procesar el pedido: ' . $e->getMessage());
}
?>
