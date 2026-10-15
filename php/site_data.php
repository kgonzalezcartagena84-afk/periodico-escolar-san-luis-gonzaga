<?php
require __DIR__ . '/../admin/config.php';

header('Content-Type: application/json; charset=utf-8');

function site_json_response(array $payload): void
{
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

try {
    $conn = admin_db();

    $menuQuery = $conn->query('SELECT * FROM menu_principal WHERE activo = 1 ORDER BY orden ASC, id ASC');
    $proyectosQuery = $conn->query('SELECT * FROM proyectos WHERE activo = 1 ORDER BY orden ASC, id ASC');
    $noticiasQuery = $conn->query('SELECT * FROM noticias_admin WHERE activo = 1 ORDER BY orden ASC, fecha DESC, id DESC');

    $menu = [];
    while ($row = $menuQuery->fetch_assoc()) {
        $menu[] = [
            'id' => (int)$row['id'],
            'slug' => (string)$row['slug'],
            'titulo' => (string)$row['titulo'],
            'texto' => (string)$row['texto'],
            'enlace' => (string)$row['enlace'],
            'orden' => (int)$row['orden'],
            'activo' => (int)$row['activo'],
        ];
    }

    $proyectos = [];
    while ($row = $proyectosQuery->fetch_assoc()) {
        $proyectos[] = [
            'id' => (int)$row['id'],
            'titulo' => (string)$row['titulo'],
            'descripcion' => (string)$row['descripcion'],
            'imagen' => (string)$row['imagen'],
            'enlace' => (string)$row['enlace'],
            'orden' => (int)$row['orden'],
            'activo' => (int)$row['activo'],
        ];
    }

    $noticias = [];
    while ($row = $noticiasQuery->fetch_assoc()) {
        $noticias[] = [
            'id' => (int)$row['id'],
            'titulo' => (string)$row['titulo'],
            'resumen' => (string)$row['resumen'],
            'contenido' => (string)$row['contenido'],
            'imagen' => (string)$row['imagen'],
            'enlace' => (string)$row['enlace'],
            'fecha' => (string)$row['fecha'],
            'orden' => (int)$row['orden'],
            'activo' => (int)$row['activo'],
        ];
    }

    site_json_response([
        'menu' => $menu,
        'proyectos' => $proyectos,
        'noticias' => $noticias,
    ]);
} catch (Throwable $e) {
    site_json_response([
        'menu' => [],
        'proyectos' => [],
        'noticias' => [],
        'error' => $e->getMessage(),
    ]);
}
