<?php if ($events): ?>
<ul class="list-group list-group-flush">
	<?php foreach ($events as $event): ?>
	<li class="list-group-item">
		<?php if ($event['type'] == 1): ?>
			<?php
			echo icon('fas fa-crosshairs');

			$match = $this->module('events')->model('matches')->get_match_info($event['event_id']);

			$opponent = '&nbsp;'.$match['opponent']['title'];

			if ($match['opponent']['country'])
			{
				$opponent .= '<img src="'.url('images/flags/'.$match['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$match['opponent']['country']].'" style="margin-left: 10px;" alt="" />';
			}

			echo '<a href="'.url('events/'.$event['event_id'].'/'.url_title($event['title'])).'">'.$opponent.'</a>';
			?>
			<div class="float-right">
				<?php if ($event['nb_rounds'] > 0): ?>
					<?php echo $this->module('events')->model('matches')->display_scores($match['scores'], $color) ?>
					<span class="<?php echo $color ?>"><?php echo $match['scores'][0] ?>:<?php echo $match['scores'][1] ?></span>
				<?php else: ?>
					<i>À jouer</i>
				<?php endif ?>
			</div>
		<?php else: ?>
			<?php echo icon('far fa-calendar') ?>
			<a href="<?php echo url('events/'.$event['event_id'].'/'.url_title($event['title'])) ?>"><?php echo str_shortener($event['title'], 30) ?></a>
		<?php endif ?>
	</li>
	<?php endforeach ?>
</ul>
<?php else: ?>
<div class="card-body">Aucun événement...</div>
<?php endif ?>
