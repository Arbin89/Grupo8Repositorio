<?php
/**
 * Modelo Reservation - Gestión de reservas
 * Maneja las reservas de mesas realizadas desde el módulo Home
 */

require_once __DIR__ . '/../../config/db.php';

class Reservation {
    private $db;
    private $table = 'reservations';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear nueva reserva
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (customer_name, customer_email, customer_phone, guests, 
                   reservation_date, reservation_time, notes, status) 
                  VALUES (:customer_name, :customer_email, :customer_phone, :guests,
                          :reservation_date, :reservation_time, :notes, :status)";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':customer_name', $data['customer_name']);
        $stmt->bindParam(':customer_email', $data['customer_email']);
        $stmt->bindParam(':customer_phone', $data['customer_phone']);
        $stmt->bindParam(':guests', $data['guests']);
        $stmt->bindParam(':reservation_date', $data['reservation_date']);
        $stmt->bindParam(':reservation_time', $data['reservation_time']);
        $stmt->bindParam(':notes', $data['notes']);
        $stmt->bindParam(':status', $data['status']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    /**
     * Obtener todas las reservas
     */
    public function getAll($filters = []) {
        $query = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (isset($filters['status'])) {
            $query .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (isset($filters['date'])) {
            $query .= " AND reservation_date = :date";
            $params[':date'] = $filters['date'];
        }
        
        if (isset($filters['email'])) {
            $query .= " AND customer_email = :email";
            $params[':email'] = $filters['email'];
        }
        
        $query .= " ORDER BY reservation_date DESC, reservation_time DESC";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener reserva por ID
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Actualizar reserva
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  customer_name = :customer_name,
                  customer_email = :customer_email,
                  customer_phone = :customer_phone,
                  guests = :guests,
                  reservation_date = :reservation_date,
                  reservation_time = :reservation_time,
                  notes = :notes,
                  status = :status
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':customer_name', $data['customer_name']);
        $stmt->bindParam(':customer_email', $data['customer_email']);
        $stmt->bindParam(':customer_phone', $data['customer_phone']);
        $stmt->bindParam(':guests', $data['guests']);
        $stmt->bindParam(':reservation_date', $data['reservation_date']);
        $stmt->bindParam(':reservation_time', $data['reservation_time']);
        $stmt->bindParam(':notes', $data['notes']);
        $stmt->bindParam(':status', $data['status']);
        
        return $stmt->execute();
    }
    
    /**
     * Actualizar estado de reserva
     */
    public function updateStatus($id, $status) {
        $query = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Eliminar reserva
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Verificar disponibilidad para una fecha/hora
     */
    public function checkAvailability($date, $time, $guests) {
        // Contar reservas confirmadas para esa fecha/hora
        $query = "SELECT COUNT(*) as count, SUM(guests) as total_guests 
                  FROM {$this->table} 
                  WHERE reservation_date = :date 
                  AND reservation_time = :time 
                  AND status = 'confirmed'";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();
        
        $result = $stmt->fetch();
        
        // Supongamos capacidad máxima de 50 personas
        $maxCapacity = 50;
        $currentGuests = $result['total_guests'] ?? 0;
        
        return ($currentGuests + $guests) <= $maxCapacity;
    }
    
    /**
     * Obtener reservas de hoy
     */
    public function getTodayReservations() {
        $today = date('Y-m-d');
        $query = "SELECT * FROM {$this->table} 
                  WHERE reservation_date = :today 
                  AND status IN ('confirmed', 'pending')
                  ORDER BY reservation_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':today', $today);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener próximas reservas
     */
    public function getUpcoming($days = 7) {
        $today = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        
        $query = "SELECT * FROM {$this->table} 
                  WHERE reservation_date BETWEEN :today AND :end_date 
                  AND status IN ('confirmed', 'pending')
                  ORDER BY reservation_date ASC, reservation_time ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':today', $today);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>
