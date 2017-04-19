<div class="column-partners">
	<?php
	$count = count($partners);
	foreach ($partners as $i => $partner): ?>
	<div class="row">
		<div class="col-12 text-center">
			<a href="<?php echo url('partners/'.$partner['partner_id'].'/'.$partner['name']) ?>" target="_blank">
				<?php if ($image_id = $partner['logo_'.$display_style]): ?>
				<img class="logo" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="" />
				<?php else: ?>
				<h4 class="m-0"><?php echo $partner['title'] ?></h4>
				<?php endif ?>
			</a>
		</div>
	</div>
	<?php
		if ($i < $count - 1) echo '<hr />';
	endforeach;
	?>
</div>
