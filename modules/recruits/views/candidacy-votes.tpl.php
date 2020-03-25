<?php if(!empty($votes)): ?>
<div class="float-right text-right">
	<ul class="list-inline m-0">
		<li class="text-success"><?php echo $total_up ?> <?php echo icon('far fa-thumbs-up') ?></li>
		<li class="text-danger"><?php echo $total_down ?> <?php echo icon('far fa-thumbs-down') ?></li>
	</ul>
</div>
<p><b>Tendance des votes</b></p>
<div class="progress">
	<div class="progress-bar progress-bar-success" style="width: <?php echo ceil(($total_up/$total_votes)*100) ?>%"><?php echo ceil(($total_up/$total_votes)*100) ?>%</div>
	<div class="progress-bar progress-bar-danger" style="width: <?php echo ceil(($total_down/$total_votes)*100) - 1 ?>%"><?php echo ceil(($total_down/$total_votes)*100) - 1 ?>%</div>
</div>
<div class="row">
	<?php foreach ($votes as $vote): ?>
	<div class="well">
		<div class="media">
			<?php echo $this->user->avatar() ?>
			<div class="media-body">
				<div class="float-right">
					<span class="badge<?php echo $vote['vote'] ? ' badge-success' : ' badge-danger' ?>" style="display: inline-block"><?php echo $vote['vote'] ? icon('far fa-thumbs-up').' Favorable' : icon('far fa-thumbs-down').' Défavorable' ?></span>
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
<?php if ($status == 1): ?>
<hr />
<h4>Mon avis sur cette candidature</h4>
<?php echo $vote_form ?>
<?php endif ?>
