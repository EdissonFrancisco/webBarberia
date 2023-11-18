<?php
  // Establecer la zona horaria por defecto
  date_default_timezone_set("America/Bogota");
?>

<h1 class="nombre-pagina">
  <span class="linea linea-izq"></span>
  Crear Nueva Cita
  <span class="linea linea-der"></span>
</h1>

<main id="app" class="contenedor">

  <nav class="tabs">
    <button class="actual" type="button" data-paso="1">Servicios</button>
    <button type="button" data-paso="2">Informacion cita</button>
    <button type="button" data-paso="3">Resumen</button>
  </nav>

  <section id="paso-1" class="seccion">
    <h2>Servicios</h2>
    <p class="text-center">elige tus servicios a continuacion</p>
    <div id="servicios" class="listado-servicios"></div>
  </section>
  <section id="paso-2" class="seccion">
    <h2>Tus Datos y Fecha</h2>
    <p class="text-center">elige la fecha y coloca tus datos</p>

    <form class="formulario">
      <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
          type="text" 
          id="nombre" 
          name="nombre" 
          placeholder="tu nombre"
          value="<?php echo $nombre ?>" 
          readonly 
        >
      </div>
      <div class="campo">
        <label for="fecha">Fecha </label>
        <input type="date" id="fecha" name="fecha" min="<?php date("Y-m-d", strtotime('+1 day')) ?>" >
      </div>
      <div class="campo">
        <label for="hora">Hora</label>
        <input type="time" id="hora" name="hora" >
      </div>
    </form>
  </section>
  <section id="paso-3" class="seccion contenido-resumen">
    <h2>Resumen</h2>
    <p class="text-center">Verifica la informacion que este correcta</p>
    <div id="resumen-cita" class="listado-servicios"></div>
  </section>

  <div class="paginacion">
    <button id="anterior" class="boton" >&laquo; Anterior </button>
    <button id="siguiente" class="boton" >Siguiente &raquo;</button>
  </div>
</main>

<?php $script = "
  <script src='build/js/app.js'></script>
" ?>