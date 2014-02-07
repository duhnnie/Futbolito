<body>
	<?php $this->load->view("nav", array("equipos" => $equiposList)); ?>
	<div class="panel panel-default" style="margin: 50px">
	  <div class="panel-heading">
	    <h3 class="panel-title"><?php echo $titulo;?></h3>
	  </div>
	  <div class="panel-body">
	    <?php $this->load->view('contenido/'.$pagina, array("datos" => $datos)); ?>
	  </div>
	</div>
</body>