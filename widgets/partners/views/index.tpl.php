<style type="text/css">
#partners-carousel-<?php echo $id ?> .item > .row {
	padding: <?php echo ($total_partners > $display_number) ? '15px 40px' : '0 15px' ?>;
}

#partners-carousel-<?php echo $id ?> .partner-item {
	font-size: 16px;
	font-weight: bold;
	height: calc(<?php echo $display_height ?>px - 30px);
	line-height: calc(<?php echo $display_height ?>px - 30px);
	text-align: center;
}

#partners-carousel-<?php echo $id ?> .partner-item img.logo {
	height: auto;
	opacity: 0;
	vertical-align: middle;
}

#partners-carousel-<?php echo $id ?> .carousel-control {
	width: 40px;
}
</style>
<div id="partners-carousel-<?php echo $id ?>" class="carousel slide" data-ride="carousel">
	<div class="carousel-inner" role="listbox">
		<?php
		$i = 0;
		for ($slider = 1; $slider <= $total_slides; $slider++): ?>
		<div class="item<?php if ($slider == 1) echo ' active' ?>">
			<div class="row">
				<?php
				$max = $slider == $total_slides ? $total_partners - (($slider - 1) * $display_number) : $display_number;
				for ($partner = 1; $partner <= $max; $partner++)
				{
				?>
				<div class="col-md-<?php echo ceil(12 / $display_number) ?> partner-item">
					<a href="<?php echo url('partners/'.$partners[$i]['partner_id'].'/'.$partners[$i]['name']) ?>" target="_blank">
						<?php if ($image_id = $partners[$i]['logo_'.$display_style]): ?>
						<img class="logo" src="<?php echo path($image_id) ?>" alt="" />
						<?php else: ?>
						<?php echo $partners[$i]['title'] ?>
						<?php endif ?>
					</a>
				</div>
				<?php
					$i++;
				}
				?>
			</div>
		</div>
		<?php endfor ?>
	</div>
	<?php if ($total_slides > 1): ?>
	<a class="left carousel-control" href="#partners-carousel-<?php echo $id ?>" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	</a>
	<a class="right carousel-control" href="#partners-carousel-<?php echo $id ?>" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	</a>
	<?php endif ?>
</div>
