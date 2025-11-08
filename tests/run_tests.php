<?php
// Simple test harness for data validations
// Place under tests/run_tests.php

require_once __DIR__ . '/../Base/Base_Data_Validations.php';

// Load descriptions
require_once __DIR__ . '/../usuario/usuario_description.php';
require_once __DIR__ . '/../tarea/tarea_description.php';

function run_test($estructura, $valores, $listaAtributos, $entidadObj, $controlador, $testName) {
    echo "Running: $testName\n";
    $validator = new Base_Data_Validations($estructura, $valores, $listaAtributos, $entidadObj, $controlador);
    $result = $validator->data_validations();
    if ($result === true) {
        echo "  => PASS\n\n";
    } else {
        echo "  => FAIL: " . json_encode($result) . "\n\n";
    }
}

// Minimal dummy entity objects (to satisfy personalized method lookups if any)
class UsuarioDummy {}
class TareaDummy {}

// Tests for usuario
$usuario_atributos = array_keys($usuario_description['attributes']);

$valid_usuario = [
    'id_usuario' => '1',
    'correo' => 'dani@test.com',
    'contrasena' => 'secret',
    'nombre' => 'Dani',
    'apellidos' => 'Martinez'
];

$invalid_usuario_email = $valid_usuario;
$invalid_usuario_email['correo'] = 'bad-email';

run_test($usuario_description, $valid_usuario, $usuario_atributos, new UsuarioDummy(), 'usuario', 'Usuario válido');
run_test($usuario_description, $invalid_usuario_email, $usuario_atributos, new UsuarioDummy(), 'usuario', 'Usuario email inválido');

// Tests for tarea
$tarea_atributos = array_keys($tarea_description['attributes']);

$valid_tarea = [
    'id_tarea' => '1',
    'id_usuario' => '1',
    'nombre_tarea' => 'Tarea 1',
    'descripcion_tarea' => 'Descripción',
    'fecha_inicio_tarea' => '2024-06-01 10:00:00',
    'fecha_fin_tarea' => '2024-06-05 18:00:00',
    'completada_tarea' => '0'
];

$invalid_tarea_date = $valid_tarea;
$invalid_tarea_date['fecha_inicio_tarea'] = 'invalid-date';

run_test($tarea_description, $valid_tarea, $tarea_atributos, new TareaDummy(), 'tarea', 'Tarea válida');
run_test($tarea_description, $invalid_tarea_date, $tarea_atributos, new TareaDummy(), 'tarea', 'Tarea fecha inválida');

echo "Tests finished.\n";
