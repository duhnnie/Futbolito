<script>
	$(function() {
		$('.btn-remove-team').on('click', function(e) {
			if(!confirm("Est'a seguro que desea quitar este equipo?")) {
				e.preventDefault();
			}
		});
	});
</script>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>Equipo</th>
			<th colspan="2" style="text-align: center">Miembros</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$equipos = $datos['equipos'];
			for($i = 0; $i < count($equipos); $i++):
		?>
		<tr>
			<td><?php echo $i + 1;?></td>
			<td><?php echo $equipos[$i]->name ?></td>
			<td><?php echo $equipos[$i]->member_1 ?></td>
			<td><?php echo $equipos[$i]->member_2 ?></td>
			<td><a class="btn btn-default btn-remove-team" href="<?php echo site_url("equipos/quitar/".$equipos[$i]->id); ?>">Quitar</a></td>
		</tr>
		<?
			endfor;
		?>
	</tbody>
</table>