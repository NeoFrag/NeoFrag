<ul class="nav <?php echo !empty($align) ? $align : 'justify-content-end' ?>">
	<?php if ($this->user()): ?>
		<li class="nav-item"><span class="nav-link"><?php echo $this->lang('Bienvenue <a href="'.url('user').'">'.$this->user->username.'</a>') ?></span></li>
		<li class="nav-item" data-toggle="tooltip" title="Éditer mon profil"><a class="nav-link" href="<?php echo url('user/profile') ?>"><?php echo icon('fas fa-cog') ?></a></li>
		<li class="nav-item" data-toggle="tooltip" title="Messagerie">
			<a class="nav-link" href="<?php echo url('user/messages') ?>">
				<?php echo icon('far fa-envelope') ?>
				<?php if ($messages = $this->module('user')->model('messages')->get_messages_unreads()): ?><span class="badge badge-danger"><?php echo $messages ?></span><?php endif  ?>
			</a>
		</li>
		<?php if ($this->access->admin()): ?>
			<li class="nav-item" data-toggle="tooltip" title="Administration"><a class="nav-link" href="<?php echo url('admin') ?>"><?php echo icon('fas fa-tachometer-alt') ?></a></li>
		<?php endif ?>
		<li data-toggle="tooltip" title="Déconnexion"><a class="nav-link" href="<?php echo url('user/logout') ?>"><?php echo icon('fas fa-times') ?></a></li>
	<?php else: ?>
		<?php if ($this->config->nf_registration_status): ?>
		<li class="nav-item"><a class="nav-link" href="#" data-modal-ajax="<?php echo url('ajax/user/register') ?>"><?php echo $this->lang('Créer un compte') ?></a></li>
		<?php endif ?>
		<li class="nav-item"><a class="nav-link" href="#" data-modal-ajax="<?php echo url('ajax/user/auth') ?>"><?php echo icon('fas fa-sign-in-alt').' '.$this->lang('Connexion') ?></a></li>
	<?php endif ?>
</ul>
