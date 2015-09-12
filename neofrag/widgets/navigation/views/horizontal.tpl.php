<div class="list-group">
<?php foreach ($data['links'] as $link): ?>
	<a class="list-group-item<?php if (strpos($NeoFrag->config->request_url, substr($link['url'], 0, -5)) === 0) echo ' active'; ?>" href="<?php echo (strpos($link['url'], 'http://') !== 0 ? url() : '').$link['url']; ?>"><?php echo $link['title']; ?></a>
<?php endforeach; ?>
</div>