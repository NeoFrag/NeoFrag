<?php if(!empty($data['votes'])): ?>
<div class="pull-right text-right">
	<ul class="list-inline m-0">
		<li class="text-success"><?php echo $data['total_up'] ?> <?php echo icon('fa-thumbs-o-up') ?></li>
		<li class="text-danger"><?php echo $data['total_down'] ?> <?php echo icon('fa-thumbs-o-down') ?></li>
	</ul>
</div>
<p><b>Tendance des votes</b></p>
<div class="progress">
	<div class="progress-bar progress-bar-success" style="width: <?php echo ceil(($data['total_up']/$data['total_votes'])*100) ?>%"><?php echo ceil(($data['total_up']/$data['total_votes'])*100) ?>%</div>
	<div class="progress-bar progress-bar-danger" style="width: <?php echo ceil(($data['total_down']/$data['total_votes'])*100) - 1 ?>%"><?php echo ceil(($data['total_down']/$data['total_votes'])*100) - 1 ?>%</div>
</div>
<div class="row">
	<?php foreach ($data['votes'] as $vote): ?>
	<div class="well">
		<div class="media">
			<div class="media-body">
				<div class="pull-right">
					<span class="badge<?php echo $vote['vote'] ? ' badge-success' : ' badge-danger' ?>" style="display: inline-block"><?php echo $vote['vote'] ? icon('fa-thumbs-o-up').' Favorable' : icon('fa-thumbs-o-down').' Défavorable' ?></span>
				</div>
				<?php echo $this->user->link($vote['user_id'], $vote['username']) ?>
				<?php echo bbcode($vote['comment']) ?>
			</div>
		</div>
	</div>
	<?php endforeach ?>
</div>
<?php else: ?>
Il n'y a pas encore de vote...
<?php endif ?>
<?php if ($data['status'] == 1): ?>
<hr />
<h4>Mon avis sur cette candidature</h4>
<?php echo $data['vote_form'] ?>
<?php endif ?>
