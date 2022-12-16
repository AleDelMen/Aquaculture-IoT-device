<?php
include_once '../modelo/Conexion.php';
require('../vendor/bluerhinos/phpmqtt/phpMQTT.php');

if (isset($_POST['time'], $_POST['Boya']) and $_POST['time'] !="" and $_POST["Boya"]!=""){

    $t = $_POST['time'];
    $b = $_POST['Boya'];

    $db = new Conexion();

    if ($db->connect_errno) {
        echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
    }

    $sql = "SELECT Dev_EUI, Aplication FROM Catalogo_boyas WHERE ID = :b";
    $query = $db->pdo->prepare($sql);
    $query->execute(array(':b' => $b));
    $boyas = $query->fetchAll();
    mysqli_close($db);

    if (intval($query->errorCode()) !== 0) {
        header('Location:../vista/cambio_tiempo.php?m=0');
    }

    $server = 'localhost';     // change if necessary
    $port = 1883;                     // change if necessary
    $username = '';                   // set your username
    $password = '';                   // set your password
    $client_id = 'phpMQTT-publisher'; // make sure this is unique for connecting to sever - you could use uniqid()

    $mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

    if ($mqtt->connect(true, NULL, $username, $password)) {
        $m = array("confirmed" => false, "fPort" => 2,"object" => array("action" => 2, "time" => $t));
        $mqtt->publish('application/'.$boyas[0]->aplication.'/device/'.$boyas[0]->dev_eui.'/command/down', json_encode($m), 0, false);
        $mqtt->close();
        header('Location:../vista/cambio_tiempo.php?m=1');
    } else {
        header('Location:../vista/cambio_tiempo.php?m=0');
    }
}
else{
    header('Location:../vista/cambio_tiempo.php');
}
?>