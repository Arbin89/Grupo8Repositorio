<?php
/**
 * Modelo User - Gestión de usuarios del sistema
 * Maneja autenticación, roles y CRUD de usuarios
 */

require_once __DIR__ . '/../../config/db.php';

class User {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear nuevo usuario
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (username, email, password, full_name, role, phone, status) 
                  VALUES (:username, :email, :password, :full_name, :role, :phone, :status)";
        
        $stmt = $this->db->prepare($query);
        
        // Hash de la contraseña
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener todos los usuarios
     */
    public function getAll($filters = []) {
        $query = "SELECT id, username, email, full_name, role, phone, status, created_at 
                  FROM {$this->table} WHERE 1=1";
        
        $params = [];
        
        if (isset($filters['role'])) {
            $query .= " AND role = :role";
            $params[':role'] = $filters['role'];
        }
        
        if (isset($filters['status'])) {
            $query .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener usuario por ID
     */
    public function getById($id) {
        $query = "SELECT id, username, email, full_name, role, phone, status, created_at 
                  FROM {$this->table} WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Actualizar usuario
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  username = :username,
                  email = :email,
                  full_name = :full_name,
                  role = :role,
                  phone = :phone,
                  status = :status";
        
        // Solo actualizar password si se proporciona uno nuevo
        if (!empty($data['password'])) {
            $query .= ", password = :password";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':full_name', $data['full_name']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':status', $data['status']);
        
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashedPassword);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar usuario (soft delete - cambiar status)
     */
    public function delete($id) {
        $query = "UPDATE {$this->table} SET status = 'inactive' WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Autenticar usuario
     */
    public function authenticate($username, $password) {
        $query = "SELECT id, username, email, password, full_name, role, status 
                  FROM {$this->table} 
                  WHERE (username = ? OR email = ?) 
                  AND status = 'active'";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$username, $username]);
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Remover password del resultado
            unset($user['password']);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Verificar si el username o email ya existe
     */
    public function exists($username, $email, $excludeId = null) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} 
                  WHERE (username = :username OR email = :email)";
        
        if ($excludeId) {
            $query .= " AND id != :id";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        
        if ($excludeId) {
            $stmt->bindParam(':id', $excludeId);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
}
?>
