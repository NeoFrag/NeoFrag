<div class="text-center">
	<b>Jeu le plus récompensé</b>
	<h1><?php echo icon('fas fa-gamepad') ?></h1>
	<a href="<?php echo url('awards/game/'.$game_id.'/'.$name) ?>"><b><?php echo $game_title ?></b></a><br />
	Avec <?php echo $nb_awards > 1 ? $nb_awards.' trophées' : $nb_awards.' trophée' ?>
</div>
