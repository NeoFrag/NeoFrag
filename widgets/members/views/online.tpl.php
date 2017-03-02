<div class="col-xs-4 text-center">
	<h2 class="no-margin"><?php echo $data['nb_visitors']; ?></h2>
	<?php echo $this->lang('guest', $data['nb_visitors']); ?>
</div>
<div class="col-xs-4 text-center">
	<?php if ($data['nb_members']): ?><a href="#" data-toggle="modal" data-target="#modal-online-members"><?php endif; ?>
		<h2 class="no-margin"><?php echo $data['nb_members']; ?></h2>
		<?php echo $this->lang('member', $data['nb_members']); ?>
	<?php if ($data['nb_members']): ?></a><?php endif; ?>
</div>
<div class="col-xs-4 text-center">
	<?php if ($data['nb_admins']): ?><a href="#" data-toggle="modal" data-target="#modal-online-administrators"><?php endif; ?>
		<h2 class="no-margin"><?php echo $data['nb_admins']; ?></h2>
		<?php echo $this->lang('admin', $data['nb_admins']); ?>
	<?php if ($data['nb_admins']): ?></a><?php endif; ?>
</div>
