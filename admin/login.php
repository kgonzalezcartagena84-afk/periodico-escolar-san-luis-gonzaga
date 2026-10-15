<?php
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim((string)($_POST['usuario'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($usuario === '' || $password === '') {
        admin_set_message('error', 'Debes ingresar usuario y contraseña.');
    } else {
        $stmt = admin_db()->prepare('SELECT id, usuario, nombre, password_hash FROM usuarios_admin WHERE usuario = ? AND activo = 1 LIMIT 1');
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $admin = $resultado->fetch_assoc();

            if (password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_id'] = (int)$admin['id'];
                $_SESSION['admin_usuario'] = $admin['usuario'];
                $_SESSION['admin_nombre'] = $admin['nombre'];

                header('Location: dashboard.php');
                exit;
            }
        }

        admin_set_message('error', 'Usuario o contraseña incorrectos.');
    }
}

$flash = admin_get_message();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1d4ed8);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
        }
        .login-box {
            position: relative;
            width: min(90%, 420px);
            background: #fff;
            padding: 32px 28px;
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.2);
        }
        .back-link {
            position: absolute;
            top: 18px;
            left: 18px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        h1 {
            margin-top: 0;
            margin-bottom: 10px;
            text-align: center;
            font-size: 2rem;
        }
        p {
            text-align: center;
            color: #475569;
            margin-bottom: 22px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
        }
        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 1rem;
        }
        button {
            width: 100%;
            border: none;
            background: #2563eb;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
        }
        .alert {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.95rem;
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .demo {
            margin-top: 18px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 10px 12px;
            color: #1e3a8a;
            font-size: 0.9rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <a href="../index.html" class="back-link">&#8592; Volver al inicio</a>
        <h1>Administrador</h1>
        <p>Accede para modificar menús, proyectos y noticias.</p>

        <?php if ($flash): ?>
            <div class="alert"><?php echo htmlspecialchars($flash['mensaje']); ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="admin" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>

            <button type="submit">Iniciar sesión</button>
        </form>

        <div class="demo">
            Solo usuarios autorizados
        </div>
    </div>
</body>
</html>
