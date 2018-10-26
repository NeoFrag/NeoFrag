<?php if ($image_id): ?>
<a href="<?php echo url('teams/'.$team_id.'/'.url_title($name)) ?>"><img class="card-img" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt=""></a>
<?php endif ?>
<div class="card-body">
	<h3 class="mb-0"><a href="<?php echo url('teams/'.$team_id.'/'.url_title($name)) ?>"><?php echo $title ?></a></h3>
	<ul class="list-inline<?php echo $players ? '' : ' mb-0' ?>">
		<li class="list-inline-item"><?php echo icon('fa-gamepad').' '.$game_title ?></li>
		<li class="list-inline-item"><?php echo icon('fa-users').' '.$this->lang('%d joueur|%d joueurs', count($players), count($players)) ?></li>
		<li class="list-inline-item"><?php echo icon('fa-crosshairs').' '.$this->lang('%d match réalisé|%d matchs réalisés', count($events), count($events)) ?></li>
	</ul>
	<?php if ($players): ?>
	<ul class="list-inline mb-0">
		<?php foreach ($players as $player): ?>
		<li class="list-inline-item text-center" data-toggle="tooltip" title="<?php echo $player['username'] ?>">
			<?php echo NeoFrag()->model2('user', $player['user_id'])->avatar()->append_attr('class', 'm-auto') ?>
			<small class="text-muted"><?php echo $player['title'] ?></small>
		</li>
		<?php endforeach ?>
	</ul>
	<?php endif ?>
</div>
