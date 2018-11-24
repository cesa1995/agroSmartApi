<?php
class equipos{
    public $id;
    public $nombre;
    public $devicetype;
    public $descripcion;

    private $conn;
    private $table_name = "equipos";

    public function __construct($db){
        $this->conn = $db;
    }

    function search($keywords){
        $query="SELECT * FROM ".$this->table_name . " WHERE
            nombre LIKE ? OR
            devicetype LIKE ? OR
            descripcion LIKE ?
        ORDER BY nombre DESC";

        $stmt = $this->conn->prepare($query);

        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords="{$keywords}";

        $stmt->bindParam(1,$keywords);
        $stmt->bindParam(2,$keywords);
        $stmt->bindParam(3,$keywords);

        $stmt->execute();
        return $stmt;
    }

    function readOne(){
        $query="SELECT * FROM " . $this->table_name . " WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        $this->nombre=$row['nombre'];
        $this->devicetype=$row['devicetype'];
        $this->descripcion=$row['descripcion'];
    }

    function update(){
        $query="UPDATE ". $this->table_name . " SET
            nombre=:nombre,
            devicetype=:devicetype,
            descripcion=:descripcion
        WHERE
            id=:id";

        $stmt=$this->conn->prepare($query);
        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->devicetype=htmlspecialchars(strip_tags($this->devicetype));
        $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":devicetype", $this->devicetype);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function delete(){
        $query="DELETE FROM ". $this->table_name . " WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id",$this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function create(){
        $query="INSERT INTO " . $this->table_name . " SET
            nombre=:nombre,
            devicetype=:devicetype,
            descripcion=:descripcion";
        $stmt= $this->conn->prepare($query);
        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->devicetype=htmlspecialchars(strip_tags($this->devicetype));
        $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":devicetype", $this->devicetype);
        $stmt->bindParam(":descripcion", $this->descripcion);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function valid(){
        $query = "SELECT * FROM ". $this->table_name . " WHERE nombre=:nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre",$this->nombre);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($row)){
            return false;
        }
        return true;
    }

    function read(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

}

?>