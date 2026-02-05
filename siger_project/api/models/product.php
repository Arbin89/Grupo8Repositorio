<?php
/**
 * Modelo Product - Gestión de productos/menú
 * Maneja inventario y catálogo del menú
 */

require_once __DIR__ . '/../../config/db.php';

class Product {
    private $db;
    private $table = 'products';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear nuevo producto
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (name, description, price, category, image, stock, available, status) 
                  VALUES (:name, :description, :price, :category, :image, :stock, :available, :status)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':available', $data['available']);
        $stmt->bindParam(':status', $data['status']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    /**
     * Obtener todos los productos
     */
    public function getAll($filters = []) {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (isset($filters['category'])) {
            $query .= " AND category = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (isset($filters['available'])) {
            $query .= " AND available = :available";
            $params[':available'] = $filters['available'];
        }
        
        if (isset($filters['status'])) {
            $query .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
        
        $query .= " ORDER BY category, name";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener producto por ID
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Actualizar producto
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  name = :name,
                  description = :description,
                  price = :price,
                  category = :category,
                  stock = :stock,
                  available = :available,
                  status = :status";
        
        // Solo actualizar imagen si se proporciona una nueva
        if (!empty($data['image'])) {
            $query .= ", image = :image";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':available', $data['available']);
        $stmt->bindParam(':status', $data['status']);
        
        if (!empty($data['image'])) {
            $stmt->bindParam(':image', $data['image']);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar producto (soft delete)
     */
    public function delete($id) {
        $query = "UPDATE {$this->table} SET status = 'inactive' WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Actualizar stock
     */
    public function updateStock($id, $quantity, $operation = 'subtract') {
        $product = $this->getById($id);
        
        if (!$product) {
            return false;
        }
        
        $newStock = $operation === 'add' 
            ? $product['stock'] + $quantity 
            : $product['stock'] - $quantity;
        
        // No permitir stock negativo
        if ($newStock < 0) {
            return false;
        }
        
        $query = "UPDATE {$this->table} SET stock = :stock WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':stock', $newStock);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    /**
     * Obtener categorías únicas
     */
    public function getCategories() {
        $query = "SELECT DISTINCT category FROM {$this->table} 
                  WHERE status = 'active' ORDER BY category";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Buscar productos
     */
    public function search($term) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE (name LIKE :term OR description LIKE :term) 
                  AND status = 'active'
                  ORDER BY name";
        
        $stmt = $this->db->prepare($query);
        $searchTerm = "%{$term}%";
        $stmt->bindParam(':term', $searchTerm);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>
