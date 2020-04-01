<div class="row">
	<div class="col-5 text-center">
		<h5>Tous nos podiums</h5>
		<div class="row">
			<div class="col text-center">
				<span data-toggle="tooltip" title="1ère place"><?php echo icon('fas fa-trophy trophy-gold fa-2x') ?></span>
				<h4 class="m-0"><?php echo $total_gold[0] ?></h4>
			</div>
			<div class="col text-center">
				<span data-toggle="tooltip" title="2ème place"><?php echo icon('fas fa-trophy trophy-silver fa-2x') ?></span>
				<h4 class="m-0"><?php echo $total_silver[0] ?></h4>
			</div>
			<div class="col text-center">
				<span data-toggle="tooltip" title="3ème place"><?php echo icon('fas fa-trophy trophy-bronze fa-2x') ?></span>
				<h4 class="m-0"><?php echo $total_bronze[0] ?></h4>
			</div>
		</div>
	</div>
	<div class="col-7">
		<table class="table table-hover m-0">
			<thead>
				<tr>
					<th>Équipes</th>
					<th class="text-center"><?php echo icon('fas fa-trophy trophy-gold') ?></th>
					<th class="text-center"><?php echo icon('fas fa-trophy trophy-silver') ?></th>
					<th class="text-center"><?php echo icon('fas fa-trophy trophy-bronze') ?></th>
					<th class="text-center"><?php echo icon('fas fa-plus') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($teams as $team): ?>
				<tr>
					<td><a href="<?php echo url('awards/team/'.$team['team_id'].'/'.$team['name']) ?>"><?php echo $team['team_title'] ?></a></td>
					<td class="text-center"><?php echo $team['total_gold'] ?></td>
					<td class="text-center"><?php echo $team['total_silver'] ?></td>
					<td class="text-center"><?php echo $team['total_bronze'] ?></td>
					<td class="text-center"><?php echo $team['total_other'] ?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
