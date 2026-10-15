<?php
session_start();

const ADMIN_DB_HOST = 'localhost';
const ADMIN_DB_USER = 'root';
const ADMIN_DB_PASS = '';
const ADMIN_DB_NAME = 'proyectoperiodico';

function admin_db(): mysqli
{
    static $conn = null;

    if ($conn instanceof mysqli) {
        return $conn;
    }

    $conn = new mysqli(ADMIN_DB_HOST, ADMIN_DB_USER, ADMIN_DB_PASS, ADMIN_DB_NAME);

    if ($conn->connect_error) {
        throw new RuntimeException('No se pudo conectar a la base de datos: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');

    return $conn;
}

function admin_migrate(): void
{
    $conn = admin_db();

    $queries = [
        "CREATE TABLE IF NOT EXISTS usuarios_admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(80) NOT NULL UNIQUE,
            nombre VARCHAR(120) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            activo TINYINT(1) NOT NULL DEFAULT 1,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

        "CREATE TABLE IF NOT EXISTS menu_principal (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(80) NOT NULL UNIQUE,
            titulo VARCHAR(120) NOT NULL,
            texto TEXT NOT NULL,
            enlace VARCHAR(255) NOT NULL,
            orden INT NOT NULL DEFAULT 0,
            activo TINYINT(1) NOT NULL DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

        "CREATE TABLE IF NOT EXISTS proyectos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(180) NOT NULL,
            descripcion TEXT NOT NULL,
            imagen VARCHAR(255) DEFAULT NULL,
            enlace VARCHAR(255) DEFAULT NULL,
            orden INT NOT NULL DEFAULT 0,
            activo TINYINT(1) NOT NULL DEFAULT 1,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

        "CREATE TABLE IF NOT EXISTS noticias_admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(220) NOT NULL,
            resumen TEXT NOT NULL,
            contenido LONGTEXT NOT NULL,
            imagen VARCHAR(255) DEFAULT NULL,
            enlace VARCHAR(255) DEFAULT NULL,
            fecha DATE NOT NULL,
            orden INT NOT NULL DEFAULT 0,
            activo TINYINT(1) NOT NULL DEFAULT 1,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
    ];

    foreach ($queries as $sql) {
        $conn->query($sql);
    }
}

function admin_ensure_default_admin(): void
{
    admin_migrate();

    $conn = admin_db();
    $usuario = 'admin';
    $nombre = 'Administrador';
    $passwordHash = password_hash('admin123', PASSWORD_BCRYPT);

    $stmt = $conn->prepare('SELECT id, password_hash FROM usuarios_admin WHERE usuario = ? LIMIT 1');
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        if (empty($admin['password_hash'])) {
            $update = $conn->prepare('UPDATE usuarios_admin SET nombre = ?, password_hash = ? WHERE usuario = ?');
            $update->bind_param('sss', $nombre, $passwordHash, $usuario);
            $update->execute();
        }

        return;
    }

    $insert = $conn->prepare('INSERT INTO usuarios_admin (usuario, nombre, password_hash, activo) VALUES (?, ?, ?, 1)');
    $insert->bind_param('sss', $usuario, $nombre, $passwordHash);
    $insert->execute();
}

function admin_set_message(string $tipo, string $mensaje): void
{
    $_SESSION['admin_flash'] = [
        'tipo' => $tipo,
        'mensaje' => $mensaje
    ];
}

function admin_get_message(): ?array
{
    if (!isset($_SESSION['admin_flash'])) {
        return null;
    }

    $flash = $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);

    return $flash;
}

function admin_require_login(): void
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

function admin_redirect(string $ruta): void
{
    header('Location: ' . $ruta);
    exit;
}

try {
    admin_ensure_default_admin();
} catch (Throwable $exception) {
    error_log('Admin database bootstrap failed: ' . $exception->getMessage());

    $scriptPath = '/' . trim(str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if (str_contains($scriptPath, '/admin/')) {
        http_response_code(503);
        header('Content-Type: text/html; charset=utf-8');
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Administración no disponible</title>
            <style>
                body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; box-sizing: border-box; font-family: Arial, sans-serif; color: #172033; background: #f1f5f9; }
                main { width: min(100%, 520px); padding: 32px; box-sizing: border-box; border: 1px solid #dbe3ee; border-radius: 14px; background: #fff; box-shadow: 0 16px 40px rgba(15, 23, 42, .08); }
                h1 { margin: 0 0 12px; font-size: 1.5rem; }
                p { line-height: 1.6; color: #475569; }
                a { display: inline-block; margin-top: 12px; padding: 11px 16px; border-radius: 8px; background: #1d4ed8; color: #fff; text-decoration: none; font-weight: 700; }
            </style>
        </head>
        <body>
            <main>
                <h1>Administración temporalmente no disponible</h1>
                <p>No se pudo conectar con la base de datos. Inicia MySQL desde el panel de XAMPP y vuelve a cargar esta página.</p>
                <a href="../index.html">Volver al inicio</a>
            </main>
        </body>
        </html>
        <?php
        exit;
    }
}
