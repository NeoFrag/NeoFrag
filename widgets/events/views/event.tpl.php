<a href="<?php echo $link = url('events/'.$event_id.'/'.url_title($title)) ?>"><img src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" class="img-fluid" alt="" /></a>
<div class="panel-body">
	<?php if (!empty($match['opponent']))://Matches ?>
	<div class="text-center"<?php echo !$description ?: ' style="margin-bottom: 10px;"' ?>>
		<div class="row vcenter">
			<div class="text-right col-5 vcenter">
				<h5 class="m-0">
					<a href="<?php echo url('events/team/'.$match['team_id'].'/'.$match['team']['name']) ?>">
					<?php if ($icon = NeoFrag()->model2('file', $match['team']['icon_id'])->path()) echo '<img src="'.NeoFrag()->model2('file', $icon)->path().'" style="margin-right: 10px;" alt="" />' ?>
					<?php echo $match['team']['title'].' '.$this->module('events')->model('matches')->display_scores($match['scores'], $color) ?>
					</a>
				</h5>
			</div>
			<?php if ($match['scores']): ?>
				<div class="text-center col-2 vcenter">
					<b class="<?php echo $color ?> m-0"><?php echo $match['scores'][0] ?>:<?php echo $match['scores'][1] ?></b>
				</div>
			<?php else: ?>
				<div class="text-center col-2 vcenter">
					<b>VS</b>
				</div>
			<?php endif ?>
			<?php if ($match['opponent']['image_id']): ?>
			<div class="text-right col-1 vcenter">
				<img src="<?php echo NeoFrag()->model2('file', $match['opponent']['image_id'])->path() ?>" class="img-fluid" alt="" />
			</div>
			<?php endif ?>
			<div class="text-left col-xs-<?php echo $match['opponent']['image_id'] ? 4 : 5 ?> vcenter">
				<h5 class="m-0">
					<?php echo '<a href="'.url('events/'.$event_id.'/'.url_title($title)).'">'.$this->module('events')->model('matches')->display_scores($match['scores'], $color, TRUE).' '.$match['opponent']['title'].'</a>' ?>
				</h5>
			</div>
		</div>
	</div>
	<?php endif ?>
	<?php if ($description): ?>
	<div class="text-left">
		<?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($description))), 150) ?>
	</div>
	<?php endif ?>
</div>
<div class="panel-footer">
	<div class="pull-right">
		<ul class="list-inline m-0">
			<li><a href="<?php echo $link.'#participants' ?>"><?php echo icon('fa-users').' '.$participants ?></a></li>
			<?php if (($comments = $this->module('comments')) && $comments->is_enabled()): ?>
				<li><?php echo $comments->link('events', $event_id, 'events/'.$event_id.'/'.url_title($title)) ?></li>
			<?php endif ?>
		</ul>
	</div>
	<ul class="list-inline m-0">
		<li>
			<?php echo $this->label($type['title'], $type['icon'], $type['color'], 'events/type/'.$type['type_id'].'/'.url_title($type['title'])) ?>
			<?php echo icon('fa-clock-o').' '.timetostr('%d/%m/%Y', $date) ?>
		</li>
		<?php
		if (!empty($show_details) && $list_participants && $location):
			foreach ($list_participants as $participant):
				if ($this->user->admin || ($participant['user_id'] == $this->user->id)): ?>
					<div style="padding-top: 5px;"><?php if (($location = explode("\n", $location))) echo '<li>'.$this->label(current($location), 'fa-map-marker')->popover_if(count($location) > 1, bbcode($location)).'</li>' ?></div>
					<?php
					break;
				endif;
			endforeach;
		endif;
		?>
	</ul>
</div>
