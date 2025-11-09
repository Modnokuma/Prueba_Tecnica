<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "../Base/Base_Mapping.php";

function responder($data, $code = 200)
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Leer JSON
//parse_str(file_get_contents("php://input"), $variables);
//$variables = json_decode(file_get_contents("php://input"), true);
$raw = file_get_contents("php://input");
$variables = json_decode($raw, true);
if (!is_array($variables)) {
    responder([
        "ok" => false,
        "code" => "json_decode_KO",
        "resources" => "Error al leer los datos enviados (JSON malformado o vacío)"
    ], 400);
}
$correo = $variables["correo"];
$contrasena = $variables["contrasena"];

// Validación de campos obligatorios
if (empty($correo) || empty($contrasena)) {
    $feedback['ok'] = false;
    $feedback['resources'] = 'Falta ';
    if (empty($correo)) {
        $feedback['code'] = 'correo_no_existe_KO';
        $feedback['resources'] .= 'correo ';
    }
    if (empty($contrasena)) {
        $feedback['code'] = 'contrasena_no_existe_KO';
        $feedback['resources'] .= 'contraseña ';
    }
    responder($feedback);
}
$db = new Base_Mapping();

// Consultar usuario
$sql = "SELECT id_usuario, correo_usuario, contrasena_usuario, nombre_usuario, apellidos_usuario FROM usuario WHERE correo_usuario = '" . $correo . "'";
$result = $db->personalized_query($sql);

$usuario = $result->fetch_assoc();
// Verificar usuario
if (!$usuario) {
    $feedback['ok'] = false;
    $feedback['code'] = 'usuario_no_existe_KO';
    $feedback['resources'] = 'El usuario no existe';
    responder($feedback);
}


// Comparar contraseñas
// Si aún no usas password_hash() en la BD:
if ($usuario["contrasena_usuario"] !== $contrasena) {
    $feedback["ok"] = false;
    $feedback["code"] = "contrasena_incorrecta_KO";
    $feedback["resources"] = "Contraseña incorrecta";
    responder($feedback);
}

// Si más adelante guardas hash:
// if (!password_verify($contrasena, $user["contrasena_usuario"])) {
//     responder(["ok" => false, "error" => "Contraseña incorrecta"], 401);
// }

unset($usuario["contrasena_usuario"]);

$feedback["ok"] = true;
$feedback["code"] = "login_OK";
$feedback["resources"] = $usuario;
responder($feedback);
