<table class="table inbox no-margin">
	<?php foreach ($data['messages'] as $message): ?>
		<tr<?php echo $message['unread'] ? ' class="unread"' : ''; ?>>
			<td class="col-md-1 text-center"><?php echo icon($message['unread'] ? 'fa-envelope text-primary' : 'fa-envelope-o text-muted'); ?></td>
			<td class="col-md-10">
				<div class="media message">
					<div class="media-left">
						<?php echo $this->user->avatar($message['avatar'], $message['sex'], $message['user_id'], $message['username']); ?>
					</div>
					<div class="media-body">
						<small class="pull-right text-muted"><?php echo time_span($message['date']); ?></small>
						<h5 class="no-margin"><a href="<?php echo url('user/messages/'.$message['message_id'].'/'.url_title($message['title'])); ?>"><b><?php echo mb_strimwidth($message['title'], 0, 40, '...'); ?></b></a></h5>
						<small><?php echo $this->user->link($message['user_id'], $message['username']) ?></small>
					</div>
				</div>
			</td>
			<?php if ($data['allow_delete']): ?>
			<td class="col-md-1 text-right">
				<?php echo $this->button_delete('user/messages/delete/'.$message['message_id'].'/'.url_title($message['title'])); ?>
			</td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
</table>
