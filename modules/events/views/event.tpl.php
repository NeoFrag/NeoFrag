<?php $link = url('events/'.$event_id.'/'.url_title($title)) ?>
<?php if ($image_id): ?>
	<a href="<?php echo $link ?>"><img src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" class="img-fluid" alt="" /></a>
<?php endif ?>
<?php if (!empty($match['opponent']))://Matches ?>
<div class="card-body text-center">
	<div class="row no-gutters align-items-center">
		<div class="text-right col-<?php echo ($icon = NeoFrag()->model2('file', $match['team']['icon_id'])->path()) ? 4 : 5 ?>">
			<h5 class="m-0">
				<a href="<?php echo url('events/team/'.$match['team_id'].'/'.$match['team']['name']) ?>">
				<?php echo $match['team']['title'].' '.$this->model('matches')->display_scores($match['scores'], $color) ?>
				</a>
			</h5>
		</div>
		<?php if ($icon): ?>
		<div class="text-left col-1">
			<img src="<?php echo $icon ?>" class="img-fluid" alt="" />
		</div>
		<?php endif ?>
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
		<p class="<?php echo count($rounds) > 1 ? 'float-right' : 'text-center' ?>"><?php echo icon('fas fa-cog') ?>Mode: <?php echo $mode ?></p>
	<?php endif ?>
	<?php if (count($rounds) > 1): ?>
		<p class="font-weight-bold">Détail des manches</p>
		<?php for ($i = 0; $i < count($rounds); $i++) { ?>
			<div class="card-group mb-2">
				<div class="card text-center justify-content-center">
					<h6 class="m-0"><?php echo $match['team']['title'].' '.$this->model('matches')->display_scores([$rounds[$i]['score1'], $rounds[$i]['score2']], $color) ?></h6>
				</div>
				<div class="card p-2 col-3 text-center">
					<span class="badge badge-dark">Manche <?php echo $i+1 ?></span>
					<h4 class="my-2"><?php echo $rounds[$i]['score1'] ?>:<?php echo $rounds[$i]['score2'] ?></h4>
					<a href="#"><?php echo $this->label($rounds[$i]['title'], 'far fa-map')->popover_if($rounds[$i]['image_id'], function($id){ return utf8_htmlentities('<img src="'.NeoFrag()->model2('file', $id)->path().'" class="img-fluid" alt="" />'); })?></a>
				</div>
				<div class="card text-center justify-content-center">
					<h6 class="m-0"><?php echo $this->model('matches')->display_scores([$rounds[$i]['score1'], $rounds[$i]['score2']], $color, TRUE).' '.$match['opponent']['title'] ?></h6>
				</div>
			</div>
		<?php } ?>
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
		<?php echo $webtv ? '<li class="list-inline-item"><a href="'.$webtv.'" target="_blank">'.icon('fab fa-twitch').' Retransmission sur Twitch</a></li>' : '' ?>
		<?php echo $website ? '<li class="list-inline-item"><a href="'.$website.'" target="_blank">'.icon('far fa-newspaper').' On en parle ici</a></li>' : '' ?>
	</ul>
</div>
<?php endif ?>
<div class="card-footer">
	<div class="float-right">
		<ul class="list-inline m-0">
			<li class="list-inline-item"><a href="<?php echo $link.'#participants' ?>"><?php echo icon('fas fa-users').' '.$participants ?></a></li>
			<?php if (($comments = $this->module('comments')) && $comments->is_enabled()): ?>
				<li class="list-inline-item"><?php echo $comments->link('events', $event_id, 'events/'.$event_id.'/'.url_title($title)) ?></li>
			<?php endif ?>
		</ul>
	</div>
	<ul class="list-inline m-0">
		<li class="list-inline-item"><?php echo $this->label($type['title'], $type['icon'], $type['color'], 'events/type/'.$type['type_id'].'/'.url_title($type['title'])) ?></li>
		<li class="list-inline-item"><?php echo icon('far fa-clock') ?> <?php echo '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $date).'">'.timetostr(NeoFrag()->lang('%d/%m/%Y %H:%M'), $date).'</span>'.($date_end ? '&nbsp;&nbsp;<span data-toggle="tooltip" title="Durée"><i>'.icon('fas fa-hourglass-end').(ceil((strtotime($date_end) - strtotime($date)) / ( 60 * 60 ))).'h</i></span>' : '') ?></li>
		<?php
		if (!empty($show_details) && $list_participants && $location):
			foreach ($list_participants as $participant):
				if ($this->user->admin || ($participant['user_id'] == $this->user->id)): ?>
					<?php if (($location = explode("\n", $location))) echo '<li class="list-inline-item">'.$this->label(current($location), 'fas fa-map-marker-alt')->popover_if(count($location) > 1, implode('<br>', $location)).'</li>' ?>
					<?php
					break;
				endif;
			endforeach;
		endif;
		?>
	</ul>
</div>
