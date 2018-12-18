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
include_once '../object/parcelas.php';
include_once '../object/jwt.php';


$data = json_decode(file_get_contents("php://input"));

if(isset($data->jwt)){
    $validToken = new myjwt();
    $validToken->jwt = $data->jwt;
    $token=$validToken->tokenlife();
    if($token && $validToken->nivel==0){
        $database = new Database();
        $db=$database->getConnection();
        $parcelas = new parcelas($db);
        if(
            !empty($data->nombre) &&
            !empty($data->tipo) &&
            !empty($data->idfinca)
        ){
                $parcelas->nombre=$data->nombre;
                $parcelas->tipo=$data->tipo;
                $parcelas->idfinca=$data->idfinca;
                if($parcelas->create()){
                    http_response_code(201);
                    echo json_encode(array("message"=>"Parcela creado."));
                }else{
                    http_response_code(503);
                    echo json_encode(array("message"=>"Parcela no creado"));
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