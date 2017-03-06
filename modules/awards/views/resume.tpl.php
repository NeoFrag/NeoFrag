<div class="row">
	<div class="col-md-5 text-center">
		<h4>Tous nos podiums</h4>
		<ul class="list-inline no-margin">
			<li>
				<span data-toggle="tooltip" title="1ère place"><?php echo icon('fa-trophy trophy-gold fa-2x'); ?></span>
				<h4 class="no-margin"><?php echo $data['total_gold'][0]; ?></h4>
			</li>
			<li>
				<span data-toggle="tooltip" title="2ème place"><?php echo icon('fa-trophy trophy-silver fa-2x'); ?></span>
				<h4 class="no-margin"><?php echo $data['total_silver'][0]; ?></h4>
			</li>
			<li>
				<span data-toggle="tooltip" title="3ème place"><?php echo icon('fa-trophy trophy-bronze fa-2x'); ?></span>
				<h4 class="no-margin"><?php echo $data['total_bronze'][0]; ?></h4>
			</li>
		</ul>
	</div>
	<div class="col-md-7">
		<table class="table table-hover no-margin">
			<thead>
				<tr>
					<th>Équipes</th>
					<th class="text-center"><?php echo icon('fa-trophy trophy-gold'); ?></th>
					<th class="text-center"><?php echo icon('fa-trophy trophy-silver'); ?></th>
					<th class="text-center"><?php echo icon('fa-trophy trophy-bronze'); ?></th>
					<th class="text-center"><?php echo icon('fa-plus'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data['teams'] as $team): ?>
				<tr>
					<td><a href="<?php echo url('awards/team/'.$team['team_id'].'/'.$team['name']); ?>"><?php echo $team['team_title']; ?></a></td>
					<td class="text-center"><?php echo $team['total_gold']; ?></td>
					<td class="text-center"><?php echo $team['total_silver']; ?></td>
					<td class="text-center"><?php echo $team['total_bronze']; ?></td>
					<td class="text-center"><?php echo $team['total_other']; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>