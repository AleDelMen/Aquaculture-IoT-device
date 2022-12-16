<?php
session_start();
$e  = $_GET['e'];
$s =  $_GET['s'];

include_once '../modelo/Conexion.php';

$db = new Conexion();
$sql = "SELECT ID, Correo FROM Correos WHERE Avisos = 1";
$query = $db->pdo->prepare($sql);
$query->execute(array());
$correos = $query->fetchAll();

if (!empty($_SESSION['usuario'])){
    include_once 'layouts/header.php';
?>


  <title>Acuicultura | Notificaciones</title>

<?php
include_once 'layouts/nav.php';
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Notificaciones</h1>
            <?php if($_GET['m'] == '1'){
            ?>
              <script>alert("Se ingresaron correctamente los datos")
              var k = window.location.href.split("?")[0];
              window.location.replace(k); </script>
            <?php
            } 
            ?>
            <?php if($_GET['m'] == '0'){
            ?>
              <script>alert("Error al ingresar los datos")
              var k = window.location.href.split("?")[0];
              window.location.replace(k); </script>
            <?php
            } 
            ?>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../vista/starter.php">Home</a></li>
              <li class="breadcrumb-item active">Notificaciones</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          
          <!-- left column -->
          <div class="col-md-6">

            <!-- general form elements disabled -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Ingresar datos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="../controlador/notifications.php" method="post">

                  <div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
                        <label>Correo</label>
                        <select class="form-control" onchange = "correo_change()" id = "email" name = "email">
                          <?php
                            if (is_null($e)){
                          ?>
                              <option> </option>
                          <?php
                            }
                            foreach($correos as $co){
                          ?>
                          <option value = <?php echo $co->id; ?> <?php if(!is_null($e)){if($e == $co->id){echo "selected";}} ?>><?php echo $co->correo; ?></option>
                          <?php
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Estatus</label>
                        <select class="form-control" onchange = "status_change()" id = "status" name = "status">
                          <?php
                            if(is_null($s)){
                          ?>
                              <option> </option>
                          <?php
                            }
                          ?>
                          <?php
                            if(!is_null($e)){
                          ?>
                            <option value = <?php echo '0'; ?> <?php if(!is_null($s)){if($s == '0'){echo "selected";}} ?>><?php echo 'Desactivado'; ?></option>
                            <option value = <?php echo '1'; ?> <?php if(!is_null($s)){if($s == '1'){echo "selected";}} ?>><?php echo 'Activado'; ?></option>
                          <?php
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col text-center">
                      <button type="button submit" class="btn btn-sm btn-success">Enviar</button>
                  </div>
                  
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            
          </div>
          <!--/.col (left) -->

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php
include_once 'layouts/footer.php';
?>

<script>
  function correo_change(){
    var x = document.getElementById("email").value;
    var y = window.location.href.split("?")[0];
    window.location.replace(y+"?e="+x); 
  }

  function status_change(){
    var x = document.getElementById("status").value;
    var y = window.location.href.split("&")[0];
    window.location.replace(y+"&"+"s="+x);
  }

</script>

</body>
</html>


<?php
}
else{
    header('Location:../index.php');
}
?>