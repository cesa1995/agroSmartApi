<?php
class asociar{
    public $id;
    public $fincaid;
    public $usuarioid;
    public $equipoid;
    public $estado;

    private $conn;
    private $usuario_table = "fincausu";
    private $equipo_table = "fincaequ";

    function __construct($db){
        $this->conn=$db;
    }

    function deleteusuario(){
        $query="DELETE FROM " . $this->usuario_table . " WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function deleteequipo(){
        $query="DELETE FROM " . $this->equipo_table . " WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id",$this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function readusuarios(){
        $query="SELECT fincausu.id, fincas.nombre as finca, usuarios.nombre as usuario, usuarios.email as email FROM "
            . $this->usuario_table . "
            INNER JOIN usuarios ON
                usuarios.id=fincausu.usuarioid AND fincausu.fincaid=:fincaid
            INNER JOIN fincas WHERE
                fincas.id=:fincaid";
        $stmt=$this->conn->prepare($query);
        $this->fincaid=htmlspecialchars(strip_tags($this->fincaid));
        $stmt->bindParam(":fincaid", $this->fincaid);
        $stmt->execute();
        return $stmt;
    }

    function readequipos(){
        $query="SELECT fincaequ.id, fincas.nombre as finca, equipos.nombre as equipo, fincaequ.estado FROM "
            . $this->equipo_table . "
            INNER JOIN equipos ON
                equipos.id=fincaequ.equipoid AND fincaequ.fincaid=:fincaid
            INNER JOIN fincas WHERE
                fincas.id=:fincaid";
        $stmt=$this->conn->prepare($query);
        $this->fincaid=htmlspecialchars(strip_tags($this->fincaid));
        $stmt->bindParam(":fincaid", $this->fincaid);
        $stmt->execute();
        return $stmt;
    }

    function validUsuario(){
        $query="SELECT id FROM ". $this->usuario_table. "
            WHERE
                usuarioid=:usuarioid
            AND
                fincaid=:fincaid";
        $stmt=$this->conn->prepare($query);
        $this->fincaid=htmlspecialchars(strip_tags($this->fincaid));
        $this->usuarioid=htmlspecialchars(strip_tags($this->usuarioid));

        $stmt->bindParam(":fincaid", $this->fincaid);
        $stmt->bindParam(":usuarioid", $this->usuarioid);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($row)){
            return false;
        }
        return true;
    }

    function addUsuario(){
        $query="INSERT INTO ". $this->usuario_table . " SET
            fincaid=:fincaid,
            usuarioid=:usuarioid";
        $stmt=$this->conn->prepare($query);
        $this->fincaid=htmlspecialchars(strip_tags($this->fincaid));
        $this->usuarioid=htmlspecialchars(strip_tags($this->usuarioid));

        $stmt->bindParam(":fincaid", $this->fincaid);
        $stmt->bindParam(":usuarioid", $this->usuarioid);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function addEquipo(){
        $query="INSERT INTO ". $this->equipo_table . " SET
            fincaid=:fincaid,
            equipoid=:equipoid,
            estado=:estado";
        $stmt=$this->conn->prepare($query);
        $this->equipoid=htmlspecialchars(strip_tags($this->equipoid));
        $this->fincaid=htmlspecialchars(strip_tags($this->fincaid));
        $this->estado=htmlspecialchars(strip_tags($this->estado));

        $stmt->bindParam(":equipoid", $this->equipoid);
        $stmt->bindParam(":fincaid", $this->fincaid);
        $stmt->bindParam(":estado", $this->estado);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
}

?>