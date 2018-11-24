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
include_once '../object/equipos.php';
include_once '../object/jwt.php';


$data = json_decode(file_get_contents("php://input"));

if(isset($data->jwt)){
    $validToken = new myjwt();
    $validToken->jwt = $data->jwt;
    $token=$validToken->tokenlife();
    if($token && $validToken->nivel==0){
        $database = new Database();
        $db=$database->getConnection();
        $equipos = new equipos($db);

        if(
            isset($data->id) &&
            isset($data->nombre) &&
            isset($data->devicetype) &&
            isset($data->descripcion)
        ){
            $equipos->id=$data->id;
            $equipos->nombre=$data->nombre;
            $equipos->devicetype=$data->devicetype;
            $equipos->descripcion=$data->descripcion;

            if($equipos->update()){
                http_response_code(200);
                echo json_encode(array("massage"=>"Equipo actualizado."));
            }else{
                http_response_code(503);
                echo json_encode(array("message"=>"Equipo no actualizado."));
            }
        }else{
            http_response_code(400);
            echo json_encode(array("message"=>"Data Incompleta."));
        }
    }else{
        http_response_code(401);
        echo json_encode(array("massage"=>"no autorizado"));
    }
}else{
    http_response_code(400);
    echo json_encode(array("message"=>"sesion no iniciada."));
}

?>