<div class="text-center">
	<h4>Tous nos podiums</h4>
	<ul class="list-inline">
		<li>
			<div class="well">
				<span data-toggle="tooltip" title="1ère place"><?php echo icon('fa-trophy trophy-gold fa-2x'); ?></span><br />
				<?php echo $data['total_gold'][0].($data['total_gold'][0] > 1 ? ' trophées' : ' trophée'); ?>
			</div>
		</li>
		<li>
			<div class="well">
				<span data-toggle="tooltip" title="2ème place"><?php echo icon('fa-trophy trophy-silver fa-2x'); ?></span><br />
				<?php echo $data['total_silver'][0].($data['total_silver'][0] > 1 ? ' trophées' : ' trophée'); ?>
			</div>
		</li>
		<li>
			<div class="well">
				<span data-toggle="tooltip" title="3ème place"><?php echo icon('fa-trophy trophy-bronze fa-2x'); ?></span><br />
				<?php echo $data['total_bronze'][0].($data['total_bronze'][0] > 1 ? ' trophées' : ' trophée'); ?>
			</div>
		</li>
	</ul>
</div>
<table class="table table-bordered">
	<tbody>
		<tr>
			<td class="col-md-4 text-center">
				<h1><?php echo icon('fa-trophy'); ?></h1>
				<p>
					La plus récompensée<br />
					<?php if ($data['best_team_awards']): ?>
						<b><a href="<?php echo url('awards/team/'.$data['best_team_awards'][0]['team_id'].'/'.$data['best_team_awards'][0]['name']); ?>"><?php echo $data['best_team_awards'][0]['team_title']; ?></a></b>
					<?php else: ?>
						-
					<?php endif; ?>
				</p>
			</td>
			<td class="col-md-4 text-center">
				<h1><?php echo icon('fa-star-o'); ?></h1>
				<p>
					Meilleur classement<br />
					<?php if ($data['best_team']): ?>
						<b><a href="<?php echo url('awards/team/'.$data['best_team'][0]['team_id'].'/'.$data['best_team'][0]['name']); ?>"><?php echo $data['best_team'][0]['team_title']; ?></a></b>
					<?php else: ?>
						-
					<?php endif; ?>
				</p>
			</td>
			<td class="col-md-4 text-center">
				<h1><?php echo icon('fa-gamepad'); ?></h1>
				<p>
					Meilleur jeu<br />
					<?php if ($data['best_game_awards']): ?>
						<b><a href="<?php echo url('awards/game/'.$data['best_game_awards'][0]['game_id'].'/'.$data['best_game_awards'][0]['name']); ?>"><?php echo $data['best_game_awards'][0]['game_title']; ?></a></b>
					<?php else: ?>
						-
					<?php endif; ?>
				</p>
			</td>
		</tr>
	</tbody>
</table>
<div class="table-responsive">
	<table class="table table-hover no-margin">
		<thead>
			<tr>
				<th class="col-md-6"><h4 class="no-margin">Classement de nos équipes</h4></th>
				<th class="text-center"><span data-toggle="tooltip" title="1ère place"><?php echo icon('fa-trophy trophy-gold'); ?></span></th>
				<th class="text-center"><span data-toggle="tooltip" title="2ème place"><?php echo icon('fa-trophy trophy-silver'); ?></span></th>
				<th class="text-center"><span data-toggle="tooltip" title="3ème place"><?php echo icon('fa-trophy trophy-bronze'); ?></span></th>
				<th class="text-center"><span data-toggle="tooltip" title="Autre place"><?php echo icon('fa-plus'); ?></span></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data['teams'] as $team): ?>
			<tr>
				<td class="col-md-6 vcenter"><a href="<?php echo url('awards/team/'.$team['team_id'].'/'.$team['name']); ?>"><?php echo $team['team_title']; ?></a></td>
				<td class="text-center">
					<input class="knob" type="text" value="<?php echo $team['total_gold']; ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo ($team['total_gold'] + $team['total_silver'] + $team['total_bronze'] + $team['total_other']); ?>" data-width="50" data-height="40" data-fgColor="#F0B036" data-displayInput="true" data-readonly="true" autocomplete="off" />
				</td>
				<td class="text-center">
					<input class="knob" type="text" value="<?php echo $team['total_silver']; ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo ($team['total_gold'] + $team['total_silver'] + $team['total_bronze'] + $team['total_other']); ?>" data-width="50" data-height="40" data-fgColor="#CCCCCC" data-displayInput="true" data-readonly="true" autocomplete="off" />
				</td>
				<td class="text-center">
					<input class="knob" type="text" value="<?php echo $team['total_bronze']; ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo ($team['total_gold'] + $team['total_silver'] + $team['total_bronze'] + $team['total_other']); ?>" data-width="50" data-height="40" data-fgColor="#CE7C42" data-displayInput="true" data-readonly="true" autocomplete="off" />
				</td>
				<td class="text-center">
					<input class="knob" type="text" value="<?php echo $team['total_other']; ?>" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" data-min="0" data-max="<?php echo ($team['total_gold'] + $team['total_silver'] + $team['total_bronze'] + $team['total_other']); ?>" data-width="50" data-height="40" data-fgColor="#CCCCCC" data-displayInput="true" data-readonly="true" autocomplete="off" />
				</td>
			</tr>
			<?php endforeach; ?>
			<?php if (!$data['teams']): ?>
			<tr>
				<td class="text-center" colspan="5">Aucun classement</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>