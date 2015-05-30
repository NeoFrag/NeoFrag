<ul class="nav nav-tabs">
	<li<?php echo ($this->config->request_url == 'user/messages.html') ? ' class="active"' : ''; ?>><a href="{base_url}user/messages.html">Messages reçus</a></li>
	<li<?php echo ($this->config->request_url == 'user/messages/sent.html') ? ' class="active"' : ''; ?>><a href="{base_url}user/messages/sent.html">Messages envoyés</a></li>
	<li<?php echo ($this->config->request_url == 'user/messages/compose.html') ? ' class="active"' : ''; ?>><a href="{base_url}user/messages/compose.html">Rédiger un message</a></li>
</ul>