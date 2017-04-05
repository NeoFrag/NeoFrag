<div class="row">
	<?php if ($data['image_id']): ?>
	<div class="col-md-4">
		<a class="thumbnail" href="<?php echo url('recruits/'.$data['recruit_id'].'/'.url_title($data['title'])); ?>"><img class="img-responsive" src="<?php echo path($data['image_id']); ?>" alt="" /></a>
	</div>
	<?php endif; ?>
	<div class="col-md-<?php echo $data['image_id'] ? '8' : '12'; ?>">
		<p><i><?php echo icon('fa-clock-o'); ?> Offre publiée le <?php echo timetostr('%e %b %Y', $data['date']); ?></i></p>
		<ul class="list-inline">
			<?php if ($data['team_id']): ?>
			<li><b>Equipe:</b> <?php echo $data['team_name']; ?></li>
			<?php endif; ?>
			<li><b>Rôle:</b> <?php echo $data['role']; ?></li>
			<li><b><?php echo $data['size'] > 1 ? 'Places disponibles:' : 'Place disponible:'; ?></b> <?php echo $data['size']; ?></li>
			<?php if ($data['date_end']): ?>
			<li><b>Expiration:</b> <?php echo timetostr('%e %b %Y', $data['date_end']); ?></li>
			<?php endif; ?>
		</ul>
		<p><?php echo $data['introduction']; ?></p>
	</div>
</div>