<ul class="nav nav-tabs">
	<li<?php echo ($this->config->request_url == 'user/messages.html') ? ' class="active"' : ''; ?>><a href="<?php echo url('user/messages.html'); ?>"><?php echo i18n('pm_inbox'); ?></a></li>
	<li<?php echo ($this->config->request_url == 'user/messages/sent.html') ? ' class="active"' : ''; ?>><a href="<?php echo url('user/messages/sent.html'); ?>"><?php echo i18n('pm_sent'); ?></a></li>
	<li<?php echo ($this->config->request_url == 'user/messages/compose.html') ? ' class="active"' : ''; ?>><a href="<?php echo url('user/messages/compose.html'); ?>"><?php echo i18n('pm_compose'); ?></a></li>
</ul>