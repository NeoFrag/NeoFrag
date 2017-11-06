<ul class="list-inline m-0">
	<li class="col-md-3">
		<b><?php echo $this->lang('Inscrit depuis le') ?></b><br />
		<?php echo time_span($data['registration_date']) ?>
	</li>
	<li class="col-md-3">
		<b><?php echo $this->lang('Dernière activité') ?></b><br />
		<?php echo time_span($data['last_activity_date']) ?>
	</li>
	<li class="col-md-6">
		<b><?php echo $this->lang('Groupes') ?></b><br />
		<?php echo $data['groups'] ?>
	</li>
</ul>
