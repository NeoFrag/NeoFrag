<?php if ($image_id): ?>
	<img class="img-responsive" src="<?php echo path($image_id) ?>" alt="" />
<?php endif ?>
<div class="panel-body">
	<div class="well text-center">
		<h4>Classement de l'équipe <a href="<?php echo url('awards/team/'.$team_id.'/'.$team_name) ?>"><b><?php echo $team_title ?></b></a></h4>
		<?php if ($ranking == 1): ?>
			<h1 class="no-margin"><?php echo icon('fa-trophy trophy-gold fa-3x') ?></h1>
			<big><b><?php echo $ranking ?>er</b> sur <?php echo $participants.($participants > 1 ? ' équipes' : ' équipe') ?></big>
		<?php endif ?>
		<?php if ($ranking == 2): ?>
			<h1 class="no-margin"><?php echo icon('fa-trophy trophy-silver fa-3x') ?></h1>
			<big><b><?php echo $ranking ?>ème</b> sur <?php echo $participants.($participants > 1 ? ' équipes' : ' équipe') ?></big>
		<?php endif ?>
		<?php if ($ranking == 3): ?>
			<h1 class="no-margin"><?php echo icon('fa-trophy trophy-bronze fa-3x') ?></h1>
			<big><b><?php echo $ranking ?>ème</b> sur <?php echo $participants.($participants > 1 ? ' équipes' : ' équipe') ?></big>
		<?php endif ?>
		<?php if ($ranking >= 4): ?>
			<big><b><?php echo $ranking ?>ème</b> sur <?php echo $participants.($participants > 1 ? ' équipes' : ' équipe') ?></big>
		<?php endif ?>
	</div>
	<ul class="list-inline<?php echo $description ? '' : ' no-margin' ?>">
		<li><span data-toggle="tooltip" title="Date"><?php echo icon('fa-calendar-o').' '.timetostr($this->lang('date_short'), $date) ?></span></li>
		<?php if ($location): ?><li><span data-toggle="tooltip" title="Lieu"><?php echo icon('fa-map-marker').' '.$location ?></span></li><?php endif ?>
		<li><span data-toggle="tooltip" title="Jeu"><a href="<?php echo url('awards/game/'.$game_id.'/'.$game_name) ?>"><?php echo icon('fa-gamepad').' '.$game_title ?></a></span></li>
		<li><span data-toggle="tooltip" title="Plateforme"><?php echo icon('fa-tv').' '.$platform ?></span></li>
		<li><?php echo icon('fa-users').' '.$participants.($participants > 1 ? ' participants' : ' participant') ?></li>
	</ul>
	<?php echo $description ? $description : '' ?>
</div>
