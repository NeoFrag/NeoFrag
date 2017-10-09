<div class="row justify-content-md-center languages">
	<?php foreach ($this->config->langs as $language): ?>
	<div class="col col-sm-6 col-md-4 col-lg-3">
		<img class="img-fluid" src="<?php echo image('flags/'.$language->info()->name.'.png') ?>" alt="">
		<a class="btn btn-primary" href="#" data-language="<?php echo $language->info()->name ?>"><?php echo $language->info()->title ?></a>
	</div>
	<?php endforeach ?>
</div>
