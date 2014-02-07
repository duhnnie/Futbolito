<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Copa Futbolito Colosa 2014</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="<?php echo site_url("home");?>">Clasificaci&oacute;n</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Equipos <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo site_url('equipos'); ?>">Todos</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo site_url('equipos/newEquipo'); ?>">Anadir Equipo</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Primera Fase <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo site_url('stages/'); ?>">Registrar Juegos</a></li>
            <li class="divider"></li>
            <?php for($i = 0; $i < count($equipos); $i++):?>
            <li><a href="<?php echo site_url("stages/".$equipos[$i]->id) ?>"><?php echo $equipos[$i]->name ?></a></li>
            <?php endfor; ?>
          </ul>
        </li>
        <li><a href="<?php echo site_url('stages/first'); ?>">Fase Final</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Salir</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>