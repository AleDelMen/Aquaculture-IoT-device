<?php
include_once '../modelo/Conexion.php';
require '../vendor/autoload.php';

if (isset($_POST['email'], $_POST['status']) and $_POST['email'] !="" and $_POST['status']!=""){

    $e = $_POST["email"];
    $s = $_POST["status"];

    $db = new Conexion();

    $pb_key = 'PUT YOUR PB KEY';

    if ($db->connect_errno) {
        echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
    }

    $sql = "UPDATE Correos SET Activo = :s WHERE ID = :e";
    $query = $db->pdo->prepare($sql);
    $query->execute(array(':s' => $s, ':e' => $e));

    $sql1 = "SELECT Correo FROM Correos WHERE ID = :e";
    $query1 = $db->pdo->prepare($sql1);
    $query1->execute(array(':e' => $e));
    $correos = $query1->fetchAll();

    mysqli_close($db);

    try{
      $pb = new Pushbullet\Pushbullet($pb_key);
      if (strcmp($s, '0') == 0){
        $pb->contact($correos[0]->correo)->delete();
      }
      else{
        $pb->createContact("", $correos[0]->correo);
      }
      header('Location:../vista/notificaciones.php?m=1');
    }
    catch (Throwable $e) {
      header('Location:../vista/notificaciones.php?m=0');
    }

    if (intval($query->errorCode()) === 0) {
        header('Location:../vista/notificaciones.php?m=1');
    }else {
        header('Location:../vista/notificaciones.php?m=0');
    }
}
else{
    header('Location:../vista/notificaciones.php');
}

?>