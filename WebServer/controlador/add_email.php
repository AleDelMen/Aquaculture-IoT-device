<?php
include_once '../modelo/Conexion.php';
require '../vendor/autoload.php';

$pb_key ='PUT YOUR PB KEY';

if (isset($_POST['email']) and $_POST['email'] !=""){

    $e = $_POST["email"];

    $db = new Conexion();

    if ($db->connect_errno) {
        echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
    }

    try{
        $sql = "INSERT INTO Correos (Correo) VALUES (:e)";
        $query = $db->pdo->prepare($sql);
        $query->execute(array(':e' => $e));
     
        $pb = new Pushbullet\Pushbullet($pb_key);
        $pb->createContact("", $e);
    }
    catch (Throwable $e) {
        mysqli_close($db);
        header('Location:../vista/agregar_correo.php?m=0');
    }
    mysqli_close($db);

    if (intval($query->errorCode()) === 0) {
        header('Location:../vista/agregar_correo.php?m=1');
    }else {
        header('Location:../vista/agregar_correo.php?m=0');
    }
}
else{
    header('Location:../vista/agregar_correo.php');
}
?>