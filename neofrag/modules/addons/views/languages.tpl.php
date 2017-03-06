<ul class="list-group">
	<?php foreach ($data['languages'] as $code => $language): ?>
	<li class="list-group-item">
		<div class="pull-left"><?php echo $this->button_sort($code, 'admin/ajax/addons/language/sort', '.list-group', 'li'); ?></div>
		<img class="img-flag" src="<?php echo image('flags/'.$language['flag']); ?>" alt="" /> <?php echo $language['name']; ?>
	</li>
	<?php endforeach; ?>
</ul>