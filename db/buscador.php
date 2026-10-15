<?php include("conexiondb.php"); // Ajusta si tu conexión está fuera de /db ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Buscar_noticia</title>
   <link rel="stylesheet" href="/css/diseño_noti.css">
  <link rel="stylesheet" href="../css/diseñophp.css"> <!-- Ajusta la ruta si estás en /db -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <!-- Para el ícono de flecha -->
</head>
<body>

  <div class="titulo-buscador">
  <h1>Centro de Noticias San Luis Gonzaga</h1>
</div>

<div class="buscador-wrapper">
  <form method="get" class="buscador-form">
    <input type="text" name="busquedad" placeholder="🔍 Buscar noticia..." class="buscador-input">
    <input type="submit" name="enviar" value="Buscar" class="buscador-boton">
  </form>
</div>


  <div class="contenedor-noticias">
    <?php
    $busquedad = isset($_GET['busquedad']) ? $_GET['busquedad'] : '';

if ($busquedad !== '') {
  $consulta = $conn->query("SELECT * FROM noticias WHERE titulo LIKE '%$busquedad%'");
} else {
  $consulta = $conn->query("SELECT * FROM noticias ORDER BY fecha DESC");
}


      while ($row = $consulta->fetch_array()) {
        $ruta = $row['enlace']; // ejemplo: 'noticias/noti6.html'
        $ruta_completa = realpath(__DIR__ . '/../' . $ruta); // sube desde /db a raíz

        echo '<div class="news">
                <div class="cuadro-noticia">
                  <div class="noticia-recorte">
                    <div class="noticia-contenedor">
                      <div class="noticia-imagen">
                        <img src="../' . htmlspecialchars($row['imagen']) . '" alt="' . htmlspecialchars($row['titulo']) . '">
                      </div>
                      <div class="noticia-detalle">
                        <h3>' . htmlspecialchars($row['titulo']) . '</h3>
                        <p>' . htmlspecialchars($row['contenido']) . '</p>
                        <div class="noticia-acciones">
                          <span class="noticia-fecha">Publicado: ' . htmlspecialchars($row['fecha']) . '</span>';

        if ($ruta_completa && file_exists($ruta_completa)) {
          echo '<a href="../' . htmlspecialchars($row['enlace']) . '" class="noticia-boton">Ver más detalles <i class="fas fa-arrow-right"></i></a>';
        } else {
          echo '<span class="noticia-boton deshabilitado">Próximamente</span>';
        }

        echo         '</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
      }
    
    ?>
  </div>

</body>
</html>
