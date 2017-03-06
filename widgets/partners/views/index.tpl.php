<style type="text/css">
#partners-carousel-<?php echo $data['id']; ?> .item > .row {
	padding: <?php echo ($data['total_partners'] > $data['display_number']) ? '15px 40px' : '0 15px'; ?>;
}

#partners-carousel-<?php echo $data['id']; ?> .partner-item {
	font-size: 16px;
	font-weight: bold;
	height: calc(<?php echo $data['display_height']; ?>px - 30px);
	line-height: calc(<?php echo $data['display_height']; ?>px - 30px);
	text-align: center;
}

#partners-carousel-<?php echo $data['id']; ?> .partner-item img.logo {
	height: auto;
	opacity: 0;
	vertical-align: middle;
}

#partners-carousel-<?php echo $data['id']; ?> .carousel-control {
	width: 40px;
}
</style>
<div id="partners-carousel-<?php echo $data['id']; ?>" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner" role="listbox">
		<?php
		$i = 0;
		for ($slider = 1; $slider <= $data['total_slides']; $slider++): ?>
		<div class="item<?php if ($slider == 1) echo ' active'; ?>">
			<div class="row">
				<?php
				$max = $slider == $data['total_slides'] ? $data['total_partners'] - (($slider - 1) * $data['display_number']) : $data['display_number'];
				for ($partner = 1; $partner <= $max; $partner++)
				{
				?>
				<div class="col-md-<?php echo ceil(12 / $data['display_number']); ?> partner-item">
					<a href="<?php echo url('partners/'.$data['partners'][$i]['partner_id'].'/'.$data['partners'][$i]['name']); ?>" target="_blank">
						<?php if ($image_id = $data['partners'][$i]['logo_'.$data['display_style']]): ?>
						<img class="logo" src="<?php echo path($image_id); ?>" alt="" />
						<?php else: ?>
						<?php echo $data['partners'][$i]['title']; ?>
						<?php endif; ?>
					</a>
				</div>
				<?php
					$i++;
				}
				?>
			</div>
		</div>
		<?php endfor; ?>
	</div>
	<?php if ($data['total_slides'] > 1): ?>
	<a class="left carousel-control" href="#partners-carousel-<?php echo $data['id']; ?>" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	</a>
	<a class="right carousel-control" href="#partners-carousel-<?php echo $data['id']; ?>" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	</a>
	<?php endif; ?>
</div>