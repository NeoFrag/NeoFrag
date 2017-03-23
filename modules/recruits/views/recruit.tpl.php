<div class="row">
	<?php if ($data['image_id']): ?>
	<div class="col-md-4">
		<a class="thumbnail" href="<?php echo url('recruits/'.$data['recruit_id'].'/'.url_title($data['title'])); ?>"><img class="img-responsive" src="<?php echo path($data['image_id']); ?>" alt="" /></a>
	</div>
	<?php endif; ?>
	<div class="col-md-<?php echo $data['image_id'] ? '8' : '12'; ?>">
		<p><b><?php echo icon('fa-clock-o'); ?> Offre publiée le <?php echo timetostr('%e %b %Y', $data['date']); ?></b></p>
		<?php echo $data['introduction']; ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if ($data['description']): ?>
			<h3>Description</h3>
			<?php echo $data['description']; ?>
		<?php endif; ?>
		<?php if ($data['requierments']): ?>
			<h3>Profil recherché</h3>
			<?php echo $data['requierments']; ?>
		<?php endif; ?>
	</div>
</div>