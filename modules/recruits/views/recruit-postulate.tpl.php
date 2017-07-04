<?php if ($status == 1): ?>
	<div class="alert alert-info m-0">Candidature <b>en cours d'éxamination</b> par les recruteurs.</div>
<?php elseif ($status == 2): ?>
	<div class="alert alert-success m-0">Candidature <b>acceptée</b>. Félicitation !</div>
<?php else: ?>
	<div class="alert alert-danger m-0">Candidature <b>refusée...</b></div>
<?php endif ?>
