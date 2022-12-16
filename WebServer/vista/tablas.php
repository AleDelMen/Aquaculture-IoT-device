<?php
session_start();
$ds  = $_GET['ds'];
$de  = $_GET['de'];
$b  = $_GET['b'];
$p  = $_GET['p'];
include_once '../modelo/Conexion.php';

$db = new Conexion();
$sql = "SELECT ID, Nombre FROM Catalogo_boyas";
$query = $db->pdo->prepare($sql);
$query->execute(array());
$boyas = $query->fetchAll();

if(!is_null($b) && !is_null($p) && !is_null($ds) && !is_null($de)){
  $sql = "SELECT ID, Date (Time_stamp) as Fecha, Time (Time_stamp) as Hora, Valor, Voltaje FROM Mediciones WHERE Sensor = :sen AND Time_stamp >= :ds AND Time_stamp < DATE_ADD(:de, INTERVAL 1 DAY)";
  $query = $db->pdo->prepare($sql);
  $query->execute(array(':sen' => $p, ':ds' => $ds, ':de' => $de));
  $t = $query->fetchAll();
}

function url($b, $p, $ds, $de){
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
  if(!is_null($ds)){
    $i++;
    if( $i > 0){
      echo "&";
    }
    echo "ds=".$ds;
  }
  if(!is_null($de)){
    if( $i > 0){
      echo "&";
    }
    echo "de=".$de;
    $i++;
  }  
}

if (!is_null($b)){
  $sql = "SELECT Catalogo_sensores.ID as ID, Tipo FROM Catalogo_sensores JOIN Catalogo_tipo_sensores ON Catalogo_tipo_sensores.ID = Variable WHERE Boya = :b ";
  $query = $db->pdo->prepare($sql);
  $query->execute(array(':b' => $b));
  $sensores = $query->fetchAll();
}


if (!empty($_SESSION['usuario'])){
    include_once 'layouts/header.php';
?>


  <title>Acuicultura | Gráficas y tablas </title>

  <!-- daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="../plugins/bs-stepper/css/bs-stepper.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="../plugins/dropzone/min/dropzone.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  

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
            <h1>Gráficas y tablas</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../vista/starter.php">Home</a></li>
              <li class="breadcrumb-item active">Gráficas y tablas</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section>
        
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- Form Element sizes -->
            <!-- general form elements disabled -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Seleccionar los elementos</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form>                      
                  <div class="row">
                    <div class="col-sm-6">
                      <!-- select -->
                      <div class="form-group">
                        <label>Boya</label>
                        <select class="form-control" onchange = "boya_change()" id = "Boya">
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
                        <select class="form-control" onchange = "sensor_change()" id = "Sensor">
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
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->

          <!-- right column -->
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Seleccionar fecha</h3>
              </div>
              <div class="card-body">
                
                <!-- Date and time range -->
                <div class="form-group">
                  <label>Date range button:</label>

                  <div class="input-group">
                    <button type="button" class="btn btn-default float-right" id="daterange-btn">
                      <i class="far fa-calendar-alt"></i> Date range picker
                      <i class="fas fa-caret-down"></i>
                    </button>
                  </div>
                </div>
                <!-- /.form group -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (right) -->
          
          <!-- begin the table -->
          <div class="col-12">

            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Valor</th>
                    <th>Voltaje</th>
                  </tr>
                  </thead>
                  <tbody>
<?php 
  foreach($t as $r){
?>
                  <tr>
                    <td><?php echo $r->id; ?></td>
                    <td><?php echo $r->fecha; ?></td>
                    <td><?php echo $r->hora; ?></td>
                    <td><?php echo $r->valor; ?></td>
                    <td><?php echo $r->voltaje; ?></td>
                  </tr>
<?php
}
?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Valor</th>
                    <th>Voltaje</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

          <div class="col-12">
            <!-- Line chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Line Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="line-chart" style="height: 300px;"></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

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

<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- FLOT CHARTS -->
<script src="../plugins/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="../plugins/flot/plugins/jquery.flot.resize.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="../plugins/flot/plugins/jquery.flot.pie.js"></script>


<script>
  function boya_change(){
    var x = document.getElementById("Boya").value;
    var y = window.location.href.split("?")[0];
    window.location.replace(y+"?b="+x+"<?php url(NULL, NULL, $ds, $de);?>"); 
  }

  function sensor_change(){
    var x = document.getElementById("Sensor").value;
    var y = window.location.href.split("?")[0];
    window.location.replace(y+"?"+"<?php url($b, NULL, $ds, $de);?>"+"&p="+x);
  }

</script>

<script>
  $(function () {
    
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        startDate: moment(),
        endDate  : moment().add(1, 'days'),
        ranges   : {
          'Hoy'       : [moment(), moment()],
          'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
          'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
          'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
          'Mes anterior'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
      },
      function (start, end) {
        var x = start.format('YYYY-MM-DD');
        var y = end.format('YYYY-MM-DD');
        var h = window.location.href.split("?");
        window.location.replace(h[0]+"?"+"<?php url($b, $p, NULL, NULL); ?>"+"&ds="+x+"&de="+y); 
        $('#date').html(x + ' / ' + y)
      }
    )
  });

  $('#date').html(moment().format('YYYY-MM-DD') + ' / ' + moment().format('YYYY-MM-DD'))
</script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  /*
     * LINE CHART
     * ----------
     */
    //LINE randomly generated data

    var values = []

<?php
  foreach($t as $r){
?>
    values.push([<?php echo $r->id; ?>, <?php echo $r->valor; ?>])
<?php
  }
?>

    var line_data1 = {
      data : values,
      color: '#039cf5'
    }
    $.plot('#line-chart', [line_data1], {
      grid  : {
        hoverable  : true,
        borderColor: '#f3f3f3',
        borderWidth: 1,
        tickColor  : '#f3f3f3'
      },
      series: {
        shadowSize: 0,
        lines     : {
          show: true
        },
        points    : {
          show: true
        }
      },
      lines : {
        fill : false,
        color: ['#3c8dbc', '#f56954']
      },
      yaxis : {
        show: true
      },
      xaxis : {
        show: false
      }
    })
    //Initialize tooltip on hover
    $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
      position: 'absolute',
      display : 'none',
      opacity : 0.8
    }).appendTo('body')
    $('#line-chart').bind('plothover', function (event, pos, item) {

      if (item) {
        var x = item.datapoint[0].toFixed(2),
            y = item.datapoint[1].toFixed(2)

        $('#line-chart-tooltip').html(item.series.label + ' of ' + x + ' = ' + y)
          .css({
            top : item.pageY + 5,
            left: item.pageX + 5
          })
          .fadeIn(200)
      } else {
        $('#line-chart-tooltip').hide()
      }

    })
    /* END LINE CHART */

</script>

</body>
</html>


<?php
}
else{
    header('Location:../index.php');
}
?>