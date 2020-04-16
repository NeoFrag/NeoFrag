<div class="card-body">
	<div class="text-center">
		<h5>Tous nos podiums</h5>
		<ul class="list-inline">
			<li class="list-inline-item">
				<span data-toggle="tooltip" title="1ère place"><?php echo icon('fas fa-trophy trophy-gold fa-2x') ?></span><br />
				<?php echo $total_gold[0].($total_gold[0] > 1 ? ' trophées' : ' trophée') ?>
			</li>
			<li class="list-inline-item">
				<span data-toggle="tooltip" title="2ème place"><?php echo icon('fas fa-trophy trophy-silver fa-2x') ?></span><br />
				<?php echo $total_silver[0].($total_silver[0] > 1 ? ' trophées' : ' trophée') ?>
			</li>
			<li class="list-inline-item">
				<span data-toggle="tooltip" title="3ème place"><?php echo icon('fas fa-trophy trophy-bronze fa-2x') ?></span><br />
				<?php echo $total_bronze[0].($total_bronze[0] > 1 ? ' trophées' : ' trophée') ?>
			</li>
		</ul>
	</div>
	<hr />
	<div class="row text-center">
		<div class="col">
			<h1><?php echo icon('fas fa-trophy') ?></h1>
			<p>
				La plus récompensée<br />
				<?php if ($best_team_awards): ?>
					<b><a href="<?php echo url('awards/team/'.$best_team_awards[0]['team_id'].'/'.$best_team_awards[0]['name']) ?>"><?php echo $best_team_awards[0]['team_title'] ?></a></b>
				<?php else: ?>
					-
				<?php endif ?>
			</p>
		</div>
		<div class="col">
			<h1><?php echo icon('far fa-star') ?></h1>
			<p>
				Meilleur classement<br />
				<?php if ($best_team && ($best_team[0]['total_gold'] || $best_team[0]['total_silver'] || $best_team[0]['total_bronze'] || $best_team[0]['total_other'])): ?>
					<b><a href="<?php echo url('awards/team/'.$best_team[0]['team_id'].'/'.$best_team[0]['name']) ?>"><?php echo $best_team[0]['team_title'] ?></a></b>
				<?php else: ?>
					-
				<?php endif ?>
			</p>
		</div>
		<div class="col">
			<h1><?php echo icon('fas fa-gamepad') ?></h1>
			<p>
				Meilleur jeu<br />
				<?php if ($best_game_awards): ?>
					<b><a href="<?php echo url('awards/game/'.$best_game_awards[0]['game_id'].'/'.$best_game_awards[0]['name']) ?>"><?php echo $best_game_awards[0]['game_title'] ?></a></b>
				<?php else: ?>
					-
				<?php endif ?>
			</p>
		</div>
	</div>
</div>
<table class="table table-hover">
	<thead>
		<tr>
			<th class="col-6"><h4>Classement de nos équipes</h4></th>
			<th class="text-center"><span data-toggle="tooltip" title="1ère place"><?php echo icon('fas fa-trophy trophy-gold') ?></span></th>
			<th class="text-center"><span data-toggle="tooltip" title="2ème place"><?php echo icon('fas fa-trophy trophy-silver') ?></span></th>
			<th class="text-center"><span data-toggle="tooltip" title="3ème place"><?php echo icon('fas fa-trophy trophy-bronze') ?></span></th>
			<th class="text-center"><span data-toggle="tooltip" title="Autre place"><?php echo icon('fas fa-plus') ?></span></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($teams as $team): ?>
		<tr>
			<td class="col-6 align-middle"><a href="<?php echo url('awards/team/'.$team['team_id'].'/'.$team['name']) ?>"><?php echo $team['team_title'] ?></a></td>
			<td class="text-center">
				<input class="knob" type="text" value="<?php echo $team['total_gold'] ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo ($team['total_gold'] + $team['total_silver'] + $team['total_bronze'] + $team['total_other']) ?>" data-width="50" data-height="40" data-fgColor="#F0B036" data-displayInput="true" data-readonly="true" autocomplete="off" />
			</td>
			<td class="text-center">
				<input class="knob" type="text" value="<?php echo $team['total_silver'] ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo ($team['total_gold'] + $team['total_silver'] + $team['total_bronze'] + $team['total_other']) ?>" data-width="50" data-height="40" data-fgColor="#CCCCCC" data-displayInput="true" data-readonly="true" autocomplete="off" />
			</td>
			<td class="text-center">
				<input class="knob" type="text" value="<?php echo $team['total_bronze'] ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo ($team['total_gold'] + $team['total_silver'] + $team['total_bronze'] + $team['total_other']) ?>" data-width="50" data-height="40" data-fgColor="#CE7C42" data-displayInput="true" data-readonly="true" autocomplete="off" />
			</td>
			<td class="text-center">
				<input class="knob" type="text" value="<?php echo $team['total_other'] ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo ($team['total_gold'] + $team['total_silver'] + $team['total_bronze'] + $team['total_other']) ?>" data-width="50" data-height="40" data-fgColor="#CCCCCC" data-displayInput="true" data-readonly="true" autocomplete="off" />
			</td>
		</tr>
		<?php endforeach ?>
		<?php if (!$teams): ?>
		<tr>
			<td class="text-center" colspan="5">Aucun classement</td>
		</tr>
		<?php endif ?>
	</tbody>
</table>
