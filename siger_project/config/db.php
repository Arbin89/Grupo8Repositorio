<?php
/**
 * Conexión a la Base de Datos MySQL usando Singleton
 * Asegura que solo exista una instancia de la conexión durante todo el ciclo de vida
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Configuración de la base de datos
    private $host = 'localhost';
    private $db_name = 'siger_db';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    
    // Constructor privado para patrón Singleton
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    
    // Obtener la instancia única de la base de datos
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Obtener la conexión PDO
    public function getConnection() {
        return $this->connection;
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("No se puede deserializar un singleton");
    }
}
?>
