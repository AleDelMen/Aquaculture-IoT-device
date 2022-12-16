<?php
include_once '../modelo/Conexion.php';

if (isset($_POST['v_min'], $_POST['v_max'], $_POST['Sensor'], $_POST['Boya']) and $_POST['v_min'] !="" and $_POST['v_max']!="" and $_POST["Sensor"]!="" and $_POST["Boya"]!=""){

    $v_min = $_POST['v_min'];
    $v_max = $_POST['v_max'];
    $p = $_POST['Sensor'];
    $b = $_POST['Boya'];

    $db = new Conexion();

    if ($db->connect_errno) {
        echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
    }

    $sql = "UPDATE Catalogo_sensores SET Valor_min = :v_min, Valor_max = :v_max WHERE Boya = :b AND Variable = :p";
    $query = $db->pdo->prepare($sql);
    $query->execute(array(':v_min' => $v_min, ':v_max' => $v_max, ':b' => $b, ':p' => $p));

    mysqli_close($db);

    if (intval($query->errorCode()) === 0) {
        header('Location:../vista/modificar_alerta.php?m=1');
    }else {
        header('Location:../vista/modificar_alerta.php?m=0');
    }
}
else{
    header('Location:../vista/modificar_alerta.php');
}
?>