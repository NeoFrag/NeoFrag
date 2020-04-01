<div class="text-center">
	<b>Équipe la plus récompensée</b>
	<h1><?php echo icon('fas fa-trophy') ?></h1>
	Équipe <a href="<?php echo url('awards/team/'.$team_id.'/'.$name) ?>"><b><?php echo $team_title ?></b></a><br />
	Avec <?php echo $nb_awards > 1 ? $nb_awards.' trophées' : $nb_awards.' trophée' ?>
</div>
