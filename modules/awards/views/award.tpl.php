<?php if ($image_id): ?>
	<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="" />
<?php endif ?>
<div class="card-body">
	<div class="text-center mb-5">
		<h5>Classement de l'équipe <a href="<?php echo url('awards/team/'.$team_id.'/'.$team_name) ?>"><b><?php echo $team_title ?></b></a></h5>
		<?php if ($ranking == 1): ?>
			<h1 class="m-0"><?php echo icon('fas fa-trophy trophy-gold fa-3x') ?></h1>
			<big><b><?php echo $ranking ?>er</b> sur <?php echo $participants.($participants > 1 ? ' équipes' : ' équipe') ?></big>
		<?php endif ?>
		<?php if ($ranking == 2): ?>
			<h1 class="m-0"><?php echo icon('fas fa-trophy trophy-silver fa-3x') ?></h1>
			<big><b><?php echo $ranking ?>ème</b> sur <?php echo $participants.($participants > 1 ? ' équipes' : ' équipe') ?></big>
		<?php endif ?>
		<?php if ($ranking == 3): ?>
			<h1 class="m-0"><?php echo icon('fas fa-trophy trophy-bronze fa-3x') ?></h1>
			<big><b><?php echo $ranking ?>ème</b> sur <?php echo $participants.($participants > 1 ? ' équipes' : ' équipe') ?></big>
		<?php endif ?>
		<?php if ($ranking >= 4): ?>
			<big><b><?php echo $ranking ?>ème</b> sur <?php echo $participants.($participants > 1 ? ' équipes' : ' équipe') ?></big>
		<?php endif ?>
	</div>
	<ul class="list-inline<?php echo $description ? '' : ' m-0' ?>">
		<li class="list-inline-item"><span data-toggle="tooltip" title="Date"><?php echo icon('far fa-calendar').' '.timetostr($this->lang('%d/%m/%Y'), $date) ?></span></li>
		<?php if ($location): ?><li class="list-inline-item"><span data-toggle="tooltip" title="Lieu"><?php echo icon('fas fa-map-marker-alt').' '.$location ?></span></li><?php endif ?>
		<li class="list-inline-item"><span data-toggle="tooltip" title="Jeu"><a href="<?php echo url('awards/game/'.$game_id.'/'.$game_name) ?>"><?php echo icon('fas fa-gamepad').' '.$game_title ?></a></span></li>
		<li class="list-inline-item"><span data-toggle="tooltip" title="Plateforme"><?php echo icon('fas fa-tv').' '.$platform ?></span></li>
		<li class="list-inline-item"><?php echo icon('fas fa-users').' '.$participants.($participants > 1 ? ' participants' : ' participant') ?></li>
	</ul>
	<?php echo $description ?: '' ?>
</div>
