<?php if ($image_id): ?>
<span class="badge badge-dark position-absolute mt-4 ml-4"><?php echo icon('fas fa-gamepad').' '.$game ?></span>
<img class="card-img" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="">
<?php endif ?>
<div class="card-body">
	<div class="row align-items-center">
		<?php if ($icon_id): ?>
		<div class="col-2 text-center">
			<img class="img-fluid rounded" src="<?php echo NeoFrag()->model2('file', $icon_id)->path() ?>" alt="">
		</div>
		<?php endif ?>
		<div class="col-<?php echo $icon_id ? '10' : '12' ?>">
			<h2 class="mb-0"><a href="<?php echo url('teams/'.$team_id.'/'.url_title($name)) ?>"><?php echo $title ?></a></h2>
			<ul class="list-inline mb-0">
				<li class="list-inline-item"><?php echo icon('fas fa-users').' '.$this->lang('%d joueur|%d joueurs', count($players), count($players)) ?></li>
				<?php if ($this->config->teams_display_matches && $events): ?><li class="list-inline-item"><?php echo icon('fas fa-crosshairs').' '.$this->lang('%d match réalisé|%d matchs réalisés', count($events), count($events)) ?></li><?php endif ?>
			</ul>
		</div>
	</div>
	<?php if ($description): ?>
	<hr />
	<h4><?php echo $this->lang('Présentation') ?></h4>
	<?php echo $description ?>
	<?php endif ?>
	<?php if ($players): ?>
	<h4 class="mt-4"><?php echo $this->lang('Nos joueurs') ?></h4>
	<ul class="list-inline mb-0">
		<?php foreach ($players as $player): ?>
		<li class="list-inline-item text-center" data-toggle="tooltip" title="<?php echo $player['username'] ?>">
			<?php echo $this->module('user')->model2('user', $player['user_id'])->avatar()->append_attr('class', 'm-auto') ?>
			<small class="text-muted"><?php echo $player['title'] ?></small>
		</li>
		<?php endforeach ?>
	</ul>
	<?php endif ?>
</div>
