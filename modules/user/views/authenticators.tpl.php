<div class="row justify-content-md-center authenticators">
	<?php foreach ($authenticators as $authenticator): ?>
	<div class="col col-sm-4 col-md-3 col-lg-2">
		<img class="img-fluid" src="<?php echo image('authenticators/'.$authenticator->info()->name.'.png') ?>" alt="">
		<a class="btn btn-primary" href="<?php echo url('user/auth/'.url_title($authenticator->info()->name)) ?>"><?php echo $authenticator->info()->title ?></a>
	</div>
	<?php endforeach ?>
</div>
