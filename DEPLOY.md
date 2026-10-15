# Despliegue a hosting gratuito

## Hosting recomendado

1. InfinityFree
   - Mejor para PHP + MySQL
   - Soporta PHP, MySQL, FTP
   - URL: https://infinityfree.com/

2. 000webhost
   - PHP + MySQL gratis
   - Más simple para empezar
   - URL: https://www.000webhost.com/

3. GitHub Pages
   - Solo para sitios estáticos
   - No sirve para PHP/MySQL
   - Útil solo si se convierte el front-end a estático

## Qué subir al hosting

- Todos los archivos del proyecto, excepto la base local.
- La base de datos se importa desde `proyectoperiodico.sql`.
- La configuración de conexión usa `localhost`, usuario `root` y contraseña vacía; en hosting gratuito debes cambiarla por los valores reales del proveedor.

## Cambios recomendados antes de subir

- Cambia en `admin/config.php` las credenciales de base de datos.
- Cambia en `php/conexion.php` y `db/conexiondb.php` la conexión de MySQL.
- Ajusta rutas con `https://` si usas recursos externos.

## Paso de subida

1. Crear cuenta en InfinityFree o 000webhost.
2. Subir la carpeta del proyecto por FTP o File Manager.
3. Crear la base de datos en el panel del hosting.
4. Importar `proyectoperiodico.sql`.
5. Ajustar credenciales de conexión.
6. Entrar a la URL pública del hosting.

## Credenciales por defecto del sitio

- Usuario administrador: `admin`
- Contraseña: `admin123`
