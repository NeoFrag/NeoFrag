<?php if ($data['matches']): ?>
<ul class="list-group">
	<?php foreach ($data['matches'] as $match): ?>
	<li class="list-group-item">
		<div class="row">
			<div class="col-md-1">
				<?php echo $this->model('matches')->display_scores($match['match']['scores'], $color) ?>
			</div>
			<?php if ($match['match']['opponent']['image_id']): ?>
			<div class="text-center col-md-1">
				<img src="<?php echo path($match['match']['opponent']['image_id']) ?>" class="img-responsive" alt="" />
			</div>
			<?php endif ?>
			<div class="text-left col-md-<?php echo $match['match']['opponent']['image_id'] ? 7 : 8 ?>">
				<a href="<?php echo url('events/'.$match['event_id'].'/'.url_title($match['title'])) ?>">
				<?php
					$opponent = $match['match']['opponent']['title'];

					if ($match['match']['opponent']['country'])
					{
						$opponent .= '<img src="'.url('themes/default/images/flags/'.$match['match']['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$match['match']['opponent']['country']].'" style="margin-left: 10px;" alt="" />';
					}

					echo $opponent;
				?>
				</a>
			</div>
			<div class="text-left col-md-3 text-right">
				<span class="<?php echo $color ?>"><?php echo $match['match']['scores'][0] ?>:<?php echo $match['match']['scores'][1] ?></span>
			</div>
		</div>
	</li>
	<?php endforeach ?>
</ul>
<?php else: ?>
<div class="panel-body">Aucun résultat...</div>
<?php endif ?>
