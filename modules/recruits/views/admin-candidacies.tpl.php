<div class="panel-body text-center">
<?php if ($total_pending): ?>
	<h1 class="no-margin"><?php echo icon('fa-clock-o') ?></h1>
	<a href="<?php echo url('admin/recruits/pending') ?>"><b><?php echo $total_pending.($total_pending > 1 ? ' candidatures' : ' candidature') ?></b></a> en <?php echo $total_pending > 1 ? 'attentes' : 'attente' ?>
<?php else: ?>
	Aucune candidature en attente...
<?php endif ?>
</div>
<ul class="list-group">
	<li class="list-group-item"><?php echo icon('fa-briefcase').' '.$total_candidacies.($total_candidacies > 1 ? ' candidatures déposées' : ' candidature déposée') ?></li>
	<li class="list-group-item"><?php echo icon('fa-check text-success').' '.$total_accepted.($total_accepted > 1 ? ' candidatures acceptées' : ' candidature acceptée') ?></li>
	<li class="list-group-item"><?php echo icon('fa-ban text-danger').' '.$total_declined.($total_declined > 1 ? ' candidatures refusées' : ' candidature refusée') ?></li>
</ul>
