<?php
/**
 * Script de instalación automática de la base de datos SIGER
 * Ejecuta los scripts schema.sql y seeds.sql
 */

// Configuración de conexión
$host = 'localhost';
$username = 'root';
$password = '';

echo "<h1>🚀 Instalación de Base de Datos SIGER</h1>";
echo "<hr>";

try {
    // Conectar a MySQL
    echo "<p>📡 Conectando a MySQL...</p>";
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✅ Conexión exitosa</p>";
    
    // Leer y ejecutar schema.sql
    echo "<p>📄 Ejecutando schema.sql...</p>";
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    
    // Dividir en múltiples queries
    $queries = explode(';', $schema);
    $executed = 0;
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query) && $query !== 'DELIMITER //') {
            try {
                $pdo->exec($query);
                $executed++;
            } catch (PDOException $e) {
                // Ignorar algunos errores comunes
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "<p style='color: orange;'>⚠️ " . $e->getMessage() . "</p>";
                }
            }
        }
    }
    
    echo "<p style='color: green;'>✅ Schema ejecutado ($executed queries)</p>";
    
    // Usar la base de datos
    $pdo->exec("USE siger_db");
    
    // Leer y ejecutar seeds.sql
    echo "<p>📄 Ejecutando seeds.sql...</p>";
    $seeds = file_get_contents(__DIR__ . '/database/seeds.sql');
    
    // Limpiar el contenido
    $seeds = preg_replace('/^USE.*?;/m', '', $seeds);
    $seeds = preg_replace('/DELIMITER.*/m', '', $seeds);
    
    $queries = explode(';', $seeds);
    $executed = 0;
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query) && 
            !preg_match('/^(SELECT|SHOW|DESCRIBE)/i', $query) &&
            strlen($query) > 10) {
            try {
                $pdo->exec($query);
                $executed++;
            } catch (PDOException $e) {
                echo "<p style='color: orange;'>⚠️ " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<p style='color: green;'>✅ Seeds ejecutado ($executed inserts)</p>";
    
    // Verificar datos
    echo "<hr>";
    echo "<h2>📊 Verificación de Datos</h2>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $users = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>👥 Usuarios: <strong>{$users['total']}</strong></p>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
    $products = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>🍽️ Productos: <strong>{$products['total']}</strong></p>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
    $orders = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>📋 Órdenes: <strong>{$orders['total']}</strong></p>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reservations");
    $reservations = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>📅 Reservas: <strong>{$reservations['total']}</strong></p>";
    
    echo "<hr>";
    echo "<h2 style='color: green;'>🎉 ¡Instalación Completada!</h2>";
    echo "<p><strong>Credenciales de acceso:</strong></p>";
    echo "<ul>";
    echo "<li>Admin: <code>admin</code> / <code>admin123</code></li>";
    echo "<li>Cocina: <code>cocina</code> / <code>cocina123</code></li>";
    echo "<li>Mesero: <code>mesero</code> / <code>mesero123</code></li>";
    echo "</ul>";
    echo "<hr>";
    echo "<p><a href='index.php' style='display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px;'>🚀 Ir al Sistema</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background: #f5f5f5;
    }
    h1 { color: #667eea; }
    code {
        background: #eee;
        padding: 2px 6px;
        border-radius: 3px;
    }
</style>
