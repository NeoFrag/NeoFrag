<div class="text-center">
	<b>Meilleur jeu</b>
	<h1><?php echo icon('fa-gamepad'); ?></h1>
	<a href="<?php echo url('awards/game/'.$data['game_id'].'/'.$data['name']); ?>"><b><?php echo $data['game_title']; ?></b></a><br />
	Avec <?php echo $data['nb_awards'] > 1 ? $data['nb_awards'].' trophées' : $data['nb_awards'].' trophée'; ?>
</div>