<?php
include_once '../modelo/Conexion.php';

if (isset($_POST['v_min'], $_POST['v_max'], $_POST['Sensor'], $_POST['Boya'], $_POST['Modelo'], $_POST['Unidades']) and $_POST['v_min'] !="" and $_POST['v_max']!="" and $_POST["Sensor"]!="" and $_POST["Boya"]!="" and $_POST["Modelo"]!="" and $_POST["Unidades"]!=""){

    $v_min = $_POST['v_min'];
    $v_max = $_POST['v_max'];
    $p = $_POST['Sensor'];
    $b = $_POST['Boya'];
    $m = $_POST["Modelo"];
    $u = $_POST["Unidades"];

    $db = new Conexion();

    if ($db->connect_errno) {
        echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
    }

    $sql = "INSERT INTO Catalogo_sensores (Boya, Nombre, Variable, Unidades, Valor_min, Valor_max) VALUES (:b, :m, :p, :u, :v_min, :v_max)";
    $query = $db->pdo->prepare($sql);
    $query->execute(array(':b' => $b, ':m' => $m, ':p' => $p, ':u' => $u, ':v_min' => $v_min, ':v_max' => $v_max));


    mysqli_close($db);


    if (intval($query->errorCode()) === 0) {
        header('Location:../vista/agregar_sensor.php?m=1');
    }else {
        header('Location:../vista/agregar_sensor.php?m=0');
    }
}
else{
    header('Location:../vista/agregar_sensor.php');
}
?>