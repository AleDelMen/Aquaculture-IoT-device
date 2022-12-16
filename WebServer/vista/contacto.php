<?php
session_start();
if (!empty($_SESSION['usuario'])){
    include_once 'layouts/header.php';
?>


  <title>Acuicultura | Información de contacto</title>

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
            <h1>Información de contacto</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../vista/starter.php">Home</a></li>
              <li class="breadcrumb-item active">Información de contacto</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <div class="card card-solid">
        <div class="card-body pb-0">
          <div class="row">
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
              <div class="card bg-light d-flex flex-fill">
                <div class="card-header text-muted border-bottom-0">
                  Ingeniero
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>Alejandro de la Cruz</b></h2>
                      <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="fas fa-map-marker-alt"></i></span> Dirección: CDMX, México</li>
                        <li class="small"><span class="fa-li"><i class="fab fa-whatsapp"></i></span> WhatsApp: </li>
                        <li class="small"><span class="fa-li"><i class="fas fa-at"></i></span> Correo: </li>
                      </ul>
                    </div>
                    <div class="col-5 text-center">
                      <img src="../img/user3.png" alt="user-avatar" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
              <div class="card bg-light d-flex flex-fill">
                <div class="card-header text-muted border-bottom-0">
                  Doctor
                </div>
                <div class="card-body pt-0">
                  <div class="row">
                    <div class="col-7">
                      <h2 class="lead"><b>José Jaime Camacho</b></h2>
                      <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small"><span class="fa-li"><i class="fas fa-map-marker-alt"></i></span> Dirección: CDMX, México</li>
                        <li class="small"><span class="fa-li"><i class="fab fa-whatsapp"></i></span> WhatsApp: </li>
                        <li class="small"><span class="fa-li"><i class="fas fa-at"></i></span> Correo: </li>
                      </ul>
                    </div>
                    <div class="col-5 text-center">
                      <img src="../img/user3.png" alt="user-avatar" class="img-circle img-fluid">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->

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