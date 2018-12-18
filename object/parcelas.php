<?php
class parcelas{
    private $conn;
    private $table_name = "parcelas";

    public $id;
    public $nombre;
    public $tipo;
    public $idfinca;
    public $finca;

    public function __construct($db){
        $this->conn = $db;
    }

    function count(){
        $query="SELECT COUNT(id) as parcelas FROM ". $this->table_name;
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    function search($keywords){
        $query="SELECT parcelas.id, parcelas.nombre, parcelas.tipo, parcelas.idfinca, fincas.nombre as finca FROM ".$this->table_name."
        INNER JOIN fincas ON
            fincas.id=parcelas.idfinca AND
            parcelas.nombre LIKE ? OR
            parcelas.tipo LIKE ?
        ORDER BY nombre DESC";

        $stmt = $this->conn->prepare($query);

        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords="{$keywords}";

        $stmt->bindParam(1,$keywords);
        $stmt->bindParam(2,$keywords);

        $stmt->execute();
        return $stmt;
    }

    function delete(){
        $query="DELETE FROM ". $this->table_name. " WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function readOne(){
        $query="SELECT fincas.nombre as finca, parcelas.nombre as nombre, parcelas.tipo as tipo, parcelas.idfinca as idfinca
        FROM ".$this->table_name."
        INNER JOIN fincas ON
            fincas.id=parcelas.idfinca AND parcelas.id=:id";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        $this->nombre=$row['nombre'];
        $this->tipo=$row['tipo'];
        $this->idfinca=$row['idfinca'];
        $this->finca=$row['finca'];
    }

    function update(){
        $query="UPDATE ". $this->table_name . " SET
            nombre=:nombre,
            tipo=:tipo
        WHERE
            id=:id";
        $stmt=$this->conn->prepare($query);
        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->tipo=htmlspecialchars(strip_tags($this->tipo));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function read(){
        $query="SELECT * FROM ". $this->table_name;
        $stmt=$this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function create(){
        $query="INSERT INTO " . $this->table_name .
        " SET nombre=:nombre, tipo=:tipo, idfinca=:idfinca";
        $stmt=$this->conn->prepare($query);
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->tipo= htmlspecialchars(strip_tags($this->tipo));
        $this->idfinca=htmlspecialchars(strip_tags($this->idfinca));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":idfinca", $this->idfinca);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

}
?>