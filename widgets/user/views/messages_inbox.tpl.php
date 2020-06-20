<ul class="list-group list-group-flush mb-0">
	<?php if ($messages): ?>
		<?php foreach ($messages as $message): ?>
			<li class="list-group-item">
				<div class="media">
					<div class="media-body">
						<h6 class="mt-1 mb-0"><?php echo icon($message['unread'] ? 'fas fa-envelope text-primary' : 'far fa-envelope-open') ?> <a href="<?php echo url('user/messages/'.$message['message_id'].'/'.url_title($message['title']).(in_array($box, array('sent', 'archives')) ? '/'.$box : '')) ?>"><?php echo mb_strimwidth($message['title'], 0, 35, '...') ?></a></h6>
						<small class="text-muted"><?php echo icon('far fa-user').'<b class="mr-1">'.$message['username'].'</b> '.icon('far fa-clock').' '.time_span($message['date']) ?></small>
					</div>
				</div>
			</li>
		<?php endforeach ?>
	<?php else: ?>
		<li class="list-group-item">
			<?php echo $this->lang('Aucun message') ?>
		</li>
	<?php endif ?>
</ul>
