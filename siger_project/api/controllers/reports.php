<?php
/**
 * Controlador reports
 * Genera reportes y estadísticas para el panel administrativo
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/order.php';
require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../models/reservation.php';

// Requiere autenticación como admin
requireAuth();
requireRole('admin');

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    jsonResponse(false, 'Método no permitido');
}

$reportType = $_GET['type'] ?? 'sales';

try {
    switch ($reportType) {
        case 'sales':
            getSalesReport();
            break;
            
        case 'products':
            getProductsReport();
            break;
            
        case 'reservations':
            getReservationsReport();
            break;
            
        case 'dashboard':
            getDashboardData();
            break;
            
        default:
            jsonResponse(false, 'Tipo de reporte no válido');
    }
    
} catch (Exception $e) {
    jsonResponse(false, 'Error al generar reporte: ' . $e->getMessage());
}

/**
 * Reporte de ventas
 */
function getSalesReport() {
    $orderModel = new Order();
    
    $startDate = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
    $endDate = $_GET['end_date'] ?? date('Y-m-d');
    
    $stats = $orderModel->getStats($startDate, $endDate);
    
    jsonResponse(true, 'Reporte de ventas', [
        'period' => [
            'start' => $startDate,
            'end' => $endDate
        ],
        'total_orders' => (int)$stats['total_orders'],
        'total_sales' => (float)$stats['total_sales'],
        'average_order' => (float)$stats['average_order'],
        'completed_orders' => (int)$stats['completed_orders'],
        'cancelled_orders' => (int)$stats['cancelled_orders']
    ]);
}

/**
 * Reporte de productos
 */
function getProductsReport() {
    $orderModel = new Order();
    $productModel = new Product();
    
    $topProducts = $orderModel->getTopProducts(10);
    $allProducts = $productModel->getAll(['status' => 'active']);
    
    jsonResponse(true, 'Reporte de productos', [
        'top_selling' => $topProducts,
        'total_products' => count($allProducts),
        'categories' => $productModel->getCategories()
    ]);
}

/**
 * Reporte de reservas
 */
function getReservationsReport() {
    $reservationModel = new Reservation();
    
    $today = $reservationModel->getTodayReservations();
    $upcoming = $reservationModel->getUpcoming(7);
    
    jsonResponse(true, 'Reporte de reservas', [
        'today' => $today,
        'upcoming_week' => $upcoming,
        'today_count' => count($today),
        'upcoming_count' => count($upcoming)
    ]);
}

/**
 * Datos del dashboard
 */
function getDashboardData() {
    $orderModel = new Order();
    $productModel = new Product();
    $reservationModel = new Reservation();
    
    // Estadísticas de hoy
    $today = date('Y-m-d');
    $todayStats = $orderModel->getStats($today, $today);
    
    // Órdenes activas
    $activeOrders = $orderModel->getActiveOrders();
    
    // Reservas de hoy
    $todayReservations = $reservationModel->getTodayReservations();
    
    // Productos con stock bajo
    $allProducts = $productModel->getAll();
    $lowStock = array_filter($allProducts, function($p) {
        return $p['stock'] < 10;
    });
    
    jsonResponse(true, 'Dashboard data', [
        'today_sales' => [
            'orders' => (int)$todayStats['total_orders'],
            'revenue' => (float)$todayStats['total_sales'],
            'average' => (float)$todayStats['average_order']
        ],
        'active_orders' => count($activeOrders),
        'today_reservations' => count($todayReservations),
        'low_stock_products' => count($lowStock),
        'alerts' => [
            'low_stock' => array_map(function($p) {
                return [
                    'name' => $p['name'],
                    'stock' => $p['stock']
                ];
            }, array_values($lowStock))
        ]
    ]);
}
?>
