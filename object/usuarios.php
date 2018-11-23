<?php
class usuarios{
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $pass;
    public $nivel;

    public function __construct($db){
        $this->conn = $db;
    }

    function login(){
        $query="SELECT * FROM ".$this->table_name." WHERE email=:email";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(":email",$this->email);
        $stmt->execute();
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty($row)){
            if(password_verify($this->pass, $row['contrasena'])){
                $this->id=$row['id'];
                $this->nombre=$row['nombre'];
                $this->apellido=$row['apellido'];
                $this->nivel=$row['nivel'];
            }
            return false;
        }else{
            return true;
        }
    }

    function search($keywords){
        $query="SELECT * FROM " . $this->table_name . " WHERE
                nombre LIKE ? OR
                apellido LIKE ? OR
                email LIKE ?
            ORDER BY nombre DESC";

            $stmt = $this->conn->prepare($query);

            $keywords=htmlspecialchars(strip_tags($keywords));
            $keywords="%{$keywords}%";

            $stmt->bindParam(1,$keywords);
            $stmt->bindParam(2,$keywords);
            $stmt->bindParam(3,$keywords);

            $stmt->execute();
            return $stmt;
    }

    function updatepass(){
        $query="UPDATE ". $this->table_name . " SET
                contrasena=:pass
            WHERE
                id=:id";
        $stmt = $this->conn->prepare($query);
        $this->pass = htmlspecialchars(strip_tags($this->pass));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':pass', $this->pass);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function update(){
        $query="UPDATE ". $this->table_name . " SET
                nombre=:nombre,
                apellido=:apellido,
                nivel=:nivel
            WHERE
                id=:id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->nivel = htmlspecialchars(strip_tags($this->nivel));

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':nivel', $this->nivel);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    function delete(){
        $query="DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt=$this->conn->prepare($query);
        $this->id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id",$this->id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function create(){
        $query="INSERT INTO " . $this->table_name .
        " SET nombre=:nombre, apellido=:apellido, email=:email, contrasena=:pass, nivel=:nivel";
        $stmt=$this->conn->prepare($query);
        $this->nombre=htmlspecialchars(strip_tags($this->nombre));
        $this->apellido=htmlspecialchars(strip_tags($this->apellido));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->nivel=htmlspecialchars(strip_tags($this->nivel));
        $this->pass=htmlspecialchars(strip_tags($this->pass));

        $stmt->bindParam(":nombre",$this->nombre);
        $stmt->bindParam(":apellido",$this->apellido);
        $stmt->bindParam(":email",$this->email);
        $stmt->bindParam(":pass",$this->pass);
        $stmt->bindParam(":nivel",$this->nivel);

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    function is_valid_email(){
        $result = (false !== filter_var($this->email, FILTER_VALIDATE_EMAIL));
        if($result){
            list($user,$domain) = preg_split('/[@]/', $this->email);
            $result = checkdnsrr($domain,"MX");
        }
        return $result;
    }

    function read(){
        $query = "SELECT * FROM usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readOne(){
        $query = "SELECT nombre,apellido,email,nivel FROM usuarios WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id",$this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->nombre=$row['nombre'];
        $this->apellido=$row['apellido'];
        $this->email=$row['email'];
        $this->nivel=$row['nivel'];
    }

    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }
}
?>