<ul class="nav navbar-nav">
<?php foreach ($data['links'] as $link): ?>
	<li<?php if (strpos($NeoFrag->config->request_url, substr($link['url'], 0, -5)) === 0) echo ' class="active"'; ?>><a href="<?php echo (!preg_match('#^(https?:)?//#i', $link['url']) ? $this->config->base_url : '').$link['url']; ?>"><?php echo $link['title']; ?></a></li>
<?php endforeach; ?>
</ul>