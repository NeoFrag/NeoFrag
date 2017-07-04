<div class="media">
	<div class="media-left">
		<?php echo $type['type'] == 1 ? icon('fa-crosshairs') : icon('fa-calendar-o') ?>
	</div>
	<div class="media-body">
		<h4 class="media-heading no-margin"><?php echo $title ?></h4>
		<?php echo $this->label($type['title'], $type['icon'], $type['color'], 'events/type/'.$type['type_id'].'/'.url_title($type['title'])).' '.icon('fa-clock-o').timetostr('%H:%M', $date) ?>
		<?php if ($description): ?>
		<div style="margin-top: 6px;">
			<?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($description))), 90) ?>
		</div>
		<?php endif ?>
	</div>
</div>
