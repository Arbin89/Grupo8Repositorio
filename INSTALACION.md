# ⚡ GUÍA RÁPIDA DE INSTALACIÓN - SIGER

Sistema de gestión de restaurantes con **React + PHP API + MySQL**

## 🎯 Pasos Rápidos

### 1. Instalar Software Requerido

#### XAMPP (Apache + MySQL + PHP)
- Descargar de: https://www.apachefriends.org/
- Instalar en `C:\xampp`
- Iniciar Apache y MySQL

#### Node.js (JavaScript Runtime)
- Descargar de: https://nodejs.org/
- Versión recomendada: LTS (v16 o superior)
- Incluye npm automáticamente

### 2. Copiar el Proyecto
```bash
# Copiar toda la carpeta siger_project a:
C:\xampp\htdocs\siger_project
```

### 3. Configurar Base de Datos

**Opción A: Automática con Instalador (Recomendada)**
1. Iniciar XAMPP → Apache y MySQL
2. Abrir navegador: http://localhost/siger_project/install.php
3. Click en "Instalar Base de Datos"
4. Esperar confirmación ✅

**Opción B: Manual con phpMyAdmin**
1. Abrir: http://localhost/phpmyadmin
2. Click en "SQL"
3. Copiar y pegar TODO el contenido de `database/schema.sql`
4. Click en "Continuar"
5. Copiar y pegar TODO el contenido de `database/seeds.sql`
6. Click en "Continuar"

### 4. Instalar Dependencias React
```bash
# Opción A: Desde la raíz del proyecto (más fácil)
cd C:\xampp\htdocs\siger_project
npm run install

# Opción B: Desde la carpeta frontend
cd C:\xampp\htdocs\siger_project\frontend
npm install
```

### 5. Iniciar el Frontend React
```bash
# Opción A: Desde la raíz del proyecto (más fácil)
cd C:\xampp\htdocs\siger_project
npm run dev

# Opción B: Desde la carpeta frontend
cd C:\xampp\htdocs\siger_project\frontend
npm run dev
```

Verás algo como:
```
  VITE v5.4.21  ready in 500 ms

  ➜  Local:   http://localhost:3000/
  ➜  Network: use --host to expose
```

### 6. ¡Listo! 🎉

Abre tu navegador en: **http://localhost:3000**

---

## 🔐 Credenciales de Prueba

### Administrador
- **Usuario**: `admin`
- **Contraseña**: `admin123`
- **Acceso**: Dashboard completo, gestión de usuarios, inventario, reportes

### Cocina
- **Usuario**: `cocina`
- **Contraseña**: `cocina123`
- **Acceso**: Pantalla de pedidos en tiempo real

### Mesero
- **Usuario**: `mesero`
- **Contraseña**: `mesero123`
- **Acceso**: Toma de pedidos, gestión de mesas

---

## 🔗 URLs del Sistema

| Servicio | URL | Descripción |
|----------|-----|-------------|
| **Frontend React** | http://localhost:3000 | Aplicación principal |
| **Backend API** | http://localhost/siger_project/api/ | Endpoints PHP |
| **Instalador BD** | http://localhost/siger_project/install.php | Configurar base de datos |
| **phpMyAdmin** | http://localhost/phpmyadmin | Administrar MySQL |

---

## ⚠️ Solución de Problemas

### ❌ Error: "No se puede conectar a la base de datos"

**Causas:**
- MySQL no está corriendo
- Base de datos `siger_db` no existe
- Credenciales incorrectas

**Solución:**
```bash
1. Abrir XAMPP Control Panel
2. Verificar que MySQL tenga luz verde (Running)
3. Si no existe la BD, usar install.php
4. Verificar config/db.php (usuario: root, password: vacío)
```

### ❌ Error: "404 Not Found"

**Causas:**
- Proyecto no está en la carpeta correcta
- Apache no está corriendo

**Solución:**
```bash
1. Verificar ruta: C:\xampp\htdocs\siger_project
2. Abrir XAMPP y verificar Apache con luz verde
3. Probar: http://localhost (debe mostrar panel de XAMPP)
```

### ❌ Error: "npm: command not found"

**Causa:**
- Node.js no está instalado

**Solución:**
```bash
1. Instalar Node.js desde https://nodejs.org/
2. Reiniciar PowerShell/CMD
3. Verificar: node --version
4. Verificar: npm --version
```

### ❌ Error: "Port 3000 is already in use"

**Causa:**
- Otra aplicación usa el puerto 3000

**Solución:**
```bash
# Opción 1: Matar el proceso
netstat -ano | findstr :3000
taskkill /PID <número_del_PID> /F

# Opción 2: Usar otro puerto
npm run dev -- --port 3001
```

### ❌ Error: "npm install" falla

**Causas:**
- Conexión a internet lenta/cortada
- Caché corrupto de npm

**Solución:**
```bash
# Limpiar caché de npm
npm cache clean --force

# Reinstalar
npm install
```

### ❌ Las imágenes de productos no cargan

**Causa:**
- Carpeta uploads/ no existe o sin permisos

**Solución:**
```bash
1. Verificar que exista: C:\xampp\htdocs\siger_project\api\uploads\products\
2. Crear la carpeta si no existe
3. Dar permisos de escritura (clic derecho → Propiedades → Seguridad)
```

### ❌ Pantalla en blanco al abrir React

**Causas:**
- Errores de JavaScript
- Proxy mal configurado

**Solución:**
```bash
1. Abrir DevTools (F12)
2. Ver consola de errores
3. Verificar que Apache esté corriendo (backend)
4. Revisar vite.config.js (proxy debe apuntar a http://localhost/siger_project)
```

---

## 📋 Checklist de Instalación

Verifica que todo esté listo:

- [ ] ✅ XAMPP instalado en `C:\xampp`
- [ ] ✅ Node.js instalado (verificar con `node --version`)
- [ ] ✅ Apache corriendo (puerto 80) - luz verde en XAMPP
- [ ] ✅ MySQL corriendo (puerto 3306) - luz verde en XAMPP
- [ ] ✅ Proyecto copiado en `C:\xampp\htdocs\siger_project`
- [ ] ✅ Base de datos `siger_db` creada
- [ ] ✅ `schema.sql` ejecutado
- [ ] ✅ `seeds.sql` ejecutado
- [ ] ✅ `npm install` ejecutado en `frontend/`
- [ ] ✅ `npm run dev` corriendo sin errores
- [ ] ✅ http://localhost:3000 abre correctamente
- [ ] ✅ Login funciona con credenciales de prueba

---

## 🚀 Comandos Útiles

### Backend (PHP)

```bash
# Ver logs de Apache
C:\xampp\apache\logs\error.log

# Reiniciar Apache
# Desde XAMPP Control Panel: Stop → Start
```

### Frontend (React)

```bash
# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev

# Build para producción
npm run build

# Ver versión de Node
node --version

# Ver versión de npm
npm --version
```

### Base de Datos

```sql
-- Ver todas las bases de datos
SHOW DATABASES;

-- Usar base de datos SIGER
USE siger_db;

-- Ver todas las tablas
SHOW TABLES;

-- Ver usuarios
SELECT * FROM users;

-- Ver productos
SELECT * FROM products;
```

---

## 🔄 Actualizar el Proyecto

Si descargas una nueva versión:

```bash
# 1. Copiar nuevos archivos a htdocs
# 2. Actualizar dependencias React
cd C:\xampp\htdocs\siger_project\frontend
npm install

# 3. Reiniciar servidor dev
npm run dev
```

---

## 📱 Acceso desde Otros Dispositivos (Red Local)

Para acceder desde tablets/celulares en la misma red:

```bash
# 1. Obtener IP de tu PC
ipconfig
# Buscar "IPv4 Address", ejemplo: 192.168.1.100

# 2. Abrir en el dispositivo:
http://192.168.1.100:3000

# 3. Configurar Vite para exponer en red:
npm run dev -- --host
```

---

## 💡 Consejos Pro

1. **Usar VS Code**: Editor recomendado con extensiones:
   - ES7+ React/Redux/React-Native snippets
   - PHP Intelephense
   - MySQL (de Jun Han)

2. **Atajos de teclado**:
   - `Ctrl + C` en terminal = Detener servidor
   - `Ctrl + Shift + R` = Recarga forzada del navegador
   - `F12` = Abrir DevTools

3. **Modo desarrollo**:
   - Los cambios en React se ven automáticamente (hot-reload)
   - Los cambios en PHP requieren refrescar el navegador

4. **Backup de BD**:
   - Exportar desde phpMyAdmin regularmente
   - Guardar en `database/backup_FECHA.sql`

---

**¿Problemas no resueltos?**  
Revisa el [README.md](README.md) principal para más detalles técnicos.

**Última actualización**: Febrero 2026  
**Versión**: 2.0 (React + PHP API)
