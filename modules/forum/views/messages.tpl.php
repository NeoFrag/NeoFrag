<table class="table table-striped">
	<thead class="forum-heading">
		<tr>
			<th colspan="2">
				<div class="pull-right">
					<?php echo icon('fa-users').' '.$this->lang('<b>%d</b> participant|<b>%d</b> participants', $nb_users, $nb_users) ?>
				</div>
				<h4 class="m-0"><?php echo icon('fa-comments-o').' '.$this->lang('%d réponse|%d réponses', $nb_messages, $nb_messages) ?></h4>
			</th>
		</tr>
	</thead>
	<tbody class="forum-content">
		<?php foreach ($messages as $message): ?>
		<tr class="row">
			<td class="col-3">
				<?php echo $this->output->module()->get_profile($message['user_id'], $profile) ?>
			</td>
			<td class="col-9">
				<div class="actions">
				<?php if (($this->user() && $this->user->id == $message['user_id']) || $this->access('forum', 'category_modify', $category_id)): ?>
					<a href="<?php echo url('forum/message/edit/'.$message['message_id'].'/'.url_title($title)) ?>" class="btn btn-xs btn-primary"><?php echo icon('fa-edit') ?></a>
					<a href="<?php echo url('forum/message/delete/'.$message['message_id'].'/'.url_title($title)) ?>" class="btn btn-xs btn-primary delete"><?php echo icon('fa-close') ?></a>
				<?php endif ?>
				</div>
				<a name="<?php echo $message['message_id'] ?>"></a><?php echo icon('fa-clock-o').' '.time_span($message['date']).' '.($last_message_read && $message['date'] <= $last_message_read ? icon('fa-comment-o').' '.$this->lang('Message lu') : icon('fa-comment').' '.$this->lang('Message non lu')) ?>
				<hr />
				<?php echo $message['message'] !== NULL ? bbcode($message['message']) : $this->lang('<i>Message supprimé</i>') ?>
				<?php if (!empty($profile['signature'])): ?>
				<hr />
				<?php echo bbcode($profile['signature']) ?>
				<?php endif ?>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
