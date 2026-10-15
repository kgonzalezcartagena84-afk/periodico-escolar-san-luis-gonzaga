<?php
require __DIR__ . '/config.php';
admin_require_login();

$conn = admin_db();

function admin_save_news_image(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $message = match ($file['error'] ?? UPLOAD_ERR_NO_FILE) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'La imagen supera el tamaño permitido por el servidor.',
            UPLOAD_ERR_PARTIAL => 'La imagen no se cargó completa. Inténtalo de nuevo.',
            UPLOAD_ERR_NO_FILE => 'Selecciona una imagen para cargar.',
            default => 'No se pudo cargar la imagen. Inténtalo de nuevo.',
        };
        throw new RuntimeException($message);
    }

    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException('La imagen debe pesar máximo 5 MB.');
    }

    $imageInfo = @getimagesize($file['tmp_name']);
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];
    $mime = $imageInfo['mime'] ?? '';

    if (!isset($extensions[$mime])) {
        throw new RuntimeException('Formato no válido. Usa una imagen JPG, PNG, GIF o WebP.');
    }

    $directory = dirname(__DIR__) . '/img/noticias';
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('No se pudo crear la carpeta para guardar las imágenes.');
    }

    $filename = 'noticia-' . bin2hex(random_bytes(12)) . '.' . $extensions[$mime];
    if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
        throw new RuntimeException('No se pudo guardar la imagen en el servidor.');
    }

    return '../img/noticias/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $titulo = trim((string)($_POST['titulo'] ?? ''));
    $resumen = trim((string)($_POST['resumen'] ?? ''));
    $contenido = trim((string)($_POST['contenido'] ?? ''));
    $imagen = trim((string)($_POST['imagen'] ?? ''));
    $enlace = trim((string)($_POST['enlace'] ?? ''));
    $fecha = trim((string)($_POST['fecha'] ?? ''));
    $orden = isset($_POST['orden']) ? (int)$_POST['orden'] : 0;
    $activo = isset($_POST['activo']) ? 1 : 0;

    try {
        if (isset($_FILES['imagen_archivo']) && ($_FILES['imagen_archivo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $imagen = admin_save_news_image($_FILES['imagen_archivo']);
        }
    } catch (RuntimeException $exception) {
        admin_set_message('error', $exception->getMessage());
        header('Location: noticias.php' . ($id > 0 ? '?editar=' . $id : ''));
        exit;
    }

    if ($titulo === '' || $resumen === '' || $contenido === '' || $fecha === '') {
        admin_set_message('error', 'Título, resumen, contenido y fecha son obligatorios.');
    } else {
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE noticias_admin SET titulo = ?, resumen = ?, contenido = ?, imagen = ?, enlace = ?, fecha = ?, orden = ?, activo = ? WHERE id = ?');
            $stmt->bind_param('ssssssiii', $titulo, $resumen, $contenido, $imagen, $enlace, $fecha, $orden, $activo, $id);
            $stmt->execute();
            admin_set_message('success', 'Noticia actualizada.');
        } else {
            $stmt = $conn->prepare('INSERT INTO noticias_admin (titulo, resumen, contenido, imagen, enlace, fecha, orden, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('ssssssii', $titulo, $resumen, $contenido, $imagen, $enlace, $fecha, $orden, $activo);
            $stmt->execute();
            admin_set_message('success', 'Noticia creada correctamente.');
        }
    }

    header('Location: noticias.php');
    exit;
}

$edit = null;
if (isset($_GET['editar'])) {
    $id = (int)$_GET['editar'];
    $stmt = $conn->prepare('SELECT * FROM noticias_admin WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc();
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $conn->query('DELETE FROM noticias_admin WHERE id = ' . $id);
    admin_set_message('success', 'Noticia eliminada.');
    header('Location: noticias.php');
    exit;
}

$noticias = $conn->query('SELECT * FROM noticias_admin ORDER BY orden ASC, fecha DESC');
$flash = admin_get_message();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar noticias</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; background: #f8fafc; color: #0f172a; }
        .topbar { background: #0f172a; color: white; padding: 18px 28px; display:flex; justify-content:space-between; align-items:center; }
        .topbar a { color: white; text-decoration: none; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 22px; border: 1px solid #e2e8f0; border-radius: 15px; box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
        form { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap: 16px; }
        .field { display:flex; flex-direction:column; gap:8px; }
        .field input, .field textarea { padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; }
        .field textarea { min-height: 120px; resize: vertical; }
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
    <h2>Noticias</h2>
    <a href="dashboard.php">Volver al panel</a>
</div>

<div class="container">
    <?php if ($flash): ?>
        <div class="alert <?php echo htmlspecialchars($flash['tipo']); ?>"><?php echo htmlspecialchars($flash['mensaje']); ?></div>
    <?php endif; ?>

    <div class="card">
        <h3><?php echo $edit ? 'Editar noticia' : 'Agregar noticia'; ?></h3>
        <form method="post" action="noticias.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo (int)($edit['id'] ?? 0); ?>">

            <div class="field">
                <label for="titulo">Título</label>
                <input id="titulo" name="titulo" value="<?php echo htmlspecialchars($edit['titulo'] ?? ''); ?>" required>
            </div>

            <div class="field">
                <label for="fecha">Fecha</label>
                <input id="fecha" type="date" name="fecha" value="<?php echo htmlspecialchars($edit['fecha'] ?? date('Y-m-d')); ?>" required>
            </div>

            <div class="field">
                <label for="orden">Orden</label>
                <input id="orden" type="number" name="orden" value="<?php echo (int)($edit['orden'] ?? 0); ?>" required>
            </div>

            <div class="field" style="grid-column: 1 / -1;">
                <label for="resumen">Resumen</label>
                <textarea id="resumen" name="resumen" required><?php echo htmlspecialchars($edit['resumen'] ?? ''); ?></textarea>
            </div>

            <div class="field" style="grid-column: 1 / -1;">
                <label for="contenido">Contenido</label>
                <textarea id="contenido" name="contenido" required><?php echo htmlspecialchars($edit['contenido'] ?? ''); ?></textarea>
            </div>

            <div class="field" style="grid-column: 1 / -1;">
                <label for="imagen_archivo">Imagen de la noticia</label>
                <input id="imagen_archivo" name="imagen_archivo" type="file" accept="image/jpeg,image/png,image/gif,image/webp">
                <small>Elige una imagen JPG, PNG, GIF o WebP de máximo 5 MB. Al editar, puedes dejarlo vacío para conservar la imagen actual.</small>
                <img id="imagen_preview" alt="Vista previa de la imagen" style="display:none; max-width:240px; max-height:160px; object-fit:cover; border-radius:8px;">
                <label for="imagen">O pega una ruta o URL</label>
                <input id="imagen" name="imagen" value="<?php echo htmlspecialchars($edit['imagen'] ?? ''); ?>" placeholder="../img/mi-imagen.jpg o https://...">
            </div>

            <div class="field" style="grid-column: 1 / -1;">
                <label for="enlace">Enlace</label>
                <input id="enlace" name="enlace" value="<?php echo htmlspecialchars($edit['enlace'] ?? ''); ?>" placeholder="html/noticias.html">
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
                    <a class="btn secondary" href="noticias.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top: 30px;">
        <h3>Listado actual</h3>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Orden</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $noticias->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                        <td><?php echo (int)$row['orden']; ?></td>
                        <td><?php echo (int)$row['activo'] === 1 ? 'Activo' : 'Inactivo'; ?></td>
                        <td>
                            <a class="btn secondary" href="noticias.php?editar=<?php echo (int)$row['id']; ?>">Editar</a>
                            <a class="btn danger" href="noticias.php?eliminar=<?php echo (int)$row['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta noticia?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    const imageInput = document.getElementById('imagen');
    const uploadInput = document.getElementById('imagen_archivo');
    const imagePreview = document.getElementById('imagen_preview');

    function showImagePreview(source) {
        if (!source) {
            imagePreview.removeAttribute('src');
            imagePreview.style.display = 'none';
            return;
        }

        let previewSource = source.trim();
        if (!/^(https?:)?\/\//i.test(previewSource) && !previewSource.startsWith('data:')) {
            if (previewSource.startsWith('img/')) previewSource = '../' + previewSource;
            else if (previewSource.startsWith('/img/')) previewSource = '..' + previewSource;
        }
        imagePreview.src = previewSource;
        imagePreview.style.display = 'block';
    }

    imageInput.addEventListener('input', () => {
        uploadInput.value = '';
        showImagePreview(imageInput.value);
    });

    uploadInput.addEventListener('change', () => {
        if (uploadInput.files.length > 0) {
            imageInput.value = '';
            showImagePreview(URL.createObjectURL(uploadInput.files[0]));
        }
    });

    showImagePreview(imageInput.value);
</script>
</body>
</html>
