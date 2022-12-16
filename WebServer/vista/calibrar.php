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
  $sql = "SELECT Catalogo_sensores.ID as ID, Tipo FROM Catalogo_sensores JOIN Catalogo_tipo_sensores ON Catalogo_tipo_sensores.ID = Variable WHERE Boya = :b AND Calibracion = 1";
  $query = $db->pdo->prepare($sql);
  $query->execute(array(':b' => $b));
  $sensores = $query->fetchAll();
}

if (!empty($_SESSION['usuario'])){
    include_once 'layouts/header.php';
?>


  <title>Acuicultura | Calibración</title>

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
            <h1>Calibración</h1>
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
              <li class="breadcrumb-item active">Calibración</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          
        <div class="card">
              <h5><b>Instrucciones:</b></h5>
                <ul>
                  <li><p>Oxígeno disuelto</p>
                  <p>
                  Para calibrar el sensor con el aire, se debe introducir el valor 0 en el apartado de valor real, si el sensor se calibra con la solución de calibración <I>Zero Dissolved Oxygen</I>, se tiene que introducir el valor 1. </p></li>
                  <li><p>pH</p>
                  <p>
                  El sensor de pH se puede calibrar con soluciones de pH de 4, 7 y 10. Este sensor tiene la capacidad de almacenar las tres calibraciones y dependiendo de las muestras con las que se calibre, será su rango de medición.
                  </p>
                  <p>Si se desea realizar una calibración con las tres disoluciones, se recomienda seguir el orden pH7, pH4 y pH10.</p></li>
                  <li><p>Temperatura</p>
                  <p>
                  El sensor de temperatura se puede calibrar con cualquier valor de temperatura. Durante el proceso de calibración la temperatura se debe de mantener constante. <b>El valor de la temperatura se debe de ingresar sin decimales, por lo tanto se recomienda multiplicar el valor de temperatura real por 1000.</b> </p>
                  <p>Por ejmplo:</p>
                  <p>Temperatura = 25.5°</p>
                  <p>Valor a ingresar = 25.5 * 1000 = 25500</p>
                </li>
                </ul>
                <b>Cualquier otro valor que se ingrese, que no sea alguno de los especificados, no funcionará.</b>
            <!-- /.card -->
            </div>

          <!-- left column -->
          <div class="col-md-6">

            <!-- general form elements disabled -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Ingresar datos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="../controlador/calibration.php" method="post">

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
                        <label>Valor real</label>
                        <input type="text" class="form-control" placeholder="Enter" name="v_real">
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