<?php if ($data['status'] == 1): ?>
	<div class="alert alert-info m-0">Candidature <b>en cours d'éxamination</b> par les recruteurs.</div>
<?php elseif ($data['status'] == 2): ?>
	<div class="alert alert-success m-0">Candidature <b>acceptée</b> !</div>
<?php else: ?>
	<div class="alert alert-danger m-0">Candidature <b>refusée</b> !</div>
<?php endif ?>
<?php if ($data['reply_text']): ?>
	<h3>Réponse des recruteurs</h3>
	<?php echo $data['reply_text'] ?>
<?php endif ?>
