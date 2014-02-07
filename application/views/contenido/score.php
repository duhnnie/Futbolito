<script>
	$(function(){
		$('.btn-close-score').on('click', function(e) {
			var id = this.id;
			$('#'+id+"-close").val(1);
			$('#'+id+"-team1").val(0);
			$('#'+id+"-team2").val(0);
		});
	});
</script>
<?php
	$encuentros = $datos['scores'];

	$match = NULL;
	for($i = 0; $i < count($encuentros); $i++):
	?>
		<?php if($encuentros[$i]->team_match !== $match):?>
			<?php if($match !== NULL):?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif;?>
			<?php $match = $encuentros[$i]->team_match; ?>
				<div class="panel panel-default" style="margin: 50px">
					<div class="panel-heading">
	    				<h3 class="panel-title"><?php echo $encuentros[$i]->team_1 . " vs. " . $encuentros[$i]->team_2;?></h3>
	  				</div>
	  				<div class="panel-body">
	    				<table class="table">
	    					<thead>
	    						<tr>
	    							<th>#</th>
	    							<th style="text-align: center"></th>
	    						</tr>
	    					</thead>
	    					<tbody>
		<?php endif; ?>
		<?php if($encuentros[$i]->open == 1): ?>
		<tr>
			<td><?php echo ($i%5) + 1;?></td>
			<td>
				<form id="<?php echo $encuentros[$i]->game."-form"?>" action="<?php echo site_url("score/update/".$encuentros[$i]->game."/".$encuentros[$i]->team_1_id."/".$encuentros[$i]->team_2_id)?>" method="post">
				<table>
					<tr>
						<td>
							<div class="input-group">
							  <span class="input-group-addon"><?php echo $encuentros[$i]->team_1;?></span>
							  <input type="number" name="team1" class="form-control" placeholder="score" required id="<?php echo $encuentros[$i]->game."-team1"?>">
							</div>
						</td>
						<td>
							<div class="input-group">
							  <span class="input-group-addon"><?php echo $encuentros[$i]->team_2;?></span>
							  <input type="number" name="team2" class="form-control" placeholder="score" required id="<?php echo $encuentros[$i]->game."-team2"?>">
							</div>
						</td>
						<td>
							&nbsp;&nbsp;&nbsp;
							<input type="hidden" name="match" value="<?php echo $encuentros[$i]->team_match?>"/>
							<input type="hidden" name="cerrar" value="0" id="<?php echo $encuentros[$i]->game."-close"?>"/>
							<input type="submit" class="btn btn-default" value="Registrar"/>
							<input type="submit" class="btn btn-default btn-close-score" value="Cerrar" id="<?php echo $encuentros[$i]->game?>"/>
						</td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
		<?php endif;?>
	<?
	endfor;

	if(count($encuentros)>0) :
	?>
	  </div>
	</div>
	<?
	endif;
?>