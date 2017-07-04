<div class="row">
	<?php if ($image_id): ?>
	<div class="col-md-4">
		<a class="thumbnail" href="<?php echo url('recruits/'.$recruit_id.'/'.url_title($title)) ?>"><img class="img-responsive" src="<?php echo path($image_id) ?>" alt="" /></a>
	</div>
	<?php endif ?>
	<div class="col-md-<?php echo $image_id ? '8' : '12' ?>">
		<p><b><?php echo icon('fa-clock-o') ?> Offre publiée le <?php echo timetostr('%e %b %Y', $date) ?></b></p>
		<?php echo $introduction ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if ($description): ?>
			<h3>Description</h3>
			<?php echo $description ?>
		<?php endif ?>
		<?php if ($requierments): ?>
			<h3>Profil recherché</h3>
			<?php echo $requierments ?>
		<?php endif ?>
	</div>
</div>
