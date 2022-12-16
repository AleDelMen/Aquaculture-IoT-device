<?php
include '../modelo/Usuario.php';
session_start();
$user = $_POST['Usuario'];
$pass = $_POST['Contrasena'];

$usuario =new Usuario();


if (!empty($_SESSION['usuario'])){
    header('Location: ../vista/starter.php');
}
else{
    $usuario->Loguearse($user,$pass);
    if (!empty($usuario->objetos)):   
        foreach ($usuario->objetos as $objeto){
            $_SESSION['usuario'] = $objeto->correo;
        }
        header('Location: ../vista/starter.php');
    else:
    
        if ($usuario->objetos):
            $_SESSION['usuario'] = $usuario->objetos;
            header('Location: ../vista/starter.php');
        else:
            header('Location: ../index.php');
        endif;
    endif;
}

?>