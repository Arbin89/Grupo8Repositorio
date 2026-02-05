# 🍽️ SIGER - Sistema de Gestión de Restaurante

Sistema integral de gestión para restaurantes desarrollado con **React + PHP API + MySQL**.

## 📋 Descripción del Proyecto

SIGER es un sistema completo que integra múltiples módulos para la gestión eficiente de un restaurante:

- **Módulo Home**: Landing page con menú interactivo para clientes
- **Módulo Login**: Sistema de autenticación de usuarios
- **Módulo Admin**: Panel administrativo para gestión de usuarios, inventario y reportes
- **Módulo Tablet**: Interfaz táctil para que clientes realicen pedidos desde tablets
- **Módulo Kitchen**: Pantalla de cocina para visualizar y gestionar pedidos en tiempo real

## 🛠️ Stack Tecnológico

- **Frontend**: React 18 + Vite + React Router
- **Backend**: PHP 7.4+ (API REST)
- **Base de Datos**: MySQL 8.0+
- **Servidor**: Apache (vía XAMPP) + Node.js para desarrollo
- **Comunicación**: Axios para peticiones HTTP

## 📁 Estructura del Proyecto

```
siger_project/
│
├── config/                     # Configuraciones globales
│   ├── db.php                  # Conexión a MySQL (Singleton)
│   └── config.php              # Constantes y funciones auxiliares
│
├── api/                        # Backend (PHP - API REST)
│   ├── models/                 # Modelos de datos
│   │   ├── users.php           # Gestión de usuarios
│   │   ├── product.php         # Productos/Inventario
│   │   ├── order.php           # Pedidos y tickets
│   │   └── reservation.php     # Reservas
│   │
│   ├── controllers/            # Controladores/Endpoints
│   │   ├── auth.php            # Login/Logout
│   │   ├── get_menu.php        # Obtener menú
│   │   ├── place_order.php     # Crear pedido
│   │   ├── kitchen_sync.php    # Sincronización cocina
│   │   └── reports.php         # Reportes y estadísticas
│   │
│   └── uploads/                # Archivos subidos
│       └── products/           # Imágenes de productos
│
├── frontend/                   # Frontend React
│   ├── src/
│   │   ├── pages/              # Páginas de la aplicación
│   │   │   ├── Home.jsx        # Landing page con menú
│   │   │   ├── Login.jsx       # Autenticación
│   │   │   ├── Admin.jsx       # Panel administrativo
│   │   │   ├── Tablet.jsx      # Interfaz Tablet/Kiosk
│   │   │   └── Kitchen.jsx     # Pantalla de cocina
│   │   ├── App.jsx             # Componente principal + Router
│   │   └── main.jsx            # Punto de entrada
│   ├── package.json            # Dependencias NPM
│   ├── vite.config.js          # Configuración de Vite
│   └── node_modules/           # Dependencias instaladas
│
├── database/                   # Scripts SQL
│   ├── schema.sql              # Estructura de BD
│   └── seeds.sql               # Datos iniciales
│
├── install.php                 # Instalador automático de BD
└── README.md                   # Documentación
```

## ⚙️ Instalación y Configuración

### Requisitos Previos

1. **XAMPP** (Apache + MySQL + PHP)
   - Descargar desde: https://www.apachefriends.org/
   - Versión recomendada: 8.0+

2. **Node.js** (v16 o superior)
   - Descargar desde: https://nodejs.org/
   - Incluye npm

3. **Visual Studio Code** (Editor de código - Opcional)
   - Descargar desde: https://code.visualstudio.com/

4. **Git** (Control de versiones - Opcional)
   - Descargar desde: https://git-scm.com/

### Instalación Paso a Paso

#### 1. Copiar el Proyecto
```bash
# Copiar toda la carpeta siger_project a:
C:\xampp\htdocs\siger_project
```

#### 2. Iniciar Servicios
- Abrir XAMPP Control Panel
- Iniciar **Apache** (puerto 80)
- Iniciar **MySQL** (puerto 3306)

#### 3. Configurar Base de Datos

**Opción A: Instalador Automático (Recomendada)**
```
1. Abrir: http://localhost/siger_project/install.php
2. Hacer clic en "Instalar Base de Datos"
3. Esperar confirmación
```

**Opción B: Manual (phpMyAdmin)**
```
1. Abrir: http://localhost/phpmyadmin
2. Ejecutar database/schema.sql
3. Ejecutar database/seeds.sql
```

#### 4. Instalar Dependencias React
```bash
# Opción A: Desde la raíz del proyecto (recomendado)
cd C:\xampp\htdocs\siger_project
npm run install

# Opción B: Desde la carpeta frontend
cd C:\xampp\htdocs\siger_project\frontend
npm install
```

#### 5. Iniciar Frontend React
```bash
# Opción A: Desde la raíz del proyecto (recomendado)
cd C:\xampp\htdocs\siger_project
npm run dev

# Opción B: Desde la carpeta frontend
cd C:\xampp\htdocs\siger_project\frontend
npm run dev
```

El servidor de desarrollo se ejecutará en `http://localhost:3000`

### 🎯 Acceso al Sistema

**Frontend React:** http://localhost:3000

**API Backend:** http://localhost/siger_project/api/

### 👤 Credenciales de Prueba

| Usuario | Contraseña | Rol |
|---------|------------|-----|
| admin | admin123 | Administrador |
| cocina | cocina123 | Cocina |
| mesero | mesero123 | Mesero |

## 🚀 Uso del Sistema

### Acceso Rápido

- **Aplicación Principal**: http://localhost:3000
- **Panel de Admin**: http://localhost:3000/admin
- **Pantalla de Cocina**: http://localhost:3000/kitchen
- **Tablet/Kiosk**: http://localhost:3000/tablet

### Flujo de Autenticación

1. Accede a http://localhost:3000/login
2. Ingresa credenciales (ver sección de usuarios de prueba)
3. El sistema te redirigirá según tu rol

## 📊 Funcionalidades Principales

### Módulo Home
- ✅ Landing page con menú interactivo
- ✅ Filtrado de productos por categoría
- ✅ Vista de productos con imágenes y precios
- ✅ Interfaz responsive

### Módulo Admin
- ✅ Gestión de usuarios (CRUD)
- ✅ Gestión de inventario/productos (CRUD)
- ✅ Reportes de ventas y estadísticas
- ✅ Dashboard con métricas en tiempo real
- ✅ Gestión de reservas

### Módulo Tablet (Kiosk)
- ✅ Menú digital interactivo
- ✅ Carrito de compras
- ✅ Envío de pedidos a cocina
- ✅ Interfaz táctil optimizada

### Módulo Kitchen
- ✅ Vista en tiempo real de pedidos
- ✅ Actualización de estados (Pendiente → Preparando → Listo)
- ✅ Polling automático cada 5 segundos
- ✅ Código de colores por estado
- ✅ Alertas de tiempo

## 🔧 Desarrollo

### Arquitectura

```
Frontend (React) ←──(HTTP)──→ Backend (PHP API) ←──(MySQL)──→ Database
    :3000                       :80/siger_project              siger_db
```

### Estructura de la API

Todos los endpoints retornan JSON con el formato:

```json
{
    "success": true/false,
    "message": "Mensaje descriptivo",
    "data": {...},
    "timestamp": "2026-02-04 12:00:00"
}
```

### Endpoints Disponibles

#### Autenticación
- `POST /api/controllers/auth.php?action=login` - Iniciar sesión
- `POST /api/controllers/auth.php?action=logout` - Cerrar sesión
- `GET /api/controllers/auth.php` - Verificar sesión

#### Menú
- `GET /api/controllers/get_menu.php` - Obtener menú completo
- `GET /api/controllers/get_menu.php?category=entradas` - Filtrar por categoría

#### Pedidos
- `POST /api/controllers/place_order.php` - Crear pedido
- `GET /api/controllers/kitchen_sync.php` - Obtener pedidos activos
- `PUT /api/controllers/kitchen_sync.php` - Actualizar estado de pedido

#### Reportes
- `GET /api/controllers/reports.php?type=sales` - Reporte de ventas
- `GET /api/controllers/reports.php?type=dashboard` - Datos del dashboard

### Agregar Nuevos Componentes React

1. Crear archivo en `frontend/src/pages/NuevoComponente.jsx`
2. Importar en `App.jsx`
3. Agregar ruta en React Router:
```jsx
<Route path="/nuevo" element={<NuevoComponente />} />
```

### Configuración del Proxy (Vite)

El frontend en desarrollo (puerto 3000) hace peticiones al backend PHP (puerto 80) a través de un proxy configurado en `vite.config.js`:

```javascript
proxy: {
  '/api': {
    target: 'http://localhost/siger_project',
    changeOrigin: true
  }
}
```

## 🐛 Depuración

### Errores Comunes

#### Backend (PHP API)

1. **"Error de conexión a la base de datos"**
   - ✅ Verificar que MySQL esté corriendo en XAMPP
   - ✅ Verificar credenciales en `config/db.php`
   - ✅ Confirmar que existe la base de datos `siger_db`

2. **"404 Not Found" en API**
   - ✅ Verificar que el proyecto esté en `C:\xampp\htdocs\siger_project`
   - ✅ Verificar que Apache esté corriendo
   - ✅ Revisar rutas en `vite.config.js` (proxy)

3. **"Headers already sent"**
   - ✅ Verificar que no haya espacios antes de `<?php` en archivos PHP
   - ✅ Revisar salidas echo/print antes de jsonResponse()

#### Frontend (React)

1. **"Cannot GET /api/..."**
   - ✅ Verificar que el backend PHP esté corriendo
   - ✅ Revisar configuración del proxy en `vite.config.js`
   - ✅ Confirmar que la ruta del endpoint sea correcta

2. **"npm run dev" no funciona**
   - ✅ Ejecutar `npm install` en `/frontend`
   - ✅ Verificar que Node.js esté instalado (v16+)
   - ✅ Revisar que el puerto 3000 esté libre

3. **Cambios no se reflejan**
   - ✅ Guardar archivos (Vite usa hot-reload automático)
   - ✅ Limpiar caché del navegador (Ctrl + F5)
   - ✅ Reiniciar servidor de desarrollo

### Modo Debug

**Backend:**
Activar en `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**Frontend:**
Ver consola del navegador (F12) para errores de React y llamadas a la API.

### Logs

- **Apache**: `C:\xampp\apache\logs\error.log`
- **MySQL**: `C:\xampp\mysql\data\*.err`
- **React**: Consola del navegador (DevTools)

## 📦 Dependencias del Proyecto

### Frontend (React)
```json
{
  "react": "^18.2.0",
  "react-dom": "^18.2.0",
  "react-router-dom": "^6.21.0",
  "axios": "^1.6.5"
}
```

### Backend (PHP)
- PHP 7.4+
- Extensión PDO MySQL
- Apache mod_rewrite (opcional)

### Base de Datos
- MySQL 8.0+
- Collation: utf8mb4_unicode_ci

## 📝 Próximas Mejoras

- [ ] Implementar autenticación con JWT
- [ ] Agregar WebSockets para actualizaciones en tiempo real
- [ ] Sistema de notificaciones push
- [ ] Reportes PDF exportables
- [ ] Integración con pasarelas de pago
- [ ] Modo oscuro en React
- [ ] Progressive Web App (PWA)
- [ ] Dockerización del proyecto

## 👥 Equipo de Desarrollo

**Grupo 8** - Proyecto SIGER

## 📄 Licencia

Este proyecto es de código abierto para fines educativos.

---

**Última actualización**: Febrero 2026  
**Versión**: 2.0 (React + PHP API)
