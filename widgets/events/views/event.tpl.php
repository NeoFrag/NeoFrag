<a href="<?php echo $link = url('events/'.$data['event_id'].'/'.url_title($data['title'])); ?>"><img src="<?php echo path($data['image_id']); ?>" class="img-responsive" alt="" /></a>
<div class="panel-body">
	<?php if (!empty($data['match']['opponent']))://Matches ?>
	<div class="text-center"<?php echo !$data['description'] ?: ' style="margin-bottom: 10px;"'; ?>>
		<div class="row vcenter">
			<div class="text-right col-xs-5 vcenter">
				<h5 class="no-margin">
					<a href="<?php echo url('events/team/'.$data['match']['team_id'].'/'.$data['match']['team']['name']); ?>">
					<?php if ($icon = path($data['match']['team']['icon_id'])) echo '<img src="'.path($icon).'" style="margin-right: 10px;" alt="" />'; ?>
					<?php echo $data['match']['team']['title'].' '.$this->model('matches')->display_scores($data['match']['scores'], $color); ?>
					</a>
				</h5>
			</div>
			<?php if ($data['match']['scores']): ?>
				<div class="text-center col-xs-2 vcenter">
					<b class="<?php echo $color; ?> no-margin"><?php echo $data['match']['scores'][0]; ?>:<?php echo $data['match']['scores'][1]; ?></b>
				</div>
			<?php else: ?>
				<div class="text-center col-xs-2 vcenter">
					<b>VS</b>
				</div>
			<?php endif; ?>
			<?php if ($data['match']['opponent']['image_id']): ?>
			<div class="text-right col-xs-1 vcenter">
				<img src="<?php echo path($data['match']['opponent']['image_id']); ?>" class="img-responsive" alt="" />
			</div>
			<?php endif; ?>
			<div class="text-left col-xs-<?php echo $data['match']['opponent']['image_id'] ? 4 : 5; ?> vcenter">
				<h5 class="no-margin">
					<?php echo '<a href="'.url('events/'.$data['event_id'].'/'.url_title($data['title'])).'">'.$this->model('matches')->display_scores($data['match']['scores'], $color, TRUE).' '.$data['match']['opponent']['title'].'</a>'; ?>
				</h5>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php if ($data['description']): ?>
	<div class="text-left">
		<?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($data['description']))), 150); ?>
	</div>
	<?php endif; ?>
</div>
<div class="panel-footer">
	<div class="pull-right">
		<ul class="list-inline no-margin">
			<li><a href="<?php echo $link.'#participants'; ?>"><?php echo icon('fa-users').' '.$data['participants']; ?></a></li>
			<li><a href="<?php echo $link.'#comments'; ?>"><?php echo icon('fa-comments-o').' '.$this->comments->count_comments('events', $data['event_id']); ?></a></li>
		</ul>
	</div>
	<ul class="list-inline no-margin">
		<li>
			<?php echo $this->label($data['type']['title'], $data['type']['icon'], $data['type']['color'], 'events/type/'.$data['type']['type_id'].'/'.url_title($data['type']['title'])); ?>
			<?php echo icon('fa-clock-o').' '.timetostr('%d/%m/%Y', $data['date']); ?>
		</li>
		<?php
		if (!empty($data['show_details']) && $data['list_participants'] && $data['location']):
			foreach ($data['list_participants'] as $participant):
				if ($this->user('admin') || ($participant['user_id'] == $this->user('user_id'))): ?>
					<div style="padding-top: 5px;"><?php if (($location = explode("\n", $data['location']))) echo '<li>'.$this->label(current($location), 'fa-map-marker')->popover_if(count($location) > 1, bbcode($data['location'])).'</li>'; ?></div>
					<?php
					break;
				endif;
			endforeach;
		endif;
		?>
	</ul>
</div>