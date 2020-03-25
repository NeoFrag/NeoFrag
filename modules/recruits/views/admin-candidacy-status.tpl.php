<?php if(!empty($votes)): ?>
	<?php foreach ($votes as $k => $vote): ?>
		<div class="media">
			<?php echo $this->user->avatar() ?>
			<div class="media-body">
				<div class="float-right">
					<span class="badge<?php echo $vote['vote'] ? ' badge-success' : ' badge-danger' ?>" style="display: inline-block"><?php echo $vote['vote'] ? icon('far fa-thumbs-up').' Favorable' : icon('far fa-thumbs-down').' Défavorable' ?></span>
				</div>
				<b><?php echo $this->user->link($vote['user_id'], $vote['username']) ?></b><br />
				<?php echo bbcode($vote['comment']) ?>
			</div>
		</div>
		<?php end($votes) ?>
		<?php $lastElementKey = key($votes) ?>
		<?php echo ($k != $lastElementKey) ? '<hr style="margin-top: 12px; margin-bottom: 12px;"/>' : '' ?>
	<?php endforeach ?>
<?php else: ?>
	Aucun avis déposé.
<?php endif ?>
