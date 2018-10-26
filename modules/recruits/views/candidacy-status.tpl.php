<?php if ($status == 1): ?>
	<div class="alert alert-info">Candidature <b>en cours d'éxamination</b> par les recruteurs.</div>
<?php elseif ($status == 2): ?>
	<div class="alert alert-success">Candidature <b>acceptée</b> !</div>
<?php else: ?>
	<div class="alert alert-danger">Candidature <b>refusée</b> !</div>
<?php endif ?>
<?php if ($reply_text): ?>
	<h3>Réponse des recruteurs</h3>
	<?php echo $reply_text ?>
<?php endif ?>
