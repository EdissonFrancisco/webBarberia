<main>
  <h1 class="nombre-pagina">
    <span class="linea linea-izq"></span>
    olvide password
    <span class="linea linea-der"></span>
  </h1>
  <p class="descripcion-pagina">Ingrese tu correo electronico para recuperar el password</p>

  <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

  <form class="formulario" action="/olvidar" method="POST">

    <div class="campo">
      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" placeholder="tu email">
    </div>

    <input class="boton" type="submit" value="enviar instrucciones">
  </form>

  <div class="acciones">
    <a href="/">¿ya tienes cuenta? Inicia cesion</a>
    <a href="/crear-cuenta">¿aun no tienes cuenta? crear cuenta</a>
  </div>
</main>