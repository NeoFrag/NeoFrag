<ul class="list-group">
	<?php foreach ($data['awards'] as $award): ?>
	<li class="list-group-item">
		<div class="pull-right">
			<span data-toggle="tooltip" title="<?php echo $award['place']; ?>"><?php echo icon('fa-map-marker'); ?></span>
			<a href="<?php echo url('awards/'.$award['award_id'].'/'.url_title($award['name'])); ?>"><?php echo str_shortener($award['name'], 20); ?></a>
		</div>
		<ul class="list-inline no-margin">
			<li>
				<?php
				if ($award['ranking'] == 1)
				{
					echo '<span data-toggle="tooltip" title="'.$award['ranking'].'er / '.$award['participants'].' équipes">'.icon('fa-trophy trophy-gold').'</span>';
				}
				else if ($award['ranking'] == 2)
				{
					echo '<span data-toggle="tooltip" title="'.$award['ranking'].'ème / '.$award['participants'].' équipes">'.icon('fa-trophy trophy-silver').'</span>';
				}
				else if ($award['ranking'] == 3)
				{
					echo '<span data-toggle="tooltip" title="'.$award['ranking'].'ème / '.$award['participants'].' équipes">'.icon('fa-trophy trophy-bronze').'</span>';
				}
				else
				{
					echo $award['ranking'].'<small>ème</small>';
				}
				?>
			</li>
			<li><?php echo $award['platform']; ?></li>
		</ul>
	</li>
	<?php endforeach; ?>
</ul>