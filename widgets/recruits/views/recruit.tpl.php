<?php if ($image_id): ?>
<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="" />
<?php endif ?>
<div class="card-body">
	<p><big><?php echo icon($icon) ?> <a href="<?php echo url('recruits/'.$recruit_id.'/'.url_title($title)) ?>"><?php echo $title ?></a></big></p>
	<?php echo $introduction ?>
</div>
<ul class="list-group list-group-flush">
	<?php if ($team_id): ?>
	<li class="list-group-item">
		<span class="float-right"><b><?php echo $team_name ?></b></span>
		<?php echo icon('fas fa-headset') ?> Equipe
	</li>
	<?php endif ?>
	<li class="list-group-item">
		<span class="float-right"><b><?php echo $role ?></b></span>
		<?php echo icon('fas fa-sitemap') ?> Rôle proposé
	</li>
	<li class="list-group-item">
		<span class="float-right"><b><?php echo ($size) ?></b></span>
		<?php echo icon('fas fa-users').' '.($size > 1 ? 'Postes disponibles' : 'Poste disponible') ?>
	</li>
	<?php if ($date_end): ?>
	<li class="list-group-item">
		<span class="float-right"><b><?php echo timetostr('%e %b %Y', $date_end) ?></b></span>
		<?php echo icon('far fa-calendar') ?> Date limite
	</li>
	<?php endif ?>
</ul>
