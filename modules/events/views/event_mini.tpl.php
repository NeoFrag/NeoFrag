<div class="media">
	<?php echo $data['type']['type'] == 1 ? icon('fa-crosshairs') : icon('fa-calendar-o') ?>
	<div class="media-body">
		<?php echo $data['title'] ?>
		<?php echo $this->label($data['type']['title'], $data['type']['icon'], $data['type']['color'], 'events/type/'.$data['type']['type_id'].'/'.url_title($data['type']['title'])).' '.icon('fa-clock-o').timetostr('%H:%M', $data['date']) ?>
		<?php if ($data['description']): ?>
		<div style="margin-top: 6px;">
			<?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($data['description']))), 90) ?>
		</div>
		<?php endif ?>
	</div>
</div>
