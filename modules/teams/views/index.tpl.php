<?php if ($image_id): ?>
<a href="<?php echo url('teams/'.$team_id.'/'.url_title($name)) ?>"><img class="card-img" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt=""></a>
<?php endif ?>
<div class="card-body">
	<div class="row align-items-center">
		<?php if ($icon_id): ?>
		<div class="col-2">
			<img class="img-fluid rounded" src="<?php echo NeoFrag()->model2('file', $icon_id)->path() ?>" alt="">
		</div>
		<?php endif ?>
		<div class="col-<?php echo $icon_id ? '10' : '12' ?>">
			<h3 class="mb-0"><a href="<?php echo url('teams/'.$team_id.'/'.url_title($name)) ?>"><?php echo $title ?></a></h3>
			<ul class="list-inline mb-0">
				<li class="list-inline-item"><?php echo icon('fas fa-gamepad').' '.$game_title ?></li>
				<li class="list-inline-item"><?php echo icon('fas fa-users').' '.$this->lang('%d joueur|%d joueurs', count($players), count($players)) ?></li>
				<?php if ($this->config->teams_display_matches && $events): ?><li class="list-inline-item"><?php echo icon('fas fa-crosshairs').' '.$this->lang('%d match réalisé|%d matchs réalisés', count($events), count($events)) ?></li><?php endif ?>
			</ul>
		</div>
	</div>
</div>
