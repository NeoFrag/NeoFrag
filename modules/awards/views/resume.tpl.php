<div class="row">
	<div class="col-5 text-center">
		<h4><?php echo $this->lang('Tous nos podiums') ?></h4>
		<ul class="list-inline m-0">
			<li>
				<span data-toggle="tooltip" title="<?php echo $this->lang('1ère place') ?>"><?php echo icon('fa-trophy trophy-gold fa-2x') ?></span>
				<h4 class="m-0"><?php echo $total_gold[0] ?></h4>
			</li>
			<li>
				<span data-toggle="tooltip" title="<?php echo $this->lang('2ème place') ?>"><?php echo icon('fa-trophy trophy-silver fa-2x') ?></span>
				<h4 class="m-0"><?php echo $total_silver[0] ?></h4>
			</li>
			<li>
				<span data-toggle="tooltip" title="<?php echo $this->lang('3ème place') ?>"><?php echo icon('fa-trophy trophy-bronze fa-2x') ?></span>
				<h4 class="m-0"><?php echo $total_bronze[0] ?></h4>
			</li>
		</ul>
	</div>
	<div class="col-7">
		<table class="table table-hover m-0">
			<thead>
				<tr>
					<th><?php echo $this->lang('Équipes') ?></th>
					<th class="text-center"><?php echo icon('fa-trophy trophy-gold') ?></th>
					<th class="text-center"><?php echo icon('fa-trophy trophy-silver') ?></th>
					<th class="text-center"><?php echo icon('fa-trophy trophy-bronze') ?></th>
					<th class="text-center"><?php echo icon('fa-plus') ?></th>
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
