<?php
include_once '../modelo/Conexion.php';

if (isset($_POST['boya'], $_POST['aplicacion'], $_POST['dev_eui']) and $_POST['boya'] !="" and $_POST['dev_eui']!="" and $_POST["aplicacion"]!=""){

    $b = $_POST["boya"];
    $dev_eui = $_POST["dev_eui"];
    $app = $_POST["aplicacion"];

    $db = new Conexion();

    if ($db->connect_errno) {
        echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
    }

    $sql = "INSERT INTO Catalogo_boyas (Nombre, Dev_EUI, Aplication) VALUES (:b, :dev_eui, :app)";
    $query = $db->pdo->prepare($sql);
    $query->execute(array(':b' => $b, ':dev_eui' => $dev_eui, ':app' => $app));

    mysqli_close($db);

    if (intval($query->errorCode()) === 0) {
        header('Location:../vista/agregar_boya.php?m=1');
    }else {
        header('Location:../vista/agregar_boya.php?m=0');
    }
}
else{
    header('Location:../vista/agregar_boya.php');
}
?>