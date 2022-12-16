<?php
include_once 'Conexion.php';
class Usuario{
    var $objetos;
    public function __construct(){
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }
    function Loguearse($dni,$pass){
        $sql = "SELECT * FROM Correos WHERE Correo=:dni AND Contrasena=:pass AND Avisos = 0";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':dni' => $dni,':pass'=>$pass));
        $this->objetos = $query->fetchAll();
        return $this->objetos;
       
    }
}
?>