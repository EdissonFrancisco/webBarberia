<main>
  <h1 class="nombre-pagina">
    <span class="linea linea-izq"></span>
    Recuperar pasword
    <span class="linea linea-der"></span>
  </h1>
  <p class="descripcion-pagina">Coloca su nuevo password</p>

  <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

  <?php   if ($error) return; ?>
  <form class="formulario" method="POST">

    <div class="campo">
      <label for="password">E-mail</label>
      <input type="password" id="password" name="password" placeholder="tu password">
    </div>

    <input class="boton" type="submit" value="Guardar">
  </form>

  <div class="acciones">
    <a href="/">¿ya tienes cuenta? Inicia cesion</a>
    <a href="/crear-cuenta">¿aun no tienes cuenta? crear cuenta</a>
  </div>
</main>