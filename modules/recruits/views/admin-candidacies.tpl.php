<div class="panel-body text-center">
<?php if ($data['total_pending']): ?>
	<h1 class="no-margin"><?php echo icon('fa-clock-o'); ?></h1>
	<a href="<?php echo url('admin/recruits/pending'); ?>"><b><?php echo $data['total_pending'].($data['total_pending'] > 1 ? ' candidatures' : ' candidature'); ?></b></a> en <?php echo $data['total_pending'] > 1 ? 'attentes' : 'attente'; ?>
<?php else: ?>
	Aucune candidature en attente...
<?php endif; ?>
</div>
<ul class="list-group">
	<li class="list-group-item"><?php echo icon('fa-briefcase').' '.$data['total_candidacies'].($data['total_candidacies'] > 1 ? ' candidatures déposées' : ' candidature déposée'); ?></li>
	<li class="list-group-item"><?php echo icon('fa-check text-success').' '.$data['total_accepted'].($data['total_accepted'] > 1 ? ' candidatures acceptées' : ' candidature acceptée'); ?></li>
	<li class="list-group-item"><?php echo icon('fa-ban text-danger').' '.$data['total_declined'].($data['total_declined'] > 1 ? ' candidatures refusées' : ' candidature refusée'); ?></li>
</ul>