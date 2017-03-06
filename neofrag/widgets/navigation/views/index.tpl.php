<ul class="nav navbar-nav">
<?php foreach ($data['links'] as $link): ?>
	<li<?php if (strpos($this->url->request, $link['url'] ?: 'index') === 0) echo ' class="active"'; ?>><a href="<?php echo (!preg_match('#^(https?:)?//#i', $link['url']) ? url() : '').$link['url']; ?>" target="<?php echo !empty($link['target']) ? $link['target'] : '_parent'; ?>"><?php echo $link['title']; ?></a></li>
<?php endforeach; ?>
</ul>