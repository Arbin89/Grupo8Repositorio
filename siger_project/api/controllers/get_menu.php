<?php
/**
 * Controlador get_menu
 * Retorna el menú en formato JSON para Tablet/Home
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/product.php';

// Permitir CORS para peticiones AJAX
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'GET') {
    jsonResponse(false, 'Método no permitido');
}

try {
    $productModel = new Product();
    
    // Obtener filtros de la query string
    $filters = [
        'available' => 1, // Solo productos disponibles
        'status' => 'active' // Solo productos activos
    ];
    
    // Filtro opcional por categoría
    if (isset($_GET['category']) && $_GET['category'] !== 'all') {
        $filters['category'] = $_GET['category'];
    }
    
    // Obtener productos
    $products = $productModel->getAll($filters);
    
    // Formatear respuesta
    $menu = array_map(function($product) {
        return [
            'id' => (int)$product['id'],
            'name' => $product['name'],
            'description' => $product['description'],
            'price' => (float)$product['price'],
            'category' => $product['category'],
            'image' => !empty($product['image']) 
                ? PRODUCT_IMG_URL . $product['image'] 
                : null,
            'available' => (bool)$product['available'],
            'stock' => (int)$product['stock']
        ];
    }, $products);
    
    jsonResponse(true, 'Menú obtenido exitosamente', $menu);
    
} catch (Exception $e) {
    jsonResponse(false, 'Error al obtener el menú: ' . $e->getMessage());
}
?>
