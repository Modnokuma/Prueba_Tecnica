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
        echo "put";
        break;
    case 'DELETE':
        echo "delete";
        break;
    case 'POST':
        echo "post";
        break;
    case 'GET':
        echo "Versión de PHP: " . PHP_VERSION . "<br>";
        echo "Versión completa: " . phpversion() . "<br>";
        echo "get";

        break;
    default:
        $feedback['ok'] = false;
        $feedback['code'] = 'action_doesnt_exist_KO';
        $feedback['resources'] = "Accion no valida, Entro en el DEFAULT.";
        responder($feedback);
        break;
}