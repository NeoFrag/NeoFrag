<?php if (${'stats-team'} || ${'stats-game'}): ?>
	<?php if ($image_id): ?>
		<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="" />
	<?php endif ?>
	<div class="card-body">
		<div class="text-center">
			<h5><?php echo ${'stats-team'} ? 'Palmarès de cette équipe' : 'Palmarès sur ce jeu' ?></h5>
			<ul class="list-inline">
				<li class="list-inline-item">
					<span data-toggle="tooltip" title="1ère place"><?php echo icon('fas fa-trophy fa-2x trophy-gold') ?></span><br />
					<?php echo $total_gold[0].($total_gold[0] > 1 ? ' trophées' : ' trophée') ?>
				</li>
				<li class="list-inline-item">
					<span data-toggle="tooltip" title="2ème place"><?php echo icon('fas fa-trophy fa-2x trophy-silver') ?></span><br />
					<?php echo $total_silver[0].($total_silver[0] > 1 ? ' trophées' : ' trophée') ?>
				</li>
				<li class="list-inline-item">
					<span data-toggle="tooltip" title="3ème place"><?php echo icon('fas fa-trophy fa-2x trophy-bronze') ?></span><br />
					<?php echo $total_bronze[0].($total_bronze[0] > 1 ? ' trophées' : ' trophée') ?>
				</li>
			</ul>
		</div>
	</div>
<?php endif ?>
<table class="table table-hover">
	<thead>
		<tr>
			<th></th>
			<th><span data-toggle="tooltip" title="Classement"><?php echo icon('fas fa-trophy') ?></span></th>
			<th><span data-toggle="tooltip" title="Plateforme"><?php echo icon('fas fa-tv') ?></span></th>
			<th colspan="2">Événement</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ($awards):
			foreach ($awards as $award): ?>
			<tr>
				<td>
					<span data-toggle="tooltip" title="<?php echo timetostr($this->lang('%A %e %B %Y'), $award['date']) ?>"><?php echo icon('far fa-calendar') ?></span>
				</td>
				<td>
					<?php
					if ($award['ranking'] == 1)
					{
						echo '<span data-toggle="tooltip" title="'.$award['ranking'].'er / '.$award['participants'].' équipes">'.icon('fas fa-trophy trophy-gold').'</span>';
					}
					else if ($award['ranking'] == 2)
					{
						echo '<span data-toggle="tooltip" title="'.$award['ranking'].'ème / '.$award['participants'].' équipes">'.icon('fas fa-trophy trophy-silver').'</span>';
					}
					else if ($award['ranking'] == 3)
					{
						echo '<span data-toggle="tooltip" title="'.$award['ranking'].'ème / '.$award['participants'].' équipes">'.icon('fas fa-trophy trophy-bronze').'</span>';
					}
					else
					{
						echo $award['ranking'].'<small>ème</small>';
					}
					?>
				</td>
				<td><?php echo $award['platform'] ?></td>
				<td>
					<a href="<?php echo url('awards/'.$award['award_id'].'/'.url_title($award['name'])) ?>"><?php echo $award['name'] ?></a>
				</td>
				<td>
					<?php if ($award['location']): ?><div><span data-toggle="tooltip" title="Lieu"><?php echo icon('fas fa-map-marker-alt').' '.$award['location'] ?></span></div><?php endif ?>
				</td>
			</tr>
		<?php
			endforeach;
		else:
		?>
		<tr>
			<td colspan="4">Aucun trophée...</td>
		</tr>
		<?php endif ?>
	</tbody>
</table>
