<?php
/**
 * Controlador de Autenticación
 * Maneja login, logout y verificación de sesiones
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/users.php';

// Obtener método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Obtener datos de la petición
$input = json_decode(file_get_contents('php://input'), true);

// Instanciar modelo
$userModel = new User();

switch ($method) {
    case 'POST':
        // Determinar acción
        $action = $_GET['action'] ?? 'login';
        
        if ($action === 'login') {
            handleLogin($userModel, $input);
        } elseif ($action === 'logout') {
            handleLogout();
        } elseif ($action === 'check') {
            checkSession();
        }
        break;
        
    case 'GET':
        // Verificar sesión actual
        checkSession();
        break;
        
    default:
        jsonResponse(false, 'Método no permitido');
}

/**
 * Manejar inicio de sesión
 */
function handleLogin($userModel, $input) {
    // Validar datos
    if (empty($input['username']) || empty($input['password'])) {
        jsonResponse(false, 'Usuario y contraseña son requeridos');
    }
    
    // Intentar autenticar
    $user = $userModel->authenticate($input['username'], $input['password']);
    
    if ($user) {
        // Guardar datos en sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['logged_in'] = true;
        
        jsonResponse(true, 'Inicio de sesión exitoso', [
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'role' => $user['role'],
                'email' => $user['email']
            ],
            'redirect' => getRedirectByRole($user['role'])
        ]);
    } else {
        jsonResponse(false, 'Credenciales incorrectas');
    }
}

/**
 * Manejar cierre de sesión
 */
function handleLogout() {
    session_destroy();
    jsonResponse(true, 'Sesión cerrada exitosamente');
}

/**
 * Verificar estado de la sesión
 */
function checkSession() {
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
        jsonResponse(true, 'Sesión activa', [
            'user' => [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'full_name' => $_SESSION['full_name'],
                'role' => $_SESSION['user_role'],
                'email' => $_SESSION['email']
            ]
        ]);
    } else {
        jsonResponse(false, 'No hay sesión activa');
    }
}

/**
 * Obtener ruta de redirección según rol
 */
function getRedirectByRole($role) {
    $redirects = [
        'admin' => '../../modules/Admin/Index.html',
        'kitchen' => '../../modules/kitchen/index.html',
        'waiter' => '../../modules/Tablet/index.html'
    ];
    
    return $redirects[$role] ?? '../../modules/home/index.html';
}
?>
