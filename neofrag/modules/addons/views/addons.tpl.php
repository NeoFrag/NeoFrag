<div class="list-group">
<?php foreach ($data['addons'] as $name => $addon): ?>
	<a class="list-group-item" href="#" data-addon="<?php echo $name; ?>"><?php echo icon($addon['icon']).' '.$addon['title']; ?></a>
<?php endforeach; ?>
</div>