<?php
session_start();

if (!empty($_SESSION['usuario'])){
    include_once 'layouts/header.php';
?>


  <title>Acuicultura | Agregar boya</title>

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
            <h1>Agregar boya</h1>
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
              <li class="breadcrumb-item active">Agregar boya</li>
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
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Ingresar datos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="../controlador/new_bouy.php" method="post">

                <div class="row">
                    <div class="col-sm-6">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" placeholder="Enter" name='boya'>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>DEV_EUI</label>
                        <input type="text" class="form-control" placeholder="Enter" name='dev_eui'>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Aplicación</label>
                        <input type="text" class="form-control" placeholder="Enter" name='aplicacion'>
                      </div>
                    </div>
                  </div>

                  <div class="col text-center">
                      <button type="button submit" class="btn btn-sm btn-primary">Enviar</button>
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

</body>
</html>


<?php
}
else{
    header('Location:../index.php');
}
?>