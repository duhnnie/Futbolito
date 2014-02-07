<?php $results = $datos["result"];?>
<table class="table">
    <thead>
        <tr>
            <th style="text-align: right">Equipo</th>
            <th style="text-align: right">Score</th>
            <th style="text-align: center"></th>
            <th style="text-align: left">Score</th>
            <th style="text-align: left">Equipo</th>
        </tr>
    </thead>
    <tbody>
        <?php for($i = 0; $i < count($results); $i++):?>
        <tr>
            <td style="text-align: right"><?php echo $results[$i]->team_1?></td>
            <td style="text-align: right"><?php echo $results[$i]->team_1_score?></td>
            <td style="text-align: center">-</td>
            <td style="text-align: left"><?php echo $results[$i]->team_2_score?></td>
            <td style="text-align: left"><?php echo $results[$i]->team_2?></td>
        </tr>
        <?php endfor;?>
    </tbody>
</table>