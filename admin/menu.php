<?php
require __DIR__ . '/config.php';
admin_require_login();

$conn = admin_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $slug = trim((string)($_POST['slug'] ?? ''));
    $titulo = trim((string)($_POST['titulo'] ?? ''));
    $texto = trim((string)($_POST['texto'] ?? ''));
    $enlace = trim((string)($_POST['enlace'] ?? ''));
    $orden = isset($_POST['orden']) ? (int)$_POST['orden'] : 0;
    $activo = isset($_POST['activo']) ? 1 : 0;

    if ($slug === '' || $titulo === '' || $texto === '' || $enlace === '') {
        admin_set_message('error', 'Todos los campos del menú son obligatorios.');
    } else {
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE menu_principal SET slug = ?, titulo = ?, texto = ?, enlace = ?, orden = ?, activo = ? WHERE id = ?');
            $stmt->bind_param('sssssii', $slug, $titulo, $texto, $enlace, $orden, $activo, $id);
            $stmt->execute();
            admin_set_message('success', 'Menú actualizado correctamente.');
        } else {
            $stmt = $conn->prepare('INSERT INTO menu_principal (slug, titulo, texto, enlace, orden, activo) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('ssssii', $slug, $titulo, $texto, $enlace, $orden, $activo);
            $stmt->execute();
            admin_set_message('success', 'Elemento agregado al menú.');
        }
    }

    header('Location: menu.php');
    exit;
}

$edit = null;
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $stmt = $conn->prepare('SELECT * FROM menu_principal WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query('DELETE FROM menu_principal WHERE id = ' . $id);
    admin_set_message('success', 'Elemento eliminado.');
    header('Location: menu.php');
    exit;
}

$items = $conn->query('SELECT * FROM menu_principal ORDER BY orden ASC, id ASC');
$flash = admin_get_message();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú principal</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; background: #f8fafc; color: #0f172a; }
        .topbar { background: #0f172a; color: white; padding: 18px 28px; display:flex; justify-content:space-between; align-items:center; }
        .topbar a { color: white; text-decoration: none; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 22px; border: 1px solid #e2e8f0; border-radius: 15px; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
        form { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap: 16px; }
        .field { display:flex; flex-direction:column; gap:8px; }
        .field input, .field textarea { padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; }
        .field textarea { min-height: 100px; resize: vertical; }
        .checkbox { display:flex; align-items:center; gap:10px; margin-top: 10px; }
        .btn { display:inline-block; margin-top:14px; padding:10px 16px; border:none; border-radius:10px; background:#2563eb; color:white; cursor:pointer; text-decoration:none; }
        .btn.secondary { background:#64748b; }
        .btn.danger { background:#dc2626; }
        table { width: 100%; border-collapse:collapse; margin-top: 24px; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #e2e8f0; text-align:left; }
        th { background:#f8fafc; }
        .alert { margin-bottom:18px; padding:12px 14px; border-radius:10px; }
        .alert.success { background:#dcfce7; color:#166534; }
        .alert.error { background:#fee2e2; color:#991b1b; }
    </style>
</head>
<body>
<div class="topbar">
    <h2>Menú principal</h2>
    <a href="dashboard.php">Volver al panel</a>
</div>

<div class="container">
    <?php if ($flash): ?>
        <div class="alert <?php echo htmlspecialchars($flash['tipo']); ?>"><?php echo htmlspecialchars($flash['mensaje']); ?></div>
    <?php endif; ?>

    <div class="card">
        <h3><?php echo $edit ? 'Editar elemento del menú' : 'Agregar elemento al menú'; ?></h3>
        <form method="post" action="menu.php">
            <input type="hidden" name="id" value="<?php echo (int)($edit['id'] ?? 0); ?>">

            <div class="field">
                <label for="slug">Slug</label>
                <input id="slug" name="slug" value="<?php echo htmlspecialchars($edit['slug'] ?? ''); ?>" required>
            </div>

            <div class="field">
                <label for="titulo">Título</label>
                <input id="titulo" name="titulo" value="<?php echo htmlspecialchars($edit['titulo'] ?? ''); ?>" required>
            </div>

            <div class="field">
                <label for="orden">Orden</label>
                <input id="orden" type="number" name="orden" value="<?php echo (int)($edit['orden'] ?? 0); ?>" required>
            </div>

            <div class="field" style="grid-column: 1 / -1;">
                <label for="texto">Texto</label>
                <textarea id="texto" name="texto" required><?php echo htmlspecialchars($edit['texto'] ?? ''); ?></textarea>
            </div>

            <div class="field" style="grid-column: 1 / -1;">
                <label for="enlace">Enlace</label>
                <input id="enlace" name="enlace" value="<?php echo htmlspecialchars($edit['enlace'] ?? ''); ?>" required>
            </div>

            <div class="field" style="grid-column: 1 / -1;">
                <label class="checkbox">
                    <input type="checkbox" name="activo" <?php echo (!empty($edit['activo']) || !isset($edit)) ? 'checked' : ''; ?>>
                    Mostrar en el sitio
                </label>
            </div>

            <div style="grid-column: 1 / -1;">
                <button class="btn" type="submit"><?php echo $edit ? 'Actualizar' : 'Guardar'; ?></button>
                <?php if ($edit): ?>
                    <a class="btn secondary" href="menu.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top: 30px;">
        <h3>Listado actual</h3>
        <table>
            <thead>
                <tr>
                    <th>Slug</th>
                    <th>Título</th>
                    <th>Orden</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $items->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['slug']); ?></td>
                        <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                        <td><?php echo (int)$row['orden']; ?></td>
                        <td><?php echo (int)$row['activo'] === 1 ? 'Activo' : 'Inactivo'; ?></td>
                        <td>
                            <a class="btn secondary" href="menu.php?editar=<?php echo (int)$row['id']; ?>">Editar</a>
                            <a class="btn danger" href="menu.php?eliminar=<?php echo (int)$row['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar este elemento del menú?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
