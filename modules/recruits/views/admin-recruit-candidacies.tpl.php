<div>
	<ul class="nav nav-tabs" role="tablist" style="margin-bottom: 20px;">
		<li class="active"><a href="#pending" aria-controls="pending" role="tab" data-toggle="tab"><?php echo icon('fa-clock-o'); ?>En attente</a></li>
		<li><a href="#accepted" aria-controls="accepted" role="tab" data-toggle="tab"><?php echo icon('fa-check'); ?>Acceptée</a></li>
		<li><a href="#declined" aria-controls="declined" role="tab" data-toggle="tab"><?php echo icon('fa-ban'); ?>Refusée</a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="pending">
			<?php echo $data['table_pending']; ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="accepted">
			<?php echo $data['table_accepted']; ?>
		</div>
		<div role="tabpanel" class="tab-pane" id="declined">
			<?php echo $data['table_declined']; ?>
		</div>
	</div>
</div>