<?php

/**
 * responder($texto)
 * This function sends a JSON response back to the client.
 * @param mixed $texto data to be sent in the response.
 * @return void
 */
function responder($texto)
{
    header('Content-type: application/json');
    echo (json_encode($texto));
    exit();
}

$metodoHTTP = $_SERVER['REQUEST_METHOD'];

switch ($metodoHTTP) {
    case 'PUT':
        parse_str(file_get_contents("php://input"), $variables);
        define('variables', $variables);
        define('action', 'ADD');
        break;
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $variables);
        define('variables', $variables);
        define('action', 'DELETE');
        $action = 'DELETE';
        break;
    case 'POST':
        define('variables', $_POST);
        define('action', 'EDIT');
        $action = 'EDIT';
        break;
    case 'GET':
        define('variables', $_GET);
        define('action', 'SEARCH');
        $action = 'SEARCH';
        break;

    default:
        $feedback['ok'] = false;
        $feedback['code'] = 'action_doesnt_exist_KO';
        $feedback['resources'] = "Accion no valida, Entro en el DEFAULT.";
        responder($feedback);
        break;
}

// no se envia un controlador desde el front
if (!isset(variables['controlador'])) {
    $feedback['ok'] = false;
    $feedback['code'] = 'controller_not_exists_KO';
    $feedback['resources'] = 'El controlador no existe';
    responder($feedback);
}

// el controlador enviado no es un controlador valido de nuestro back
$controller_file = "../" . variables['controlador'] . "/" . variables['controlador'] . "_description.php";


if (!file_exists($controller_file)) {
    responder('no es un controlador valido');
}

// el controlador valido no tiene definido un _CONTROLLER.php
$controller = "../" . variables['controlador'] . "/" . variables['controlador'] . "_CONTROLLER.php";
if (!file_exists($controller)) {
    $controller = "../Base/Base_CONTROLLER.php";
    $claseatratar = "Base_CONTROLLER";
} else {
    $claseatratar = variables['controlador'] . "_CONTROLLER";
}

// incluir el fichero del controlador y instanciar la clase del controlador
include $controller;
$claseinstanciada = new $claseatratar;
