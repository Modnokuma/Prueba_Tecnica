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
define('controlador', 'tarea');
$metodoHTTP = $_SERVER['REQUEST_METHOD'];
$raw = file_get_contents("php://input");
$variables = json_decode($raw, true);

switch ($metodoHTTP) {
    case 'PUT':
        //parse_str(file_get_contents("php://input"), $variables);

        define('variables', $variables);
        define('action', 'ADD');
        break;
    case 'DELETE':
        //parse_str(file_get_contents("php://input"), $variables);
        define('variables', $variables);
        define('action', 'DELETE');
        $action = 'DELETE';
        break;
    case 'POST':
        //define('variables', $_POST);
        define('variables', $variables);
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

// el controlador valido no tiene definido un _CONTROLLER.php
$controller = "../" . controlador . "/" . controlador . "_CONTROLLER.php";
if (!file_exists($controller)) {
    $controller = "../Base/Base_CONTROLLER.php";
    $claseatratar = "Base_CONTROLLER";
} else {
    $claseatratar = controlador . "_CONTROLLER";
}

// incluir el fichero del controlador y instanciar la clase del controlador
include $controller;
$claseinstanciada = new $claseatratar;

