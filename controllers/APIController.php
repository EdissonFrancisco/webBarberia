<?php

namespace Controllers;

use Model\Servicio;

class APIController {
  public static function index() {
    $servicios = Servicio::all();
    //convertir un arreglo a json
    echo json_encode($servicios);
  }
}