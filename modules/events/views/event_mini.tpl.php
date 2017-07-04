<div class="media">
	<?php echo $type['type'] == 1 ? icon('fa-crosshairs') : icon('fa-calendar-o') ?>
	<div class="media-body">
		<?php echo $title ?>
		<?php echo $this->label($type['title'], $type['icon'], $type['color'], 'events/type/'.$type['type_id'].'/'.url_title($type['title'])).' '.icon('fa-clock-o').timetostr('%H:%M', $date) ?>
		<?php if ($description): ?>
		<div style="margin-top: 6px;">
			<?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($description))), 90) ?>
		</div>
		<?php endif ?>
	</div>
</div>
