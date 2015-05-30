<table class="table table-striped">
	<thead class="forum-heading">
		<tr>
			<th colspan="2">
				<div class="pull-right">
					{fa-icon users} {nb_users} <?php echo $data['nb_users'] > 1 ? 'participants' : 'participant'; ?>
				</div>
				<h4 class="no-margin">{fa-icon comments-o} {nb_messages} <?php echo $data['nb_messages'] > 1 ? 'réponses' : 'réponse'; ?></h4>
			</th>
		</tr>
	</thead>
	<tbody class="forum-content">
		<?php foreach ($data['messages'] as $message): ?>
		<tr>
			<td class="col-md-3 text-center">
				<br />
				<h4 class="no-margin"><?php echo $NeoFrag->user->link($message['user_id'], $message['username']); ?></h4>
				<p><i class="fa fa-circle <?php echo $message['online'] ? 'text-green' : 'text-gray'; ?>"></i> <?php echo $message['admin'] ? 'Admin' : 'Membre'; ?> <?php echo $message['online'] ? 'en ligne' : 'hors ligne'; ?></p>
				<img class="img-avatar-forum" src="<?php echo $NeoFrag->user->avatar($message['avatar'], $message['sex']); ?>" title="<?php echo $message['username']; ?>" alt="" />
			</td>
			<td class="text-left col-md-9">
				<div class="padding-top">
					<div class="pull-right">
					<?php if ($NeoFrag->user('user_id') == $message['user_id'] || is_authorized('forum', 'category_modify', $data['category_id'])): ?>
						<a href="{base_url}forum/message/edit/<?php echo $message['message_id']; ?>/{url_title(title)}.html" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
						<a href="{base_url}forum/message/delete/<?php echo $message['message_id']; ?>/{url_title(title)}.html" class="btn btn-xs btn-primary delete"><i class="fa fa-close"></i></a>
					<?php endif; ?>
					</div>
					<a name="message_<?php echo $message['message_id']; ?>"></a>{fa-icon clock-o} <?php echo time_span($message['date']); ?> <?php echo $data['last_message_read'] && $message['date'] <= $data['last_message_read'] ? $NeoFrag->assets->icon('fa-comment-o').' Message lu' : $NeoFrag->assets->icon('fa-comment').' Message non lu'; ?>
				</div>
				<hr />
				<?php echo !is_null($message['message']) ? bbcode($message['message']) : '<i>Message supprimé</i>'; ?>
				<?php if (!empty($message['signature'])): ?>
				<hr />
				<?php echo bbcode($message['signature']); ?>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>