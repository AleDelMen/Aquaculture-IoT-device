  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini" style="text-align: justify;">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
        <a href="../controlador/Logout.php">Cerrar Sesion</a>
      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../vista/starter.php" class="brand-link">
      <img src="../img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><h3>Acuicultura</h3></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../img/user2.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="../vista/contacto.php" class="d-block"><h5>Administrador</h5></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-header">CONTENIDO</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-edit"></i>
              <p>
                Reportes
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../vista/tablas.php" class="nav-link">
                  <i class="fas fa-poll"></i>
                  <p>Gráficas y tablas</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="../vista/alertas.php" class="nav-link">
                  <i class="fas fa-bell"></i>
                  <p> Alertas</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="../vista/calibracion_rep.php" class="nav-link">
                  <i class="fab fa-algolia"></i>
                  <p> Calibración</p>
                </a>
              </li>
              
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>
                Configuración
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../vista/modificar_alerta.php" class="nav-link">
                  <i class="far fa-circle"></i>
                  <p>Modificar alertas</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="../vista/agregar_sensor.php" class="nav-link">
                  <i class="far fa-circle"></i>
                  <p>Añadir sensor a boya</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="../vista/agregar_boya.php" class="nav-link">
                  <i class="far fa-circle"></i>
                  <p>Agregar boya</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="../vista/notificaciones.php" class="nav-link">
                  <i class="far fa-circle"></i>
                  <p>Notificaciones</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="../vista/agregar_correo.php" class="nav-link">
                  <i class="far fa-circle"></i>
                  <p>Agregar correo</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="../vista/cambio_tiempo.php" class="nav-link">
                  <i class="far fa-circle"></i>
                  <p>Cambiar tiempo entre mediciones</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="../vista/calibrar.php" class="nav-link">
                  <i class="far fa-circle"></i>
                  <p>Calibrar</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="../vista/contacto.php" class="nav-link">
              <i class="fas fa-user-circle"></i>
              <p>
                Contacto
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
