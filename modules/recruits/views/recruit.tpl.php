<div class="row">
	<?php if ($image_id): ?>
	<div class="col-4">
		<a class="thumbnail" href="<?php echo url('recruits/'.$recruit_id.'/'.url_title($title)) ?>"><img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="" /></a>
	</div>
	<?php endif ?>
	<div class="col-<?php echo $image_id ? '8' : '12' ?>">
		<p><b><?php echo icon('far fa-clock') ?> Offre publiée le <?php echo timetostr('%e %b %Y', $date) ?></b></p>
		<?php echo $introduction ?>
	</div>
</div>
<div class="row">
	<div class="col-12">
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
