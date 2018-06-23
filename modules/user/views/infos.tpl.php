<div class="row">
	<div class="col-4">
		<b><?php echo $this->lang('Inscrit depuis le') ?></b><br />
		<?php echo $user->registration_date ?>
	</div>
	<div class="col-4">
		<b><?php echo $this->lang('Dernière activité') ?></b><br />
		<?php echo $user->last_activity_date ?>
	</div>
	<div class="col-4">
		<b><?php echo $this->lang('Groupes') ?></b><br />
		<?php echo $user->groups() ?>
	</div>
</div>
