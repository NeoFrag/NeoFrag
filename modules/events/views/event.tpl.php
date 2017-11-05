<a href="<?php echo $link = url('events/'.$data['event_id'].'/'.url_title($data['title'])); ?>"><img src="<?php echo path($data['image_id']); ?>" class="img-responsive" alt="" /></a>
<?php if (!empty($data['match']['opponent']))://Matches ?>
<div class="panel-body text-center">
	<div class="row vcenter">
		<div class="text-right col-xs-5 vcenter">
			<h3 class="no-margin">
				<a href="<?php echo url('events/team/'.$data['match']['team_id'].'/'.$data['match']['team']['name']); ?>">
				<?php if ($icon = path($data['match']['team']['icon_id'])) echo '<img src="'.path($icon).'" style="margin-right: 10px;" alt="" />'; ?>
				<?php echo $data['match']['team']['title'].' '.$this->model('matches')->display_scores($data['match']['scores'], $color); ?>
				</a>
			</h3>
		</div>
		<?php if ($data['match']['scores']): ?>
			<div class="text-center col-xs-2 vcenter">
				<div class="well no-margin"><h2 class="<?php echo $color; ?> no-margin"><?php echo $data['match']['scores'][0]; ?>:<?php echo $data['match']['scores'][1]; ?></h2></div>
			</div>
		<?php else: ?>
			<div class="text-center col-xs-2 vcenter">
				<div class="well no-margin"><h2 class="no-margin">VS</h2></div>
			</div>
		<?php endif; ?>
		<?php if ($data['match']['opponent']['image_id']): ?>
		<div class="text-right col-xs-1 vcenter">
			<img src="<?php echo path($data['match']['opponent']['image_id']); ?>" class="img-responsive" alt="" />
		</div>
		<?php endif; ?>
		<div class="text-left col-xs-<?php echo $data['match']['opponent']['image_id'] ? 4 : 5; ?> vcenter">
			<h3 class="no-margin">
				<?php
					$opponent = $this->model('matches')->display_scores($data['match']['scores'], $color, TRUE).' '.$data['match']['opponent']['title'];

					if ($data['match']['opponent']['country'])
					{
						$opponent .= '<img src="'.url('neofrag/themes/default/images/flags/'.$data['match']['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$data['match']['opponent']['country']].'" style="margin-left: 10px;" alt="" />';
					}

					if ($data['match']['opponent']['website'])
					{
						$opponent = '<a href="'.$data['match']['opponent']['website'].'" target="_blank">'.$opponent.'</a>';
					}

					echo $opponent;
				?>
			</h3>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if (!empty($data['rounds'])): ?>
<div class="panel-body">
	<?php if ($data['mode']): ?>
		<p class="<?php echo count($data['rounds']) > 1 ? 'pull-right' : 'text-center'; ?>"><?php echo icon('fa-cog'); ?>Mode: <?php echo $data['mode']; ?></p>
	<?php endif; ?>
	<?php if (count($data['rounds']) > 1): ?>
		<p><?php echo icon('fa-gamepad'); ?> <b>Détail des manches</b></p>
		<?php for ($i = 0; $i < count($data['rounds']); $i++) { ?>
		<div class="well well-sm<?php echo (($i+1) == count($data['rounds'])) ? ' no-margin' : ''; ?>">
			<div class="row vcenter">
				<?php if ($data['rounds'][$i]['image_id']): ?>
				<div class="col-xs-2 vcenter">
					<img src="<?php echo path($data['rounds'][$i]['image_id']); ?>" class="img-responsive" alt="" />
				</div>
				<?php endif; ?>
				<div class="col-xs-<?php echo $data['rounds'][$i]['image_id'] ? '3' : '5'; ?> vcenter">
					<h4 class="no-margin">Manche <?php echo $i+1; ?></h4>
					<?php echo $data['rounds'][$i]['title']; ?>
				</div>
				<div class="text-right col-xs-3 vcenter">
					<?php echo $data['match']['team']['title'].' '.$this->model('matches')->display_scores([$data['rounds'][$i]['score1'], $data['rounds'][$i]['score2']], $color); ?>
				</div>
				<div class="text-center col-xs-1 vcenter">
					<big><?php echo $data['rounds'][$i]['score1']; ?>:<?php echo $data['rounds'][$i]['score2']; ?></big>
				</div>
				<div class="col-xs-3 vcenter">
					<?php echo $this->model('matches')->display_scores([$data['rounds'][$i]['score1'], $data['rounds'][$i]['score2']], $color, TRUE).' '.$data['match']['opponent']['title']; ?>
				</div>
			</div>
		</div>
		<?php } ?>
	<?php endif; ?>
</div>
<?php endif; ?>
<?php if ($data['description']): ?>
<div class="panel-body">
	<?php echo bbcode($data['description']); ?>
</div>
<?php endif; ?>
<?php
if (!empty($data['show_details']) && $data['list_participants'] && $data['private_description']):
	foreach ($data['list_participants'] as $participant):
		if ($this->user('admin') || ($participant['user_id'] == $this->user('user_id'))): ?>
			<div class="panel-body">
				<?php echo bbcode($data['private_description']); ?>
			</div>
			<?php
			break;
		endif;
	endforeach;
endif;
?>
<?php if ($data['webtv'] || $data['website']): ?>
<div class="panel-body">
	<ul class="list-inline no-margin">
		<?php echo $data['webtv'] ? '<li><a href="'.$data['webtv'].'" target="_blank">'.icon('fa-twitch').' Retransmission sur Twitch</a></li>' : ''; ?>
		<?php echo $data['website'] ? '<li><a href="'.$data['website'].'" target="_blank">'.icon('fa-newspaper-o').' On en parle ici</a></li>' : ''; ?>
	</ul>
</div>
<?php endif; ?>
<div class="panel-footer">
	<div class="pull-right">
		<ul class="list-inline no-margin">
			<li><a href="<?php echo $link.'#participants'; ?>"><?php echo icon('fa-users').' '.$data['participants']; ?></a></li>
			<li><a href="<?php echo $link.'#comments'; ?>"><?php echo icon('fa-comments-o').' '.$this->comments->count_comments('events', $data['event_id']); ?></a></li>
		</ul>
	</div>
	<ul class="list-inline no-margin">
		<li><?php echo $this->label($data['type']['title'], $data['type']['icon'], $data['type']['color'], 'events/type/'.$data['type']['type_id'].'/'.url_title($data['type']['title'])); ?></li>
		<li><?php echo icon('fa-clock-o'); ?> <?php echo '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $data['date']).'">'.timetostr(NeoFrag()->lang('date_time_short'), $data['date']).'</span>'.($data['date_end'] ? '&nbsp;&nbsp;<span data-toggle="tooltip" title="Durée"><i>'.icon('fa-hourglass-end').(ceil((strtotime($data['date_end']) - strtotime($data['date'])) / ( 60 * 60 ))).'h</i></span>' : ''); ?></li>
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
