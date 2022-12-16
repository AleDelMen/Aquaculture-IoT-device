<?php
session_start();
$b  = $_GET['b'];
$p  = $_GET['p'];
include_once '../modelo/Conexion.php';

$db = new Conexion();
$sql = "SELECT ID, Nombre FROM Catalogo_boyas";
$query = $db->pdo->prepare($sql);
$query->execute(array());
$boyas = $query->fetchAll();

function url($b, $p){
  $i = 0;
  if(!is_null($b)){
    echo "b=".$b;
    $i++;
  }
  if(!is_null($p)){
    if( $i > 0){
      echo "&";
    }
    echo "p=".$p;
    $i++;
  }  
}

if (!is_null($b)){
  $sql = "SELECT ID, Tipo FROM Catalogo_tipo_sensores";
  $query = $db->pdo->prepare($sql);
  $query->execute(array(':b' => $b));
  $sensores = $query->fetchAll();
}

if (!empty($_SESSION['usuario'])){
    include_once 'layouts/header.php';
?>


  <title>Acuicultura | Añadir sensor a boya</title>

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
            <h1>Añadir sensor a boya</h1>
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
              <li class="breadcrumb-item active">Añadir sensor a boya</li>
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
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Ingresar datos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="../controlador/add_sensor.php" method="post">

                  <div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
                        <label>Boya</label>
                        <select class="form-control" onchange = "boya_change()" id = "Boya" name = "Boya">
                          <?php
                            if (is_null($b)){
                          ?>
                              <option> </option>
                          <?php
                            }
                            foreach($boyas as $bo){
                          ?>
                          <option value = <?php echo $bo->id; ?> <?php if(!is_null($b)){if($b == $bo->id){echo "selected";}} ?>><?php echo $bo->nombre; ?></option>
                          <?php
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Parámetro</label>
                        <select class="form-control" onchange = "sensor_change()" id = "Sensor" name = "Sensor">
                          <?php
                            if(is_null($p)){
                          ?>
                              <option> </option>
                          <?php
                            }
                            foreach($sensores as $s){
                          ?>
                          <option value = <?php echo $s->id; ?> <?php if(!is_null($p)){if($p == $s->id){echo "selected";}} ?>><?php echo $s->tipo; ?></option>
                          <?php
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Modelo del sensor</label>
                        <input type="text" class="form-control" placeholder="Enter" name="Modelo">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Unidades</label>
                        <input type="text" class="form-control" placeholder="Enter" name="Unidades">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-6">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Valor de alerta mínimo</label>
                        <input type="text" class="form-control" placeholder="Enter" name="v_min">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Valor de alerta máximo</label>
                        <input type="text" class="form-control" placeholder="Enter" name="v_max">
                      </div>
                    </div>
                  </div>

                  <div class="col text-center">
                      <button type="button submit" class="btn btn-sm btn-info">Enviar</button>
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
  function boya_change(){
    var x = document.getElementById("Boya").value;
    var y = window.location.href.split("?")[0];
    window.location.replace(y+"?b="+x+"<?php url(NULL, NULL);?>"); 
  }

  function sensor_change(){
    var x = document.getElementById("Sensor").value;
    var y = window.location.href.split("?")[0];
    window.location.replace(y+"?"+"<?php url($b, NULL);?>"+"&p="+x);
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