<?php $link = url('events/'.$event_id.'/'.url_title($title)) ?>
<?php if ($image_id): ?>
	<a href="<?php echo $link ?>"><img src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" class="img-fluid" alt="" /></a>
<?php endif ?>
<?php if (!empty($match['opponent']))://Matches ?>
<div class="card-body text-center">
	<div class="row align-items-center">
		<div class="text-right col-5">
			<h5 class="m-0">
				<a href="<?php echo url('events/team/'.$match['team_id'].'/'.$match['team']['name']) ?>">
				<?php if ($icon = NeoFrag()->model2('file', $match['team']['icon_id'])->path()) echo '<img src="'.NeoFrag()->model2('file', $icon)->path().'" style="margin-right: 10px;" alt="" />' ?>
				<?php echo $match['team']['title'].' '.$this->model('matches')->display_scores($match['scores'], $color) ?>
				</a>
			</h5>
		</div>
		<?php if ($match['scores']): ?>
			<div class="text-center col-2">
				<h3 class="<?php echo $color ?> m-0"><?php echo $match['scores'][0] ?>:<?php echo $match['scores'][1] ?></h3>
			</div>
		<?php else: ?>
			<div class="text-center col-2">
				<h3 class="m-0">VS</h3>
			</div>
		<?php endif ?>
		<?php if ($match['opponent']['image_id']): ?>
		<div class="text-right col-1">
			<img src="<?php echo NeoFrag()->model2('file', $match['opponent']['image_id'])->path() ?>" class="img-fluid" alt="" />
		</div>
		<?php endif ?>
		<div class="text-left col-xs-<?php echo $match['opponent']['image_id'] ? 4 : 5 ?>">
			<h5 class="m-0">
				<?php
					$opponent = $this->model('matches')->display_scores($match['scores'], $color, TRUE).' '.$match['opponent']['title'];

					if ($match['opponent']['country'])
					{
						$opponent .= '<img src="'.url('images/flags/'.$match['opponent']['country'].'.png').'" data-toggle="tooltip" title="'.get_countries()[$match['opponent']['country']].'" style="margin-left: 10px;" alt="" />';
					}

					if ($match['opponent']['website'])
					{
						$opponent = '<a href="'.$match['opponent']['website'].'" target="_blank">'.$opponent.'</a>';
					}

					echo $opponent;
				?>
			</h5>
		</div>
	</div>
</div>
<?php endif ?>
<?php if (!empty($rounds)): ?>
<div class="card-body">
	<?php if ($mode): ?>
		<p class="<?php echo count($rounds) > 1 ? 'pull-right' : 'text-center' ?>"><?php echo icon('fa-cog') ?>Mode: <?php echo $mode ?></p>
	<?php endif ?>
	<?php if (count($rounds) > 1): ?>
		<p><?php echo icon('fa-gamepad') ?> <b>Détail des manches</b></p>
		<table>
			<tbody>
				<?php for ($i = 0; $i < count($rounds); $i++) { ?>
				<tr class="row">
					<?php if ($rounds[$i]['image_id']): ?>
					<td class="col-2 align-middle">
						<img src="<?php echo NeoFrag()->model2('file', $rounds[$i]['image_id'])->path() ?>" class="img-fluid" alt="" />
					</td>
					<?php endif ?>
					<td class="col align-middle">
						<b>Manche <?php echo $i+1 ?></b><br />
						<?php echo $rounds[$i]['title'] ?>
					</td>
					<td class="col text-right align-middle">
						<?php echo $match['team']['title'].' '.$this->model('matches')->display_scores([$rounds[$i]['score1'], $rounds[$i]['score2']], $color) ?>
					</td>
					<td class="col-1 text-center align-middle">
						<big><?php echo $rounds[$i]['score1'] ?>:<?php echo $rounds[$i]['score2'] ?></big>
					</td>
					<td class="col align-middle">
						<?php echo $this->model('matches')->display_scores([$rounds[$i]['score1'], $rounds[$i]['score2']], $color, TRUE).' '.$match['opponent']['title'] ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php endif ?>
</div>
<?php endif ?>
<?php if ($description): ?>
<div class="card-body">
	<?php echo bbcode($description) ?>
</div>
<?php endif ?>
<?php
if (!empty($show_details) && $list_participants && $private_description):
	foreach ($list_participants as $participant):
		if ($this->user->admin || ($participant['user_id'] == $this->user->id)): ?>
			<div class="card-body">
				<?php echo bbcode($private_description) ?>
			</div>
			<?php
			break;
		endif;
	endforeach;
endif;
?>
<?php if ($webtv || $website): ?>
<div class="card-body">
	<ul class="list-inline m-0">
		<?php echo $webtv ? '<li class="list-inline-item"><a href="'.$webtv.'" target="_blank">'.icon('fa-twitch').' Retransmission sur Twitch</a></li>' : '' ?>
		<?php echo $website ? '<li class="list-inline-item"><a href="'.$website.'" target="_blank">'.icon('fa-newspaper-o').' On en parle ici</a></li>' : '' ?>
	</ul>
</div>
<?php endif ?>
<div class="card-footer">
	<div class="pull-right">
		<ul class="list-inline m-0">
			<li class="list-inline-item"><a href="<?php echo $link.'#participants' ?>"><?php echo icon('fa-users').' '.$participants ?></a></li>
			<?php if (($comments = $this->module('comments')) && $comments->is_enabled()): ?>
				<li class="list-inline-item"><?php echo $comments->link('events', $event_id, 'events/'.$event_id.'/'.url_title($title)) ?></li>
			<?php endif ?>
		</ul>
	</div>
	<ul class="list-inline m-0">
		<li class="list-inline-item"><?php echo $this->label($type['title'], $type['icon'], $type['color'], 'events/type/'.$type['type_id'].'/'.url_title($type['title'])) ?></li>
		<li class="list-inline-item"><?php echo icon('fa-clock-o') ?> <?php echo '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $date).'">'.timetostr(NeoFrag()->lang('%d/%m/%Y %H:%M'), $date).'</span>'.($date_end ? '&nbsp;&nbsp;<span data-toggle="tooltip" title="Durée"><i>'.icon('fa-hourglass-end').(ceil((strtotime($date_end) - strtotime($date)) / ( 60 * 60 ))).'h</i></span>' : '') ?></li>
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
