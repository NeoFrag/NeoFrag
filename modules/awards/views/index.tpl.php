<?php if (${'stats-team'} || ${'stats-game'}): ?>
	<?php if ($image_id): ?>
		<img class="img-fluid" src="<?php echo NeoFrag()->model2('file', $image_id)->path() ?>" alt="" />
	<?php endif ?>
	<div class="panel-body">
		<div class="well text-center">
			<h4><?php echo ${'stats-team'} ? $this->lang('Palmarès de cette équipe') : $this->lang('Palmarès sur ce jeu') ?></h4>
			<ul class="list-inline m-0">
				<li>
					<span data-toggle="tooltip" title="<?php echo $this->lang('1ère place') ?>"><?php echo icon('fa-trophy fa-2x trophy-gold') ?></span><br />
					<b><?php echo $total_gold[0].($total_gold[0] > 1 ? ' '.$this->lang('trophées') : ' '.$this->lang('trophée')) ?></b>
				</li>
				<li>
					<span data-toggle="tooltip" title="<?php echo $this->lang('2ème place') ?>"><?php echo icon('fa-trophy fa-2x trophy-silver') ?></span><br />
					<b><?php echo $total_silver[0].($total_silver[0] > 1 ? ' '.$this->lang('trophées') : ' '.$this->lang('trophée')) ?></b>
				</li>
				<li>
					<span data-toggle="tooltip" title="<?php echo $this->lang('3ème place') ?>"><?php echo icon('fa-trophy fa-2x trophy-bronze') ?></span><br />
					<b><?php echo $total_bronze[0].($total_bronze[0] > 1 ? ' '.$this->lang('trophées') : ' '.$this->lang('trophée')) ?></b>
				</li>
			</ul>
		</div>
<?php else: ?>
<div class="panel-body">
<?php endif ?>
	<div class="table-responsive">
		<table class="table table-hover m-0">
			<thead>
				<tr>
					<th class="col-1"></th>
					<th class="col-1 text-center"><span data-toggle="tooltip" title="<?php echo $this->lang('Classement') ?>"><?php echo icon('fa-trophy') ?></span></th>
					<th class="col-2"><span data-toggle="tooltip" title="<?php echo $this->lang('Plateforme') ?>"><?php echo icon('fa-tv') ?></span></th>
					<th class="col-8" colspan="2"><?php echo $this->lang('Événement') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($awards):
					foreach ($awards as $award): ?>
					<tr>
						<td>
							<span data-toggle="tooltip" title="<?php echo timetostr($this->lang('%A %e %B %Y'), $award['date']) ?>"><?php echo icon('fa-calendar-o') ?></span>
						</td>
						<td class="text-center">
							<?php
							if ($award['ranking'] == 1)
							{
								echo '<span data-toggle="tooltip" title="'.$award['ranking'].$this->lang('er')' / '.$award['participants'].' '.$this->lang('équipes').'">'.icon('fa-trophy trophy-gold').'</span>';
							}
							else if ($award['ranking'] == 2)
							{
								echo '<span data-toggle="tooltip" title="'.$award['ranking'].$this->lang('ème')' / '.$award['participants'].' '.$this->lang('équipes').'">'.icon('fa-trophy trophy-silver').'</span>';
							}
							else if ($award['ranking'] == 3)
							{
								echo '<span data-toggle="tooltip" title="'.$award['ranking'].$this->lang('ème')' / '.$award['participants'].' '.$this->lang('équipes').'">'.icon('fa-trophy trophy-bronze').'</span>';
							}
							else
							{
								echo $award['ranking'].'<small>'.$this->lang('ème').'</small>';
							}
							?>
						</td>
						<td><?php echo $award['platform'] ?></td>
						<td>
							<a href="<?php echo url('awards/'.$award['award_id'].'/'.url_title($award['name'])) ?>"><?php echo $award['name'] ?></a>
						</td>
						<td>
							<?php if ($award['location']): ?><div><span data-toggle="tooltip" title="<?php echo $this->lang('Lieu') ?>"><?php echo icon('fa-map-marker').' '.$award['location'] ?></span></div><?php endif ?>
						</td>
					</tr>
				<?php
					endforeach;
				else:
				?>
				<tr>
					<td colspan="3" class="text-center"><?php echo $this->lang('Aucun trophée...') ?></td>
				</tr>
				<?php
				endif;
				?>
			</tbody>
		</table>
	</div>
</div>
