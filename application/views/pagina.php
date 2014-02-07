<!DOCTYPE html>
<html lang="es">
<?php $this->load->view("head", array("titulo" => $titulo))?>
<?php $this->load->view("body", array("pagina" => $pagina, "datos" => $datos, "equipos" => $equiposList)); ?>
</html>
