<div class="column-partners">
	<?php
	$count = count($data['partners']);
	foreach ($data['partners'] as $i => $partner): ?>
	<div class="row">
		<div class="col-md-12 text-center">
			<a href="<?php echo url('partners/'.$partner['partner_id'].'/'.$partner['name']); ?>" target="_blank">
				<?php if ($image_id = $partner['logo_'.$data['display_style']]): ?>
				<img class="logo" src="<?php echo path($image_id); ?>" alt="" />
				<?php else: ?>
				<h4 class="no-margin"><?php echo $partner['title']; ?></h4>
				<?php endif; ?>
			</a>
		</div>
	</div>
	<?php
		if ($i < $count - 1) echo '<hr />';
	endforeach;
	?>
</div>