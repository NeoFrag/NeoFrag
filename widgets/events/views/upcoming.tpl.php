<?php if ($matches): ?>
<ul class="list-group">
	<?php foreach ($matches as $match): ?>
	<li class="list-group-item">
		<div class="row">
			<?php if ($match['match']['opponent']['image_id']): ?>
			<div class="text-center col-xs-1">
				<img src="<?php echo path($match['match']['opponent']['image_id']) ?>" class="img-responsive" alt="" />
			</div>
			<?php endif ?>
			<div class="col-md-<?php echo $match['match']['opponent']['image_id'] ? 7 : 8 ?>">
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
			<div class="col-xs-4 text-right">
				<?php echo icon('fa-clock-o') ?> <?php echo timetostr('%d/%m/%Y', $match['date']) ?>
			</div>
		</div>
	</li>
	<?php endforeach ?>
</ul>
<?php else: ?>
<div class="panel-body">Aucun match à venir...</div>
<?php endif ?>
