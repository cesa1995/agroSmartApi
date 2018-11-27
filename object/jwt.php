<?php
use Firebase\JWT\JWT;
define('SECRET','AGRONOMIA_PRECISION');
class myjwt{
    public $jwt;
    public $tokenlife;
    public $nivel;
    public $nombre;
    public $apellido;
    public $id;
    private $key;
    private $JWT;

    public function __construct(){
        $this->key = SECRET;
        $this->JWT = new JWT();
    }

    public function tokenlife(){
        try{
            $decoded = $this->JWT->decode($this->jwt,$this->key, array('HS256'));
            $decoded_array = (array)$decoded;
            $this->tokenlife= $decoded_array['exp'] - $decoded_array['iat'];
            $this->nivel=$decoded_array['nivel'];
            $this->id=$decoded_array['id'];
        }catch(\Exception $e){
        }

        if($this->tokenlife>0){
            return true;
        }else{
            return false;
        }
    }

    public function createToken(){
        $issuedAt = time();
        $expitationTime = $issuedAt + (((60*60)*60)*24);
        $payload = array(
            "id" => $this->id,
            "nivel" => $this->nivel,
            "iat" => $issuedAt,
            "exp" => $expitationTime
        );
        $alg = 'HS256';
        $this->jwt = $this->JWT->encode($payload,$this->key,$alg);
    }
}
?>