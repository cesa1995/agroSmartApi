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
        $db = $database->getConnection();

        $equipos = new equipos($db);

        $stmt = $equipos->read();
        $num = $stmt->rowCount();

        if($num>0){
            $equipos_arr = array();
            $equipos_arr["records"]=array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                $equipos_item=array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "devicetype" => $devicetype,
                    "descripcion" => $descripcion
                );
                array_push($equipos_arr["records"],$equipos_item);
            }

            http_response_code(200);
            echo json_encode($equipos_arr);
        }else{
            http_response_code(404);
            echo json_encode(array("message"=>"no hay equipos."));
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