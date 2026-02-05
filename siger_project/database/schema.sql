-- ============================================
-- SIGER - Sistema de Gestión de Restaurante
-- Script de Creación de Base de Datos
-- ============================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS siger_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE siger_db;

-- ============================================
-- Tabla: users
-- Gestión de usuarios del sistema
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'kitchen', 'waiter') NOT NULL DEFAULT 'waiter',
    phone VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: products
-- Catálogo de productos/menú
-- ============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category ENUM('entradas', 'principales', 'postres', 'bebidas', 'otros') NOT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    available BOOLEAN DEFAULT TRUE,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_available (available),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: orders
-- Pedidos de clientes
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_number VARCHAR(10),
    customer_name VARCHAR(100),
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    order_type ENUM('dine_in', 'takeout', 'delivery') DEFAULT 'dine_in',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_order_type (order_type),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: order_details
-- Detalles de cada pedido
-- ============================================
CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: reservations
-- Reservas de mesas
-- ============================================
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    guests INT NOT NULL,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    notes TEXT,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_date (reservation_date),
    INDEX idx_status (status),
    INDEX idx_email (customer_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: activity_log (Opcional - para auditoría)
-- ============================================
CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(50) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Vistas útiles
-- ============================================

-- Vista: Órdenes con detalles
CREATE OR REPLACE VIEW v_orders_with_details AS
SELECT 
    o.id,
    o.table_number,
    o.customer_name,
    o.total,
    o.status,
    o.order_type,
    o.created_at,
    COUNT(od.id) as items_count,
    GROUP_CONCAT(CONCAT(od.quantity, 'x ', od.product_name) SEPARATOR ', ') as items_summary
FROM orders o
LEFT JOIN order_details od ON o.id = od.order_id
GROUP BY o.id;

-- Vista: Productos más vendidos
CREATE OR REPLACE VIEW v_top_products AS
SELECT 
    p.id,
    p.name,
    p.category,
    p.price,
    COUNT(od.id) as times_ordered,
    SUM(od.quantity) as total_quantity,
    SUM(od.subtotal) as total_revenue
FROM products p
INNER JOIN order_details od ON p.id = od.product_id
GROUP BY p.id
ORDER BY total_quantity DESC;

-- Vista: Ventas diarias
CREATE OR REPLACE VIEW v_daily_sales AS
SELECT 
    DATE(created_at) as sale_date,
    COUNT(*) as total_orders,
    SUM(total) as total_revenue,
    AVG(total) as average_order,
    COUNT(CASE WHEN status = 'delivered' THEN 1 END) as completed_orders
FROM orders
GROUP BY DATE(created_at)
ORDER BY sale_date DESC;

-- ============================================
-- Procedimientos almacenados (Opcional)
-- ============================================

DELIMITER //

-- Procedimiento: Crear orden completa
CREATE PROCEDURE IF NOT EXISTS sp_create_order(
    IN p_table_number VARCHAR(10),
    IN p_customer_name VARCHAR(100),
    IN p_order_type VARCHAR(20),
    IN p_notes TEXT,
    IN p_items JSON,
    OUT p_order_id INT
)
BEGIN
    DECLARE v_total DECIMAL(10, 2) DEFAULT 0;
    DECLARE v_item_count INT;
    DECLARE v_index INT DEFAULT 0;
    DECLARE v_product_id INT;
    DECLARE v_quantity INT;
    DECLARE v_price DECIMAL(10, 2);
    DECLARE v_subtotal DECIMAL(10, 2);
    DECLARE v_product_name VARCHAR(100);
    
    -- Iniciar transacción
    START TRANSACTION;
    
    -- Calcular total
    SET v_item_count = JSON_LENGTH(p_items);
    
    WHILE v_index < v_item_count DO
        SET v_product_id = JSON_EXTRACT(p_items, CONCAT('$[', v_index, '].product_id'));
        SET v_quantity = JSON_EXTRACT(p_items, CONCAT('$[', v_index, '].quantity'));
        
        -- Obtener precio del producto
        SELECT price, name INTO v_price, v_product_name
        FROM products 
        WHERE id = v_product_id;
        
        SET v_subtotal = v_price * v_quantity;
        SET v_total = v_total + v_subtotal;
        SET v_index = v_index + 1;
    END WHILE;
    
    -- Crear orden
    INSERT INTO orders (table_number, customer_name, total, order_type, notes)
    VALUES (p_table_number, p_customer_name, v_total, p_order_type, p_notes);
    
    SET p_order_id = LAST_INSERT_ID();
    
    -- Insertar detalles
    SET v_index = 0;
    WHILE v_index < v_item_count DO
        SET v_product_id = JSON_EXTRACT(p_items, CONCAT('$[', v_index, '].product_id'));
        SET v_quantity = JSON_EXTRACT(p_items, CONCAT('$[', v_index, '].quantity'));
        
        SELECT price, name INTO v_price, v_product_name
        FROM products 
        WHERE id = v_product_id;
        
        SET v_subtotal = v_price * v_quantity;
        
        INSERT INTO order_details (order_id, product_id, product_name, quantity, price, subtotal)
        VALUES (p_order_id, v_product_id, v_product_name, v_quantity, v_price, v_subtotal);
        
        SET v_index = v_index + 1;
    END WHILE;
    
    COMMIT;
END //

DELIMITER ;

-- ============================================
-- Triggers
-- ============================================

DELIMITER //

-- Trigger: Actualizar stock después de crear orden
CREATE TRIGGER IF NOT EXISTS trg_update_stock_after_order
AFTER INSERT ON order_details
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock = stock - NEW.quantity
    WHERE id = NEW.product_id;
END //

-- Trigger: Registrar actividad de usuarios
CREATE TRIGGER IF NOT EXISTS trg_log_user_changes
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    INSERT INTO activity_log (user_id, action, table_name, record_id, description)
    VALUES (NEW.id, 'UPDATE', 'users', NEW.id, 
            CONCAT('Usuario actualizado: ', NEW.username));
END //

DELIMITER ;

-- ============================================
-- Índices adicionales para optimización
-- ============================================

-- Índice compuesto para búsquedas de reservas
ALTER TABLE reservations 
ADD INDEX idx_date_status (reservation_date, status);

-- Índice para búsquedas de órdenes por fecha
ALTER TABLE orders 
ADD INDEX idx_created_status (created_at, status);

-- ============================================
-- Fin del script
-- ============================================
