<?php if(!empty($data['votes'])): ?>
	<?php foreach ($data['votes'] as $k => $vote): ?>
		<div class="media">
			<div class="media-body">
				<div class="pull-right">
					<span class="badge<?php echo $vote['vote'] ? ' badge-success' : ' badge-danger' ?>" style="display: inline-block"><?php echo $vote['vote'] ? icon('fa-thumbs-o-up').' Favorable' : icon('fa-thumbs-o-down').' Défavorable' ?></span>
				</div>
				<b><?php echo $this->user->link($vote['user_id'], $vote['username']) ?></b><br />
				<?php echo bbcode($vote['comment']) ?>
			</div>
		</div>
		<?php end($data['votes']) ?>
		<?php $lastElementKey = key($data['votes']) ?>
		<?php echo ($k != $lastElementKey) ? '<hr style="margin-top: 12px; margin-bottom: 12px;"/>' : '' ?>
	<?php endforeach ?>
<?php else: ?>
	Aucun avis déposé.
<?php endif ?>
