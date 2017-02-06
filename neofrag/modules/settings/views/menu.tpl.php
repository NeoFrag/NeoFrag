<div class="list-group">
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings.html' ? ' active' : ''; ?>" href="<?php echo url('admin/settings.html'); ?>"><?php echo icon('fa-cog'); ?> Préférences générales</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/registration.html' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/registration.html'); ?>"><?php echo icon('fa-sign-in fa-rotate-90'); ?> Gestions des inscriptions</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/team.html' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/team.html'); ?>"><?php echo icon('fa-users'); ?> Notre structure</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/socials.html' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/socials.html'); ?>"><?php echo icon('fa-globe'); ?> Réseaux sociaux</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/captcha.html' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/captcha.html'); ?>"><?php echo icon('fa-shield'); ?> Sécurité anti-bots</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/email.html' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/email.html'); ?>"><?php echo icon('fa-envelope-o'); ?> Serveur e-mail</a>
</div>