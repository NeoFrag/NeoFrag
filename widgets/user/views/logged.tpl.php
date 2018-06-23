<div class="card-body text-center">
	<h6 class="mb-3"><?php echo $this->lang('Bienvenue <a href="'.url('user').'">'.$this->user->username.'</a>') ?></h6>
	<?php echo $this->user->avatar()->append_attr('class', 'm-auto') ?>
</div>
<ul class="list-group">
	<li class="list-group-item">
		<!--<span class="badge badge-success pull-right"><?php echo 0; //TODO nombre de nouvelles notifications ?></span>-->
		<?php echo icon('fa-user') ?> <a href="<?php echo url('user') ?>"><?php echo $this->lang('Mon espace') ?></a>
	</li>
	<li class="list-group-item">
		<?php echo icon('fa-cogs') ?> <a href="<?php echo url('user/account') ?>"><?php echo $this->lang('Gérer mon compte') ?></a>
	</li>
	<li class="list-group-item">
		<?php echo icon('fa-eye') ?> <a href="<?php echo url('user/'.$this->user->id.'/'.url_title($username)) ?>"><?php echo $this->lang('Voir mon profil') ?></a>
	</li>
	<li class="list-group-item">
		<?php /* if ($messages = $this->user->get_messages()): ?><span class="badge badge-danger pull-right"><?php echo $messages ?></span><?php endif */ ?>
		<?php echo icon('fa-envelope-o') ?> <a href="<?php echo url('user/messages') ?>">Messagerie</a>
	</li>
	<?php if ($this->access->admin()): ?>
	<li class="list-group-item">
		<?php echo icon('fa-dashboard') ?> <a href="<?php echo url('admin') ?>"><?php echo $this->lang('Administration') ?></a>
	</li>
	<?php endif ?>
</ul>
