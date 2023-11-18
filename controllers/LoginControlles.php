<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Classes\Email;

class LoginControlles {
  public static function login(Router $router) {

    $alertas = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      //envio de datos post a la clase usuario
      $auth = new Usuario($_POST);

      //errores de autenticacion login
      $alertas = $auth->validarLogin();

      if (empty($alertas)) {
        $auth->email = strtolower($auth->email);
        //comprovacion que el usuario existe
        $usuario = Usuario::where('email', $auth->email);

        if ($usuario) {
          $verificado = $usuario->comprovarPasswordandVerificado($auth->password);

          if ($verificado) {
            //autenticacion de usuario
            session_start();

            $_SESSION['id'] = $usuario->id;
            $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['login'] = true;

            if ($usuario->admin === '1') {
              $_SESSION['admin'] = $usuario->admin ?? null;
              header("Location: /admin");
            } else {
              header("location: /cita");
            }
          }

        } else {
          Usuario::setAlerta('error', 'Usuario no registrado');
        }
      }
    }
    $alertas = Usuario::getAlertas();
    $router->render('auth/login', [
      'alertas' => $alertas
    ]);
  }
  public static function logout() {
    echo "desde logout";
  }
  public static function olvidar(Router $router) {
    $alertas = [];

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
      $auth = new Usuario($_POST);
      $alertas = $auth->validarEmaiil();

      if (empty($alertas)) {
        $usuario = Usuario::where('email', $auth->email);

        if (isset($usuario) && $usuario->confirmado === "1") {
          //Generar token unico
          $usuario->generarToken();
          $usuario->guardar();

          //enviar el email
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

          $email->enviarInformacion();

          Usuario::setAlerta('exito', 'revisa tu corre');
        } else {
          Usuario::setAlerta("error", "el usuario no existe o no esta confirmado");
        }
      }
    }
    $alertas = Usuario::getAlertas();
    $router->render('auth/olvide-password', [
      'alertas' => $alertas
    ]);
  }
  public static function recuperar(Router $router) {
    //alertas vacio
    $alertas = [];
    $error = false;

    $token = escapeHtml($_GET['token']);

    if (empty($token)) {
      Usuario::setAlerta("error", "Token no valido");
      $error = true;
    }
    $usuario = Usuario::where('token', $token);
    if (empty($usuario)) {
      Usuario::setAlerta("error", "Token no valido");
      $error = true;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $paswordUsuario = new Usuario($_POST);
      $alertas = $paswordUsuario->validarPassword();


      if (empty($alertas)) {
        $usuario->password = null;
        $usuario->password = $paswordUsuario->password;
        $usuario->hashPassword();
        $usuario->token = null;

        $resultado = $usuario->guardar();

        if ($resultado) {
          header("Location: /");
        }
      }
    }

    $alertas = Usuario::getAlertas();
    $router->render('auth/recuperar-password', [
      'alertas' => $alertas,
      'error' => $error
    ]);
  }

  public static function crear(Router $router) {
    //instancia de la clase usuario
    $usuario = new Usuario;
    
    //alertas vacio
    $alertas = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $_POST['email'] = strtolower($_POST['email']);

      debuguear($_POST);
      $usuario->sincronizar($_POST);

      //validaciones de formulario
      $alertas = $usuario->validarNuevaCuenta();

      //validar que alertas este vacio
      if (empty($alertas)) {
        //verificar que usuario no este registrado
        $resultado = $usuario->existeUsuario();

        if ($resultado->num_rows) {
          $alertas = Usuario::getAlertas();
        } else {//si no esta registrado
          //hashear el password
          $usuario->hashPassword();
          //Generar token unico
          $usuario->generarToken();

          //enviar el email
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

          $email->enviarConfirmacion();

          //crear el usuario
          $resultado = $usuario->guardar();
          if ($resultado) {
            header('Location: /mensaje');
          }
        }

      }
    }

    $router->render('auth/crear-cuenta', [
      "usuario" => $usuario,
      "alertas" => $alertas
    ]);
  }

  public static function mensaje(Router $router){
    $router->render('auth/mensaje');
  }

  public static function confirmar(Router $router) {
    $alertas = [];
    $usuario = new Usuario;

    $token = $_GET['token'];

    if (empty($token) || strlen($token) < 13) {
      Usuario::setAlerta('error', 'Token de usuario NO valido');
    } else {
      $usuario = Usuario::where('token',$token);

      if (empty($usuario)) {
        //mensaje error
        Usuario::setAlerta('error', 'Token de usuario NO valido');
      } else {
        //actualizo al usuario

        $usuario->confirmado = "1";
        $usuario->token = "";
        $usuario->guardar();
        Usuario::setAlerta('exito', 'Cuenta Comprobada con exito');
      }
    }



    $alertas = Usuario::getAlertas();
    $router->render('auth/confirmar-cuenta', ["alertas" => $alertas]);
  }

}