<main>
  <h1 class="nombre-pagina">
    <span class="linea linea-izq"></span>
    Registrarse
    <span class="linea linea-der"></span>
  </h1>
  <p class="descripcion-pagina">Llena el formulario para poder crear una cuenta</p>

  <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

  <form class="formulario formulario-crear" action="/crear-cuenta" method="POST">

    <div class="grupo">

      <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="tu nombre" value="<?php echo escapeHtml($usuario->nombre) ?>">
      </div>

      <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" placeholder="tu apellido" value="<?php echo escapeHtml($usuario->apellido) ?>">
      </div>

      <div class="campo">
        <label for="telefono">Telefono</label>
        <input type="tel" id="telefono" name="telefono" placeholder="tu telefono" value="<?php echo escapeHtml($usuario->telefono) ?>">
      </div>

    </div>

    <div class="campo">
      <label for="email">E-mail - Correo</label>
      <input type="email" id="email" name="email" placeholder="tu email" value="<?php echo escapeHtml($usuario->email) ?>">
    </div>

    <div class="campo">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="tu password">
    </div>



    <input class="boton" type="submit" value="Registrarse">
  </form>

  <div class="acciones">
    <a href="/">¿ya tienes cuenta? Inicia cesion</a>
    <a href="/olvidar">¿Olvidaste tu Clave?</a>
  </div>
</main>

