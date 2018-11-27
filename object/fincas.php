<?php

class fincas{
    private $conn;
    private $table_name = "fincas";

    public $id;
    public $nombre;
    public $telefono;
    public $direccion;

    public function __construct($db){
        $this->conn = $db;
    }

    function count(){
        $query="SELECT COUNT(id) as fincas FROM ".$this->table_name;
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    function search($keywords){
        $query="SELECT * FROM ". $this->table_name . " WHERE
            nombre LIKE ? OR
            direccion LIKE ?
        ORDER BY nombre DESC";

        $stmt=$this->conn->prepare($query);
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords="%{$keywords}%";

        $stmt->bindParam(1,$keywords);
        $stmt->bindParam(2,$keywords);

        $stmt->execute();
        return $stmt;
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

    function update(){
        $query="UPDATE ". $this->table_name . " SET
            nombre=:nombre,
            telefono=:telefono,
            direccion=:direccion
        WHERE
            id=:id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function read(){
        $query = "SELECT * FROM " . $this->table_name ;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readOne(){
        $query="SELECT * FROM " . $this->table_name . " WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":id",$this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->nombre=$row['nombre'];
        $this->telefono=$row['telefono'];
        $this->direccion=$row['direccion'];
    }

    function create(){
        $query = "INSERT INTO " . $this->table_name .
            " SET nombre=:nombre, telefono=:telefono, direccion=:direccion";
        $stmt = $this->conn->prepare($query);
        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->telefono=htmlspecialchars(strip_tags($this->telefono));
        $this->direccion=htmlspecialchars(strip_tags($this->direccion));

        $stmt->bindParam(":nombre",$this->nombre);
        $stmt->bindParam(":telefono",$this->telefono);
        $stmt->bindParam(":direccion",$this->direccion);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function valid(){
        $expresion='/^(\+?[0-9]{9,13})$/';
        $query = "SELECT * FROM " . $this->table_name . " WHERE nombre=:nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nombre",$this->nombre);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($row) || !preg_match($expresion,$this->telefono)){
            return false;
        }
        return true;
    }
}

?>