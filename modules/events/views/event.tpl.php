<a href="<?php echo $link = url('events/'.$event_id.'/'.url_title($title)) ?>"><img src="<?php echo $this->model2('file', $image_id)->path() ?>" class="img-responsive" alt="" /></a>
<?php if (!empty($match['opponent']))://Matches ?>
<div class="panel-body text-center">
	<div class="row vcenter">
		<div class="text-right col-xs-5 vcenter">
			<h3 class="no-margin">
				<a href="<?php echo url('events/team/'.$match['team_id'].'/'.$match['team']['name']) ?>">
				<?php if ($icon = $this->model2('file', $match['team']['icon_id'])->path()) echo '<img src="'.$this->model2('file', $icon)->path().'" style="margin-right: 10px;" alt="" />' ?>
				<?php echo $match['team']['title'].' '.$this->model('matches')->display_scores($match['scores'], $color) ?>
				</a>
			</h3>
		</div>
		<?php if ($match['scores']): ?>
			<div class="text-center col-xs-2 vcenter">
				<div class="well no-margin"><h2 class="<?php echo $color ?> no-margin"><?php echo $match['scores'][0] ?>:<?php echo $match['scores'][1] ?></h2></div>
			</div>
		<?php else: ?>
			<div class="text-center col-xs-2 vcenter">
				<div class="well no-margin"><h2 class="no-margin">VS</h2></div>
			</div>
		<?php endif ?>
		<?php if ($match['opponent']['image_id']): ?>
		<div class="text-right col-xs-1 vcenter">
			<img src="<?php echo $this->model2('file', $match['opponent']['image_id'])->path() ?>" class="img-responsive" alt="" />
		</div>
		<?php endif ?>
		<div class="text-left col-xs-<?php echo $match['opponent']['image_id'] ? 4 : 5 ?> vcenter">
			<h3 class="no-margin">
				<?php
					$opponent = $this->model('matches')->display_scores($match['scores'], $color, TRUE).' '.$match['opponent']['title'];

					if ($match['opponent']['country'])
					{
						$opponent .= '<img src="'.url('themes/default/images/flags/'.$match['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$match['opponent']['country']].'" style="margin-left: 10px;" alt="" />';
					}

					if ($match['opponent']['website'])
					{
						$opponent = '<a href="'.$match['opponent']['website'].'" target="_blank">'.$opponent.'</a>';
					}

					echo $opponent;
				?>
			</h3>
		</div>
	</div>
</div>
<?php endif ?>
<?php if (!empty($rounds)): ?>
<div class="panel-body">
	<?php if ($mode): ?>
		<p class="<?php echo count($rounds) > 1 ? 'pull-right' : 'text-center' ?>"><?php echo icon('fa-cog') ?>Mode: <?php echo $mode ?></p>
	<?php endif ?>
	<?php if (count($rounds) > 1): ?>
		<p><?php echo icon('fa-gamepad') ?> <b>Détail des manches</b></p>
		<?php for ($i = 0; $i < count($rounds); $i++) { ?>
		<div class="well well-sm<?php echo (($i+1) == count($rounds)) ? ' no-margin' : '' ?>">
			<div class="row vcenter">
				<?php if ($rounds[$i]['image_id']): ?>
				<div class="col-xs-2 vcenter">
					<img src="<?php echo $this->model2('file', $rounds[$i]['image_id'])->path() ?>" class="img-responsive" alt="" />
				</div>
				<?php endif ?>
				<div class="col-xs-<?php echo $rounds[$i]['image_id'] ? '3' : '5' ?> vcenter">
					<h4 class="no-margin">Manche <?php echo $i+1 ?></h4>
					<?php echo $rounds[$i]['title'] ?>
				</div>
				<div class="text-right col-xs-3 vcenter">
					<?php echo $match['team']['title'].' '.$this->model('matches')->display_scores([$rounds[$i]['score1'], $rounds[$i]['score2']], $color) ?>
				</div>
				<div class="text-center col-xs-1 vcenter">
					<big><?php echo $rounds[$i]['score1'] ?>:<?php echo $rounds[$i]['score2'] ?></big>
				</div>
				<div class="col-xs-3 vcenter">
					<?php echo $this->model('matches')->display_scores([$rounds[$i]['score1'], $rounds[$i]['score2']], $color, TRUE).' '.$match['opponent']['title'] ?>
				</div>
			</div>
		</div>
		<?php } ?>
	<?php endif ?>
</div>
<?php endif ?>
<?php if ($description): ?>
<div class="panel-body">
	<?php echo bbcode($description) ?>
</div>
<?php endif ?>
<?php
if (!empty($show_details) && $list_participants && $private_description):
	foreach ($list_participants as $participant):
		if ($this->user->admin || ($participant['user_id'] == $this->user->id)): ?>
			<div class="panel-body">
				<?php echo bbcode($private_description) ?>
			</div>
			<?php
			break;
		endif;
	endforeach;
endif;
?>
<?php if ($webtv || $website): ?>
<div class="panel-body">
	<ul class="list-inline no-margin">
		<?php echo $webtv ? '<li><a href="'.$webtv.'" target="_blank">'.icon('fa-twitch').' Retransmission sur Twitch</a></li>' : '' ?>
		<?php echo $website ? '<li><a href="'.$website.'" target="_blank">'.icon('fa-newspaper-o').' On en parle ici</a></li>' : '' ?>
	</ul>
</div>
<?php endif ?>
<div class="panel-footer">
	<div class="pull-right">
		<ul class="list-inline no-margin">
			<li><a href="<?php echo $link.'#participants' ?>"><?php echo icon('fa-users').' '.$participants ?></a></li>
			<li><a href="<?php echo $link.'#comments' ?>"><?php echo icon('fa-comments-o').' '.$this->comments->count_comments('events', $event_id) ?></a></li>
		</ul>
	</div>
	<ul class="list-inline no-margin">
		<li><?php echo $this->label($type['title'], $type['icon'], $type['color'], 'events/type/'.$type['type_id'].'/'.url_title($type['title'])) ?></li>
		<li><?php echo icon('fa-clock-o') ?> <?php echo '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('date_time_long'), $date).'">'.timetostr(NeoFrag()->lang('date_time_short'), $date).'</span>'.($date_end ? '&nbsp;&nbsp;<span data-toggle="tooltip" title="Durée"><i>'.icon('fa-hourglass-end').(ceil((strtotime($date_end) - strtotime($date)) / ( 60 * 60 ))).'h</i></span>' : '') ?></li>
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
