<?php if ($data['image_id']): ?>
	<img class="img-responsive" src="<?php echo path($data['image_id']); ?>" alt="" />
<?php endif; ?>
<div class="panel-body">
	<div class="well text-center">
		<h4>Classement de l'équipe <a href="<?php echo url('awards/team/'.$data['team_id'].'/'.$data['team_name']); ?>"><b><?php echo $data['team_title']; ?></b></a></h4>
		<?php if ($data['ranking'] == 1): ?>
			<h1 class="no-margin"><?php echo icon('fa-trophy trophy-gold fa-3x'); ?></h1>
			<big><b><?php echo $data['ranking']; ?>er</b> sur <?php echo $data['participants'].($data['participants'] > 1 ? ' équipes' : ' équipe'); ?></big>
		<?php endif; ?>
		<?php if ($data['ranking'] == 2): ?>
			<h1 class="no-margin"><?php echo icon('fa-trophy trophy-silver fa-3x'); ?></h1>
			<big><b><?php echo $data['ranking']; ?>ème</b> sur <?php echo $data['participants'].($data['participants'] > 1 ? ' équipes' : ' équipe'); ?></big>
		<?php endif; ?>
		<?php if ($data['ranking'] == 3): ?>
			<h1 class="no-margin"><?php echo icon('fa-trophy trophy-bronze fa-3x'); ?></h1>
			<big><b><?php echo $data['ranking']; ?>ème</b> sur <?php echo $data['participants'].($data['participants'] > 1 ? ' équipes' : ' équipe'); ?></big>
		<?php endif; ?>
		<?php if ($data['ranking'] >= 4): ?>
			<big><b><?php echo $data['ranking']; ?>ème</b> sur <?php echo $data['participants'].($data['participants'] > 1 ? ' équipes' : ' équipe'); ?></big>
		<?php endif; ?>
	</div>
	<ul class="list-inline<?php echo $data['description'] ? '' : ' no-margin'; ?>">
		<li><span data-toggle="tooltip" title="Date"><?php echo icon('fa-calendar-o').' '.timetostr($this->lang('date_short'), $data['date']); ?></span></li>
		<?php if ($data['location']): ?><li><span data-toggle="tooltip" title="Lieu"><?php echo icon('fa-map-marker').' '.$data['location']; ?></span></li><?php endif; ?>
		<li><span data-toggle="tooltip" title="Jeu"><a href="<?php echo url('awards/game/'.$data['game_id'].'/'.$data['game_name']); ?>"><?php echo icon('fa-gamepad').' '.$data['game_title']; ?></a></span></li>
		<li><span data-toggle="tooltip" title="Plateforme"><?php echo icon('fa-tv').' '.$data['platform']; ?></span></li>
		<li><?php echo icon('fa-users').' '.$data['participants'].($data['participants'] > 1 ? ' participants' : ' participant'); ?></li>
	</ul>
	<?php echo $data['description'] ? $data['description'] : ''; ?>
</div>