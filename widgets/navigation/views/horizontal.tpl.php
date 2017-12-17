<ul class="nav">
<?php foreach ($links as $link): ?>
	<li class="nav-item">
		<a class="nav-link<?php if ((($url = ltrim(preg_replace('_^'.preg_quote($this->url(), '_').'_', '', $this->url($link['url'])), '/')) == $this->url->request) || ($url && strpos($this->url->request, $url) === 0)) echo ' active' ?>" href="<?php echo url($link['url']) ?>" target="<?php echo !empty($link['target']) ? $link['target'] : '_parent' ?>"><?php echo $link['title'] ?></a>
	</li>
<?php endforeach ?>
</ul>
