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
include_once '../object/usuarios.php';
include_once '../object/jwt.php';


$data = json_decode(file_get_contents("php://input"));

if(isset($data->jwt)){
    $validToken = new myjwt();
    $validToken->jwt = $data->jwt;
    $token=$validToken->tokenlife();
    if($token && $validToken->nivel==0){
        $database = new Database();
        $db=$database->getConnection();
        $usuarios = new usuarios($db);

        $data = json_decode(file_get_contents("php://input"));

        if(
            !empty($data->nombre) &&
            !empty($data->apellido) &&
            !empty($data->email) &&
            !empty($data->nivel) &&
            !empty($data->password)
        ){
            $usuarios->email=$data->email;
            if($usuarios->login() && $usuarios->is_valid_email()){
                $usuarios->nombre=$data->nombre;
                $usuarios->apellido=$data->apellido;
                $usuarios->pass=password_hash($data->password,PASSWORD_DEFAULT,['cost' => 12]);
                $usuarios->nivel=$data->nivel;
                if($usuarios->create()){
                    http_response_code(201);
                    echo json_encode(array("massage"=>"Usuario creado."));
                }else{
                    http_response_code(503);
                    echo json_encode(array("massage"=>"Usuario no creado"));
                }
            }else{
                http_response_code(503);
                echo json_encode(array("massage"=>"Usuario ya creado. Datos no validos."));
            }
        }else{
            http_response_code(400);
            echo json_encode(array("massage"=>"Data incompleta."));
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