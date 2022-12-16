<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/css/all.min.css">
</head>

<?php
session_start();
if(!empty($_SESSION['usuario'])){
    header('Location: ../controlador/LoginController.php');
}
else{
    session_destroy();
?>
<body>
    <img class='wave' src="img/wave4.png" alt="">
    <div class="contenedor">
        <div class="img">
            <img src="img/aqua5.png" alt="">
        </div>
        <div class="contenido_login">
            <form action="controlador/LoginController.php" method="post">
                <img src="img/user2.png" alt="">
                <h2>Acuicultura</h2>
                <div class="input_div dni">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input type="text" name="Usuario" class="input">
                    </div>
                </div>
                <div class="input_div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" name="Contrasena" class="input">
                    </div>
                </div>
                <a href="">Created by ADM</a>
                <input type="submit" class="btn" value="Iniciar sesión">
            </form>
        </div>
    </div>
</body>
<script src="js/login.js"></script>
</html>
<?php
}
?>
