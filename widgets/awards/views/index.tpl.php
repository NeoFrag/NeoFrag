<ul class="list-group list-group-flush">
	<?php foreach ($awards as $award): ?>
	<li class="list-group-item">
		<div class="float-right">
			<span data-toggle="tooltip" title="<?php echo $award['location'] ?>"><?php echo icon('fas fa-map-marker-alt') ?></span>
			<a href="<?php echo url('awards/'.$award['award_id'].'/'.url_title($award['name'])) ?>"><?php echo str_shortener($award['name'], 20) ?></a>
		</div>
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

		echo $award['platform'];
		?>
	</li>
	<?php endforeach ?>
</ul>
