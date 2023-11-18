<main>
  <h1 class="nombre-pagina">
    <span class="linea linea-izq"></span>
    login
    <span class="linea linea-der"></span>
  </h1>
  <p class="descripcion-pagina">inicia cesión con tus datos</p>

  <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

  <form class="formulario" action="/" method="POST">
    <div class="campo">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" placeholder="tu email">
    </div>
    <div class="campo">
      <label for="password">Clave</label>
      <input type="password" name="password" id="password" placeholder="tu password">
    </div>

    <input class="boton" type="submit" value="iniciar cesión">
  </form>

  <div class="acciones">
    <a href="/crear-cuenta">¿no tienes cuenta? Crear Cuenta</a>
    <a href="/olvidar">¿Olvidaste tu Clave?</a>
  </div>
</main>
