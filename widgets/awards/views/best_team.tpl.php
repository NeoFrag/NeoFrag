<div class="text-center">
	<b>Équipe la plus récompensée</b>
	<h1><?php echo icon('fa-trophy'); ?></h1>
	Équipe <a href="<?php echo url('awards/team/'.$data['team_id'].'/'.$data['name']); ?>"><b><?php echo $data['team_title']; ?></b></a><br />
	Avec <?php echo $data['nb_awards'] > 1 ? $data['nb_awards'].' trophées' : $data['nb_awards'].' trophée'; ?>
</div>