<?php if ($matches): ?>
<ul class="list-group list-group-flush">
	<?php foreach ($matches as $match): ?>
	<li class="list-group-item">
		<div class="row">
			<?php if ($match['match']['opponent']['image_id']): ?>
			<div class="text-center col-1">
				<img src="<?php echo NeoFrag()->model2('file', $match['match']['opponent']['image_id'])->path() ?>" class="img-fluid" alt="" />
			</div>
			<?php endif ?>
			<div class="col-<?php echo $match['match']['opponent']['image_id'] ? 11 : 12 ?>">
				<span class="pull-right"><small><?php echo icon('fa-clock-o') ?> <?php echo timetostr('%d/%m/%Y', $match['date']) ?></small></span>
				<a href="<?php echo url('events/'.$match['event_id'].'/'.url_title($match['title'])) ?>">
				<?php
					$opponent = $match['match']['opponent']['title'];

					if ($match['match']['opponent']['country'])
					{
						$opponent .= '<img src="'.url('images/flags/'.$match['match']['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$match['match']['opponent']['country']].'" style="margin-left: 10px;" alt="" />';
					}

					echo $opponent;
				?>
				</a>
			</div>
		</div>
	</li>
	<?php endforeach ?>
</ul>
<?php else: ?>
<div class="card-body">Aucun match à venir...</div>
<?php endif ?>
