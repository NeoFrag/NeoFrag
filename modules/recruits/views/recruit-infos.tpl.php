<ul class="list-unstyled">
	<?php if ($team_id): ?>
	<li class="list-item"><b>Equipe:</b> <?php echo $team_name ?></li>
	<?php endif ?>
	<li class="list-item"><b>Rôle:</b> <?php echo $role ?></li>
	<li class="list-item"><b><?php echo $size > 1 ? 'Places disponibles:' : 'Place disponible:' ?></b> <?php echo $size ?></li>
	<?php if ($date_end): ?>
	<li class="list-item"><b>Expiration:</b> <?php echo timetostr('%e %b %Y', $date_end) ?></li>
	<?php endif ?>
</ul>
