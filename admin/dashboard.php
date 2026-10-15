<?php
require __DIR__ . '/config.php';
admin_require_login();

$conn = admin_db();

$totales = [
    'menu' => (int)$conn->query('SELECT COUNT(*) AS total FROM menu_principal')->fetch_assoc()['total'],
    'proyectos' => (int)$conn->query('SELECT COUNT(*) AS total FROM proyectos')->fetch_assoc()['total'],
    'noticias' => (int)$conn->query('SELECT COUNT(*) AS total FROM noticias_admin')->fetch_assoc()['total'],
];

$flash = admin_get_message();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Administrador</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }
        .topbar {
            background: #0f172a;
            color: white;
            padding: 18px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar h2 { margin: 0; }
        .nav a {
            color: white;
            text-decoration: none;
            margin-left: 16px;
            opacity: 0.9;
        }
        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-top: 24px;
        }
        .card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
            padding: 22px;
            border: 1px solid #e2e8f0;
        }
        .number {
            display: block;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .card a {
            text-decoration: none;
            color: #2563eb;
            font-weight: 700;
        }
        .alert {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 10px;
            font-weight: 600;
        }
        .alert.success { background: #dcfce7; color: #166534; }
        .alert.error { background: #fee2e2; color: #991b1b; }
        .panel {
            margin-top: 28px;
            background: white;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
            padding: 22px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        th, td {
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 10px;
            text-align: left;
        }
        th { background: #f8fafc; }
        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: white;
            background: #2563eb;
            font-weight: 700;
            margin-right: 8px;
        }
        .btn.secondary { background: #64748b; }
        .btn.danger { background: #dc2626; }
    </style>
</head>
<body>
    <div class="topbar">
        <h2>Panel de administración</h2>
        <div class="nav">
            <a href="menu.php">Menú</a>
            <a href="proyectos.php">Proyectos</a>
            <a href="noticias.php">Noticias</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>

    <div class="container">
        <?php if ($flash): ?>
            <div class="alert <?php echo htmlspecialchars($flash['tipo']); ?>"><?php echo htmlspecialchars($flash['mensaje']); ?></div>
        <?php endif; ?>

        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></h1>

        <div class="cards">
            <div class="card">
                <span class="number"><?php echo $totales['menu']; ?></span>
                <div>Elementos del menú</div>
                <a href="menu.php">Administrar</a>
            </div>
            <div class="card">
                <span class="number"><?php echo $totales['proyectos']; ?></span>
                <div>Proyectos</div>
                <a href="proyectos.php">Administrar</a>
            </div>
            <div class="card">
                <span class="number"><?php echo $totales['noticias']; ?></span>
                <div>Noticias</div>
                <a href="noticias.php">Administrar</a>
            </div>
        </div>

        <div class="panel">
            <h3>Acciones rápidas</h3>
            <p>
                <a class="btn" href="menu.php">Gestionar menú</a>
                <a class="btn secondary" href="proyectos.php">Gestionar proyectos</a>
                <a class="btn secondary" href="noticias.php">Gestionar noticias</a>
            </p>
        </div>
    </div>
</body>
</html>
