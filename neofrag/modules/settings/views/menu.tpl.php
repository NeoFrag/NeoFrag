<div class="list-group">
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings' ? ' active' : ''; ?>" href="<?php echo url('admin/settings'); ?>"><?php echo icon('fa-cog'); ?> Préférences générales</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/registration' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/registration'); ?>"><?php echo icon('fa-sign-in fa-rotate-90'); ?> Gestions des inscriptions</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/team' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/team'); ?>"><?php echo icon('fa-users'); ?> Notre structure</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/socials' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/socials'); ?>"><?php echo icon('fa-globe'); ?> Réseaux sociaux</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/captcha' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/captcha'); ?>"><?php echo icon('fa-shield'); ?> Sécurité anti-bots</a>
	<a class="list-group-item<?php echo $this->url->request == 'admin/settings/email' ? ' active' : ''; ?>" href="<?php echo url('admin/settings/email'); ?>"><?php echo icon('fa-envelope-o'); ?> Serveur e-mail</a>
</div>