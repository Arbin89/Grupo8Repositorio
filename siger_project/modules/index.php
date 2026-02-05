<?php
/**
 * SIGER - Redireccionador Principal de Módulos
 * Fecha: Enero 2026
 * Grupo #8
 */

// Configuración básica
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGER - Sistema de Gestión de Restaurantes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 800px;
            width: 90%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        .subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 1.1em;
        }
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .module-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .module-card:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        }
        .module-icon {
            font-size: 3em;
            margin-bottom: 15px;
        }
        .module-title {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .module-desc {
            color: #666;
            font-size: 0.9em;
            line-height: 1.5;
        }
        .footer {
            margin-top: 40px;
            color: #888;
            font-size: 0.9em;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🍽️ SIGER</h1>
        <p class="subtitle">Sistema Inteligente de Gestión de Restaurantes - Grupo #8</p>
        
        <p>Selecciona el módulo al que deseas acceder:</p>
        
        <div class="modules-grid">
            <!-- Módulo Administrativo -->
            <a href="Admin/Index.html" class="module-card">
                <div class="module-icon">👨‍💼</div>
                <div class="module-title">Panel Administrativo</div>
                <div class="module-desc">Gestión de usuarios, inventario, pedidos y reportes del restaurante.</div>
            </a>
            
            <!-- Módulo Tablet -->
            <a href="Tablet/index.html" class="module-card">
                <div class="module-icon">📱</div>
                <div class="module-title">Menú Digital (Tablet)</div>
                <div class="module-desc">Interfaz táctil para que los clientes realicen pedidos desde su mesa.</div>
            </a>
            
            <!-- Módulo Cocina -->
            <a href="kitchen/index.html" class="module-card">
                <div class="module-icon">👨‍🍳</div>
                <div class="module-title">Vista Cocina</div>
                <div class="module-desc">Monitor de pedidos en tiempo real para el personal de cocina.</div>
            </a>
            
            <!-- Módulo Home (Futuro) -->
            <div class="module-card" style="opacity: 0.7; cursor: not-allowed;">
                <div class="module-icon">🏠</div>
                <div class="module-title">Pedidos desde Casa</div>
                <div class="module-desc">Reservas y pedidos para llevar/recoger (Próximamente).</div>
            </div>
        </div>
        
        <div class="footer">
            <p>Tecnologías: PHP • MySQL • HTML5 • CSS3 • JavaScript Vanilla</p>
            <p>Repositorio: <a href="https://github.com/Arbin89/Grupo8Repositorio" target="_blank">GitHub Grupo #8</a></p>
            <p>© 2026 - Proyecto SIGER - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>
