<? $datos = $datos["datos"];?>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>Equipo</th>
			<th>Miembros</th>
			<th title="Encuentros Jugados">EJ</th>
			<th title="Encuentros Ganados">EG</th>
			<th title="Encuentros Perdidos">EP</th>
			<th title="Partidos Jugados">PJ</th>
			<th title="Partidos Ganados">PG</th>
			<th title="Partidos Perdidos">PP</th>
			<th title="Goles a favor">GF</th>
			<th title="Goles en contra">GC</th>
			<th title="Difrerencia de gol">Dif.</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i = 0; $i < count($datos); $i++):?>
			<tr>
				<td><?php echo $i + 1?></td>
				<td><?php echo $datos[$i]->name; ?></td>
				<td><?php echo $datos[$i]->member_1 ."<br/>".$datos[$i]->member_2; ?></td>
				<td><?php echo $datos[$i]->EJ; ?></td>
				<td><?php echo $datos[$i]->EG; ?></td>
				<td><?php echo $datos[$i]->EJ - $datos[$i]->EG; ?></td>
				<td><?php echo $datos[$i]->JJ; ?></td>
				<td><?php echo $datos[$i]->JG; ?></td>
				<td><?php echo $datos[$i]->JJ - $datos[$i]->JG; ?></td>
				<td><?php echo $datos[$i]->GF; ?></td>
				<td><?php echo $datos[$i]->GC; ?></td>
				<td><?php echo $datos[$i]->GF - $datos[$i]->GC; ?></td>
			</tr>
		<?php endfor;?>
	</tbody>
</table>