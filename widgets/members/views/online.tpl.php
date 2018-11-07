<div class="row text-center">
	<div class="col-4">
		<h3 class="m-0"><?php echo $nb_visitors ?></h3>
		<?php echo $this->lang('Visiteur|Visiteurs', $nb_visitors) ?>
	</div>
	<div class="col-4">
		<?php if ($nb_members): ?><a href="#" data-toggle="modal" data-target="#modal-online-members"><?php endif ?>
			<h3 class="m-0"><?php echo $nb_members ?></h3>
			<?php echo $this->lang('Membre|Membres', $nb_members) ?>
		<?php if ($nb_members): ?></a><?php endif ?>
	</div>
	<div class="col-4">
		<?php if ($nb_admins): ?><a href="#" data-toggle="modal" data-target="#modal-online-administrators"><?php endif ?>
			<h3 class="m-0"><?php echo $nb_admins ?></h3>
			<?php echo $this->lang('Admin|Admins', $nb_admins) ?>
		<?php if ($nb_admins): ?></a><?php endif ?>
	</div>
</div>
