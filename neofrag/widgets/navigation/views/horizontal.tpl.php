<div class="list-group">
<?php foreach ($data['links'] as $link): ?>
	<a class="list-group-item<?php if (strpos($this->url->request, $link['url'] ?: 'index') === 0) echo ' active'; ?>" href="<?php echo (!preg_match('#^(https?:)?//#i', $link['url']) ? url() : '').$link['url']; ?>" target="<?php echo !empty($link['target']) ? $link['target'] : '_parent'; ?>"><?php echo $link['title']; ?></a>
<?php endforeach; ?>
</div>