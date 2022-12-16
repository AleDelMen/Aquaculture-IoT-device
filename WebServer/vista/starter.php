<?php
session_start();
if (!empty($_SESSION['usuario'])){
    include_once 'layouts/header.php';
?>


  <title>Acuicultura | Home</title>

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
            <h1>Home</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
      <hr size="10%" color="gray" />
    </section>

<!-- Main content -->
<section class="content">
      <div class="container-fluid">
        <h3><strong>Diseño y desarrollo de un prototipo para la medición de la calidad del agua orientado a la producción acuícola.</strong></h3>
        <h5><strong>Introducción.</strong></h5>
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <p>La acuicultura, o la producción de peces y mariscos en piscifactorías, ha crecido rápidamente, ya que pasó de suministrar sólo el 7 por ciento del pescado para consumo humano en 1974 a más de la mitad en 2018.</p>

            <p>Esta rápida expansión ha generado desafíos que incluyen preocupaciones sobre la degradación ambiental, las enfermedades, los brotes de parásitos y la necesidad de administrar los recursos de manera eficiente para maximizar la productividad.</p>  

          </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">
            <!-- Form Element sizes -->
            <!-- general form elements disabled -->
            <div align="center">
              <img src="../img/aqua4.png" width="80%">
            </div>
            <!-- /.card -->
            
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->

        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <div align="center">
              <img src="../img/aqua1.png" width="75%">
            </div>
          </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">
            <!-- Form Element sizes -->
            <!-- general form elements disabled -->

            <p>El manejo apropiado de la calidad del agua de un estanque juega un papel muy importante para el éxito de las operaciones acuícolas. Cada parámetro de calidad del agua por sí solo puede afectar de manera directa a la salud de los peces. La exposición a niveles impropios lleva al estrés y enfermedades.</p>

            <p>La acuicultura de precisión se basa en un conjunto de sensores interconectados desplegados dentro del entorno marino para monitorear, analizar, interpretar y brindar apoyo a la toma de decisiones que optimicen la salud, el crecimiento y el rendimiento económico de los peces, además de reducir el riesgo para el medio ambiente.
            Tradicionalmente, muchos de éstos pasos han requerido la intervención humana y han dependido en gran medida de la experiencia e intuición del acuicultor para tomar decisiones y actuar correctamente.</p>
            
            <!-- /.card -->
            
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->

        <p>De forma general, se muestra en la figura la interacción entre un ostión y el entorno que lo rodea, se puede ver que es un sistema complejo en el cual se tiene que estar al pendiente de las variables que lo componen para mantenerlo en condiciones favorables para las criaturas.</p>

        <div align="center">
          <img src="../img/estanque_ost.png" width = "75%">
        </div>

        <p>Las condiciones favorables dependen del objetivo que se tenga, el cual podría ser el condicionamiento reproductivo, el objetivo de engorda, entre otros. Un ejemplo del cuidado de la calidad del agua sería cuando los ostiones son alimentados, estos excretan, además de sus desechos habituales, nitrógeno amoniacal total (NH<sub>3</sub> + NH<sub>4</sub>), el cual es un compuesto que en grandes cantidades llega a ser tóxico para ellos, por lo tanto, se tiene que estar monitoreando su concentración para que este no se acumule.</p>

        <h5><strong>Objetivo.</strong></h5>
        <p>Es por lo anterior que el objetivo de este trabajo es:</p>
        <p>Diseñar e implementar un sistema distribuido para el sensado de la calidad del agua de un sistema cerrado de acuicultura a través del monitoreo remoto de los parámetros de oxígeno disuelto, pH y temperatura.</p>

        <h5><strong>Propuesta del sistema.</strong></h5>

        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <p>El diagrama de bloques del proyecto es el que se muestra en la figura, las líneas punteadas representan una conexión inalámbrica y las líneas continuas representan una conexión física.</p>

            <p>El funcionamiento en general es el que se describe a continuación:</p>

            <ul>
                <li>La placa wireless stick lite, recibirá como entradas los datos obtenidos por el bloque de sensado.</li>
                <li>Los datos se transmitirán desde la placa al Gateway utilizando el protocolo LoRaWAN.</li>
                <li>La puerta de enlace será la encargada de enviar los datos al host a través del protocolo MQTT.</li>
                <li>El host se encargará de procesar la información y en caso de encontrar alguna irregularidad activará una alarma tanto física como digital.</li>
                <li>El host también almacenará los datos en una base y podrá devolver al cliente una página web a través de la cual se podrán consultar los datos en tiempo real.</li>
              </ul>

          </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">
            <!-- Form Element sizes -->
            <!-- general form elements disabled -->
            <div align="center">
              <img src="../img/diagrama_sistema.png" width="90%">
            </div>
            <!-- /.card -->
            
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
        <h5><strong>Modelo del sistema.</strong></h5>
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
           <div align="center">
              <img src="../img/Boya_render_2.jpeg" width="90%">
            </div>

          </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">
            <!-- Form Element sizes -->
            <!-- general form elements disabled -->

            <p>La boya estaría compuesta por el sistema de sensado y transmisión de datos, esta se observa en la figura, se puede dividir en tres partes, la primera está conformada por una cúpula de acrílico la cual contiene los paneles solares que estarán cosechando la energía solar.<p>
            <p>En la segunda sección se tendría la circuitería encargada del sensado de los parámetros seleccionados del cuerpo de agua. Y por último se tendría una sección llena de algún material pesado, que funcionaría como estabilizador de la boya, cuya función es evitar que la boya se voltee.<p>
            <p>En la la figura se puede observar que las pruebas irían colocadas en el exterior de la boya de tal forma que puedan estar en contacto con el líquido.<p>
            <!-- /.card -->
            
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->



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