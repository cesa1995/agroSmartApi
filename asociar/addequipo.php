<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../php-jwt/src/BeforeValidException.php';
require_once '../php-jwt/src/ExpiredException.php';
require_once '../php-jwt/src/SignatureInvalidException.php';
require_once '../php-jwt/src/JWT.php';
include_once '../config/database.php';
include_once '../object/asociar.php';
include_once '../object/jwt.php';


$data = json_decode(file_get_contents("php://input"));

if(isset($data->jwt)){
    $validToken = new myjwt();
    $validToken->jwt = $data->jwt;
    $token=$validToken->tokenlife();
    if($token && $validToken->nivel==0){
        $database = new Database();
        $db=$database->getConnection();
        $asociar = new asociar($db);

        if(
            !empty($data->fincaid) || $data->fincaid=="0" &&
            !empty($data->equipoid) || $data->equipoid=="0" &&
            !empty($data->estado) || $data->estado=="0"
        ){
            $asociar->fincaid=$data->fincaid;
            $asociar->equipoid=$data->equipoid;
            $asociar->estado=$data->estado;
            if($asociar->addEquipo()){
                http_response_code(201);
                echo json_encode(array("message"=>"Equipo agegado."));
            }else{
                http_response_code(503);
                echo json_encode(array("message"=>"Equipo no agregado."));
            }
        }else{
            http_response_code(400);
            echo json_encode(array("message"=>"Data incompleta."));
        }
    }else{
        http_response_code(401);
        echo json_encode(array("message"=>"no autorizado"));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message"=>"sesion no iniciada."));
}


?>