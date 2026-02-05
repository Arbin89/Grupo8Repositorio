-- ============================================
-- SIGER - Sistema de Gestión de Restaurante
-- Script de Datos Iniciales (Seeds)
-- ============================================

USE siger_db;

-- ============================================
-- Insertar Usuarios por Defecto
-- ============================================

-- Usuario Admin (password: admin123)
INSERT INTO users (username, email, password, full_name, role, phone, status) VALUES
('admin', 'admin@siger.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Sistema', 'admin', '1234567890', 'active');

-- Usuario Cocina (password: cocina123)
INSERT INTO users (username, email, password, full_name, role, phone, status) VALUES
('cocina', 'cocina@siger.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Personal de Cocina', 'kitchen', '1234567891', 'active');

-- Usuario Mesero (password: mesero123)
INSERT INTO users (username, email, password, full_name, role, phone, status) VALUES
('mesero', 'mesero@siger.com', '$2y$10$gPVKhAKXJL3WXGqqKvXXx.Yn1EbBmqCqXFpWqLq3r9r8tN6zLaO5i', 'Mesero Principal', 'waiter', '1234567892', 'active');

-- ============================================
-- Insertar Productos de Ejemplo
-- ============================================

-- Entradas
INSERT INTO products (name, description, price, category, stock, available, status) VALUES
('Ensalada César', 'Lechuga romana, crutones, queso parmesano y aderezo césar', 89.00, 'entradas', 50, TRUE, 'active'),
('Sopa de Tortilla', 'Sopa tradicional mexicana con tiras de tortilla, aguacate y queso', 75.00, 'entradas', 40, TRUE, 'active'),
('Dedos de Queso', 'Dedos de queso mozzarella empanizados con salsa marinara', 95.00, 'entradas', 30, TRUE, 'active'),
('Guacamole con Chips', 'Guacamole fresco preparado al momento con totopos', 79.00, 'entradas', 45, TRUE, 'active'),
('Alitas BBQ', '8 piezas de alitas de pollo bañadas en salsa BBQ', 125.00, 'entradas', 35, TRUE, 'active');

-- Platos Principales
INSERT INTO products (name, description, price, category, stock, available, status) VALUES
('Hamburguesa Clásica', 'Carne de res 180g, lechuga, tomate, cebolla, pepinillos y papas fritas', 159.00, 'principales', 50, TRUE, 'active'),
('Pizza Margarita', 'Salsa de tomate, mozzarella fresca y albahaca', 189.00, 'principales', 40, TRUE, 'active'),
('Tacos al Pastor', '4 tacos con carne al pastor, piña, cilantro y cebolla', 139.00, 'principales', 60, TRUE, 'active'),
('Pasta Alfredo', 'Fetuccini en salsa cremosa de queso parmesano', 169.00, 'principales', 35, TRUE, 'active'),
('Filete de Pescado', 'Filete a la plancha con verduras al vapor y arroz', 219.00, 'principales', 25, TRUE, 'active'),
('Enchiladas Verdes', '3 enchiladas de pollo bañadas en salsa verde con crema', 149.00, 'principales', 45, TRUE, 'active'),
('Costillas BBQ', 'Rack completo de costillas con ensalada de col', 279.00, 'principales', 20, TRUE, 'active'),
('Pechuga Rellena', 'Pechuga de pollo rellena de espinacas y queso', 199.00, 'principales', 30, TRUE, 'active');

-- Postres
INSERT INTO products (name, description, price, category, stock, available, status) VALUES
('Pastel de Chocolate', 'Rebanada de pastel de chocolate con helado de vainilla', 89.00, 'postres', 40, TRUE, 'active'),
('Flan Napolitano', 'Flan casero con caramelo', 69.00, 'postres', 50, TRUE, 'active'),
('Helado Artesanal', '3 bolas de helado a elegir', 79.00, 'postres', 60, TRUE, 'active'),
('Churros con Chocolate', 'Churros recién hechos con chocolate caliente', 85.00, 'postres', 45, TRUE, 'active'),
('Cheesecake de Frutos Rojos', 'Pay de queso con coulis de frutos rojos', 99.00, 'postres', 35, TRUE, 'active');

-- Bebidas
INSERT INTO products (name, description, price, category, stock, available, status) VALUES
('Agua Natural', 'Agua purificada 500ml', 25.00, 'bebidas', 100, TRUE, 'active'),
('Refresco', 'Refresco de cola, naranja o limón', 35.00, 'bebidas', 100, TRUE, 'active'),
('Agua de Sabor', 'Jamaica, horchata o limón (1 litro)', 45.00, 'bebidas', 80, TRUE, 'active'),
('Jugo Natural', 'Naranja, zanahoria o mixto', 55.00, 'bebidas', 60, TRUE, 'active'),
('Café Americano', 'Café de grano recién molido', 39.00, 'bebidas', 70, TRUE, 'active'),
('Cappuccino', 'Café espresso con leche espumada', 49.00, 'bebidas', 70, TRUE, 'active'),
('Smoothie', 'Fresa, mango o plátano', 65.00, 'bebidas', 50, TRUE, 'active'),
('Cerveza Nacional', 'Cerveza lager mexicana', 45.00, 'bebidas', 80, TRUE, 'active'),
('Michelada', 'Cerveza preparada con limón y sal', 59.00, 'bebidas', 60, TRUE, 'active'),
('Margarita', 'Margarita clásica de limón', 89.00, 'bebidas', 40, TRUE, 'active');

-- ============================================
-- Insertar Órdenes de Ejemplo
-- ============================================

-- Orden 1 - Mesa 5
INSERT INTO orders (table_number, customer_name, total, status, order_type, notes) VALUES
('5', 'Juan Pérez', 468.00, 'preparing', 'dine_in', 'Sin cebolla en la hamburguesa');

SET @order_id = LAST_INSERT_ID();

INSERT INTO order_details (order_id, product_id, product_name, quantity, price, subtotal) VALUES
(@order_id, 1, 'Ensalada César', 1, 89.00, 89.00),
(@order_id, 6, 'Hamburguesa Clásica', 2, 159.00, 318.00),
(@order_id, 17, 'Refresco', 2, 35.00, 70.00),
(@order_id, 14, 'Pastel de Chocolate', 1, 89.00, 89.00);

-- Orden 2 - Mesa 3
INSERT INTO orders (table_number, customer_name, total, status, order_type) VALUES
('3', 'María González', 617.00, 'pending', 'dine_in');

SET @order_id = LAST_INSERT_ID();

INSERT INTO order_details (order_id, product_id, product_name, quantity, price, subtotal) VALUES
(@order_id, 3, 'Dedos de Queso', 1, 95.00, 95.00),
(@order_id, 7, 'Pizza Margarita', 1, 189.00, 189.00),
(@order_id, 13, 'Costillas BBQ', 1, 279.00, 279.00),
(@order_id, 20, 'Café Americano', 2, 39.00, 78.00),
(@order_id, 16, 'Helado Artesanal', 2, 79.00, 158.00);

-- Orden 3 - Mesa 7
INSERT INTO orders (table_number, customer_name, total, status, order_type) VALUES
('7', 'Carlos Ramírez', 347.00, 'ready', 'dine_in');

SET @order_id = LAST_INSERT_ID();

INSERT INTO order_details (order_id, product_id, product_name, quantity, price, subtotal) VALUES
(@order_id, 8, 'Tacos al Pastor', 2, 139.00, 278.00),
(@order_id, 18, 'Agua de Sabor', 1, 45.00, 45.00),
(@order_id, 15, 'Flan Napolitano', 1, 69.00, 69.00);

-- Orden 4 - Para llevar
INSERT INTO orders (table_number, customer_name, total, status, order_type, notes) VALUES
(NULL, 'Ana Martínez', 538.00, 'pending', 'takeout', 'Bien cocida la carne');

SET @order_id = LAST_INSERT_ID();

INSERT INTO order_details (order_id, product_id, product_name, quantity, price, subtotal) VALUES
(@order_id, 5, 'Alitas BBQ', 2, 125.00, 250.00),
(@order_id, 9, 'Pasta Alfredo', 1, 169.00, 169.00),
(@order_id, 19, 'Jugo Natural', 2, 55.00, 110.00),
(@order_id, 17, 'Cheesecake de Frutos Rojos', 1, 99.00, 99.00);

-- ============================================
-- Insertar Reservas de Ejemplo
-- ============================================

-- Reservas para hoy
INSERT INTO reservations (customer_name, customer_email, customer_phone, guests, reservation_date, reservation_time, notes, status) VALUES
('Luis Hernández', 'luis.hernandez@email.com', '5551234567', 4, CURDATE(), '14:30:00', 'Mesa junto a la ventana', 'confirmed'),
('Patricia Sánchez', 'patricia.sanchez@email.com', '5559876543', 2, CURDATE(), '19:00:00', 'Aniversario', 'confirmed'),
('Roberto López', 'roberto.lopez@email.com', '5556789012', 6, CURDATE(), '20:30:00', '', 'pending');

-- Reservas futuras
INSERT INTO reservations (customer_name, customer_email, customer_phone, guests, reservation_date, reservation_time, notes, status) VALUES
('Carmen Ruiz', 'carmen.ruiz@email.com', '5554321098', 8, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '13:00:00', 'Cumpleaños infantil', 'confirmed'),
('Fernando Díaz', 'fernando.diaz@email.com', '5558765432', 3, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '18:00:00', '', 'pending'),
('Gabriela Torres', 'gabriela.torres@email.com', '5552109876', 5, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '19:30:00', 'Cena de negocios', 'confirmed');

-- ============================================
-- Insertar Registros de Actividad
-- ============================================

INSERT INTO activity_log (user_id, action, table_name, record_id, description, ip_address) VALUES
(1, 'CREATE', 'products', 1, 'Producto creado: Ensalada César', '127.0.0.1'),
(1, 'CREATE', 'users', 2, 'Usuario creado: cocina', '127.0.0.1'),
(3, 'UPDATE', 'orders', 1, 'Estado de orden actualizado a: preparing', '127.0.0.1'),
(2, 'UPDATE', 'orders', 3, 'Estado de orden actualizado a: ready', '127.0.0.1');

-- ============================================
-- Verificación de datos insertados
-- ============================================

SELECT 'Datos iniciales insertados correctamente' as Status;
SELECT COUNT(*) as Total_Usuarios FROM users;
SELECT COUNT(*) as Total_Productos FROM products;
SELECT COUNT(*) as Total_Ordenes FROM orders;
SELECT COUNT(*) as Total_Reservas FROM reservations;

-- ============================================
-- Fin del script
-- ============================================
