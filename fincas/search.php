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
include_once '../object/fincas.php';
include_once '../object/jwt.php';


$data = json_decode(file_get_contents("php://input"));

if(isset($data->jwt)){
    $validToken = new myjwt();
    $validToken->jwt = $data->jwt;
    $token=$validToken->tokenlife();
    if($token && $validToken->nivel==0){
        $database = new Database();
        $db=$database->getConnection();
        $fincas = new fincas($db);

        $keywords=$data->keywords;

        $stmt=$fincas->search($keywords);
        $num=$stmt->rowCount();

        if($num>0){
            $fincas_arr=array();
            $fincas_arr["records"]=array();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $fincas_item=array(
                    "id" => $id,
                    "nombre"=>$nombre,
                    "telefono"=>$telefono,
                    "direccion"=>$direccion
                );
                array_push($fincas_arr["records"], $fincas_item);
            }
            http_response_code(200);
            echo json_encode($fincas_arr);
        }else{
            http_response_code(404);
            echo json_encode(array("massage"=>"No hay fincas encontrados."));
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