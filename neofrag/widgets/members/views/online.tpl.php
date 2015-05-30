<div class="col-xs-4 text-center">
	<h2 class="no-margin">{nb_visitors}</h2>
	<?php echo $data['nb_visitors'] > 1 ? 'Visiteurs' : 'Visiteur'; ?>
</div>
<div class="col-xs-4 text-center">
	<?php if ($data['nb_members']): ?><a href="#" data-toggle="modal" data-target="#modal-online-members"><?php endif; ?>
		<h2 class="no-margin">{nb_members}</h2>
		<?php echo $data['nb_members'] > 1 ? 'Membres' : 'Membre'; ?>
	<?php if ($data['nb_members']): ?></a><?php endif; ?>
</div>
<div class="col-xs-4 text-center">
	<?php if ($data['nb_administrators']): ?><a href="#" data-toggle="modal" data-target="#modal-online-administrators"><?php endif; ?>
		<h2 class="no-margin">{nb_administrators}</h2>
		<?php echo $data['nb_administrators'] > 1 ? 'Admins' : 'Admin'; ?>
	<?php if ($data['nb_administrators']): ?></a><?php endif; ?>
</div>
