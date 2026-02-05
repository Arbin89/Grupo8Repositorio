<?php
/**
 * Configuración Global del Proyecto SIGER
 * Define constantes y configuraciones compartidas por toda la aplicación
 */

// Configuración de sesiones
session_start();

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de errores (Desactivar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// URL Base del proyecto
define('BASE_URL', 'http://localhost/siger_project/');
define('API_URL', BASE_URL . 'api/');

// Rutas de archivos
define('UPLOAD_PATH', __DIR__ . '/../api/uploads/');
define('UPLOAD_URL', BASE_URL . 'api/uploads/');
define('PRODUCT_IMG_PATH', UPLOAD_PATH . 'products/');
define('PRODUCT_IMG_URL', UPLOAD_URL . 'products/');

// Configuración de carga de archivos
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Estados de pedidos
define('ORDER_STATUS', [
    'pending' => 'Pendiente',
    'preparing' => 'En Preparación',
    'ready' => 'Listo',
    'delivered' => 'Entregado',
    'cancelled' => 'Cancelado'
]);

// Roles de usuario
define('USER_ROLES', [
    'admin' => 'Administrador',
    'kitchen' => 'Cocina',
    'waiter' => 'Mesero'
]);

// Configuración de respuestas JSON
header('Content-Type: application/json; charset=utf-8');

// Función auxiliar para respuestas JSON
function jsonResponse($success, $message = '', $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Función para validar sesión
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        jsonResponse(false, 'No autorizado. Inicia sesión primero.');
    }
}

// Función para validar rol
function requireRole($role) {
    requireAuth();
    if ($_SESSION['user_role'] !== $role) {
        jsonResponse(false, 'No tienes permisos para acceder a este recurso.');
    }
}
?>