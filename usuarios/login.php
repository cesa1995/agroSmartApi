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
require_once '../config/database.php';
require_once '../object/usuarios.php';
require_once '../object/jwt.php';

    $database = new Database();
    $db=$database->getConnection();

    $usuarios = new usuarios($db);

    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->email) && !empty($data->password)){
        $usuarios->pass = $data->password;
        $usuarios->email = $data->email;
        $login=$usuarios->login();
        if($usuarios->id!=null){
            $token=new myjwt();
            $token->id=$usuarios->id;
            $token->nivel=$usuarios->nivel;
            $tokengen=$token->createToken();
            $result =array(
                "nombre"=>$usuarios->nombre,
                "apellido"=>$usuarios->apellido,
                "jwt"=>$token->jwt
            );
            http_response_code(200);
            echo json_encode($result);

        }else{
            http_response_code(404);

            echo json_encode(array("message"=>"Email o password invalido."));
        }
    }else{
        http_response_code(400);
        echo json_encode(array("message" => "Datos incompletos."));
    }

?>