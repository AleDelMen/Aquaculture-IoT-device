<?php
include_once '../modelo/Conexion.php';
require('../vendor/bluerhinos/phpmqtt/phpMQTT.php');

if (isset($_POST['v_real'], $_POST['Boya'],$_POST['Sensor']) and $_POST['v_real'] !="" and $_POST["Boya"]!="" and $_POST['Sensor']!=""){

    $r = $_POST['v_real'];
    $b = $_POST['Boya'];
    $p = $_POST['Sensor'];

    $db = new Conexion();

    if ($db->connect_errno) {
        echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
    }

    $sql = "SELECT Dev_EUI, Aplication FROM Catalogo_boyas WHERE ID = :b";
    $query = $db->pdo->prepare($sql);
    $query->execute(array(':b' => $b));
    $boyas = $query->fetchAll();

    $sql0 = "SELECT Variable FROM Catalogo_sensores WHERE ID = :p";
    $query0 = $db->pdo->prepare($sql0);
    $query0->execute(array(':p' => $p));
    $parametro = $query0->fetchAll();

    $sql1 = "INSERT INTO Registro_calibracion (Sensor, Valor_cal) VALUES (:p, :r)";
    $query1 = $db->pdo->prepare($sql1);
    $query1->execute(array(':p' => $p, ':r' => $r));

    mysqli_close($db);

    if (intval($query->errorCode()) !== 0) {
        header('Location:../vista/calibrar.php?m=0');
    }

    $server = 'localhost';     // change if necessary
    $port = 1883;                     // change if necessary
    $username = '';                   // set your username
    $password = '';                   // set your password
    $client_id = 'phpMQTT-publisher'; // make sure this is unique for connecting to sever - you could use uniqid()

    $mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

    if ($mqtt->connect(true, NULL, $username, $password)) {
        $m = array("confirmed" => true, "fPort" => 2,"object" => array("action" => 1, "sensor" => $parametro[0]->variable, "valor" => $r));
        $mqtt->publish('application/'.$boyas[0]->aplication.'/device/'.$boyas[0]->dev_eui.'/command/down', json_encode($m), 0, false);
        $mqtt->close();
        header('Location:../vista/calibrar.php?m=1');
    } else {
        header('Location:../vista/calibrar.php?m=0');
    }
}
else{
    header('Location:../vista/calibrar.php');
}
?>