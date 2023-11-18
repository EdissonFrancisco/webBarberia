<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\LoginControlles;
use Controllers\CitaController;
use Controllers\APIController;

$router = new Router();

//Rutas para inicio de session 
$router->get('/', [LoginControlles::class, 'login']);
$router->post('/', [LoginControlles::class, 'login']);
$router->get('/logout', [LoginControlles::class, 'logout']);

//recuperar clave
$router->get('/olvidar', [LoginControlles::class, 'olvidar']);
$router->post('/olvidar', [LoginControlles::class, 'olvidar']);
$router->get('/recuperar', [LoginControlles::class, 'recuperar']);
$router->post('/recuperar', [LoginControlles::class, 'recuperar']);

//crear cuenta
$router->get('/crear-cuenta', [LoginControlles::class, 'crear']);
$router->post('/crear-cuenta', [LoginControlles::class, 'crear']);

//confirmacion de cuenta
$router->get('/confirmar-cuenta', [LoginControlles::class, 'confirmar']);
$router->get('/mensaje', [LoginControlles::class, 'mensaje']);

//Areas privadas
$router->get('/cita', [CitaController::class, 'index']);

//API de citas o servicios de la peluqueria
$router->get('/api/servicios', [APIController::class, 'index']);
$router-


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();