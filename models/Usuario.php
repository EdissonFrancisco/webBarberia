<?php

namespace Model;

class Usuario extends ActiveRecord {
  //datos de la tabla
  protected static $tabla = 'usuarios';
  protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'telefono', 'admin', 'password', 'confirmado', 'token'];

  public $id;
  public $nombre;
  public $apellido;
  public $email;
  public $telefono;
  public $admin;
  public $password;
  public $confirmado;
  public $token;

  public function __construct($args = []) {
    $this->id = $args['id'] ?? NULL;
    $this->nombre = $args['nombre'] ?? '';
    $this->apellido = $args['apellido'] ?? '';
    $this->email = strtolower($args['email']) ?? '';
    $this->telefono = $args['telefono'] ?? '';
    $this->admin = $args['admin'] ?? '0';
    $this->password = $args['password'] ?? '';
    $this->confirmado = $args['confirmado'] ?? '0';
    $this->token = $args['token'] ?? '';
  }

  public function validarNuevaCuenta() {
    
    if (!$this->nombre && !$this->apellido && !$this->email && !$this->telefono && !$this->password) {
      self::$alertas['error'][] = "Por favor, complete todos los campos.";
    } else {
      if(!$this->nombre) {
        self::$alertas['error'][] = "nombre es obligatorio";
      }
      if(!$this->apellido) {
        self::$alertas['error'][] = "apellido es obligatorio";
      }
      if(!$this->email) {
        self::$alertas['error'][] = "email es obligatorio";
      }
      if(!$this->telefono) {
        self::$alertas['error'][] = "numero de telefono es obligatorio";
      }
      if(!$this->password) {
        self::$alertas['error'][] = "password es obligatorio";
      }
      if (strlen($this->password) < 6) {
        self::$alertas['error'][] = "el paswor debe tener al menos seis caracteres";
      }
    }
    
    return self::$alertas;
  }

  //validacion y asignacion de errores para login
  public function validarLogin() {
    if(!$this->email) {
      self::$alertas['error'][] = "Email es obligatorio";
    }
    if(!$this->password) {
      self::$alertas['error'][] = "password es obligatorio";
    }
    return self::$alertas;
  }

  public function validarEmaiil() {
    if(!$this->email) {
      self::$alertas['error'][] = "Email es obligatorio";
    }
    return self::$alertas;
  }

  public function validarPassword() {
    if(!$this->password) {
      self::$alertas['error'][] = "password es obligatorio";
    }
    if (strlen($this->password) < 6) {
      self::$alertas['error'][] = "el paswor debe tener al menos seis caracteres";
    }
    return self::$alertas;
  }

  public function existeUsuario(){
    $query = "SELECT email FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
    $resultado = self::$db->query($query);
    if ($resultado->num_rows) {
      self::$alertas['error'][] = 'el usuario ya esta registrado';
    }
    return $resultado;
  }

  public function hashPassword() {
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  public function generarToken() {
    $this->token = uniqid();
  }

  public function comprovarPasswordandVerificado($password) {
    $resultado = password_verify($password, $this->password);

    if (!$resultado || !$this->confirmado) {
      self::$alertas['error'][] = "password incorrecto o la cuenta no se ha verificado";
    } else {
      return true;
    }

  }
}