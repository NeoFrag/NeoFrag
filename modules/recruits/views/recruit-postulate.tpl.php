<?php if ($data['status'] == 1): ?>
	<div class="alert alert-info no-margin">Candidature <b>en cours d'éxamination</b> par les recruteurs.</div>
<?php elseif ($data['status'] == 2): ?>
	<div class="alert alert-success no-margin">Candidature <b>acceptée</b>. Félicitation !</div>
<?php else: ?>
	<div class="alert alert-danger no-margin">Candidature <b>refusée...</b></div>
<?php endif; ?>