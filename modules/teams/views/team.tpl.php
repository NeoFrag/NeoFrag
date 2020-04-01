<?php if ($image_id): ?>
<span class="badge badge-dark position-absolute mt-3 ml-3"><?php echo icon('fas fa-gamepad').' '.$game ?></span>
<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="">
<?php endif ?>
<div class="card-body">
	<h3><a href="<?php echo url('teams/'.$team_id.'/'.url_title($name)) ?>"><?php echo $title ?></a></h3>
	<hr />
	<?php if ($description): ?>
	<h4><?php echo $this->lang('Présentation') ?></h4>
	<p><?php echo $description ?></p>
	<?php endif ?>
	<?php if ($players): ?>
	<h4><?php echo $this->lang('Nos joueurs') ?></h4>
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
