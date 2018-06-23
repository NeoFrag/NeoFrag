<ul class="nav <?php echo !empty($align) ? $align : 'justify-content-end' ?>">
	<?php if ($this->user()): ?>
		<li class="nav-item"><span class="nav-link"><?php echo $this->lang('Bienvenue <a href="'.url('user').'">'.$this->user->username.'</a>') ?></span></li>
		<li class="nav-item" data-toggle="tooltip" title="Gérer mon compte"><a class="nav-link" href="<?php echo url('user/account') ?>"><?php echo icon('fa-cogs') ?></a></li>
		<li class="nav-item" data-toggle="tooltip" title="Messagerie">
			<?php /*if ($messages = $this->user->get_messages()): ?><span class="badge badge-danger pull-right"><?php echo $messages ?></span><?php endif */ ?>
			<a class="nav-link" href="<?php echo url('user/messages') ?>"><?php echo icon('fa-envelope-o') ?></a>
		</li>
		<?php if ($this->access->admin()): ?>
			<li class="nav-item" data-toggle="tooltip" title="Administration"><a class="nav-link" href="<?php echo url('admin') ?>"><?php echo icon('fa-dashboard') ?></a></li>
		<?php endif ?>
		<li data-toggle="tooltip" title="Déconnexion"><a class="nav-link" href="<?php echo url('user/logout') ?>"><?php echo icon('fa-close') ?></a></li>
	<?php else: ?>
		<li class="nav-item"><a class="nav-link" href="#" data-modal-ajax="<?php echo url('ajax/user/register') ?>"><?php echo $this->lang('Créer un compte') ?></a></li>
		<li class="nav-item"><a class="nav-link" href="#" data-modal-ajax="<?php echo url('ajax/user/auth') ?>"><?php echo icon('fa-sign-in').' '.$this->lang('Connexion') ?></a></li>
	<?php endif ?>
</ul>
