<div class="table-responsive">
<table class="table">
	<thead class="forum-heading">
		<tr>
			<th colspan="2">
				<div class="pull-right">
					<?php echo icon('fa-eye').' '.$data['views'].' '.($data['views'] > 1 ? 'vues' : 'vue'); ?>
				</div>
				<h4 class="no-margin"><?php echo icon('fa-file-text-o').' '.$data['title']; ?></h4>
			</th>
		</tr>
	</thead>
	<tbody class="forum-content">
		<tr>
			<td class="col-md-3 text-center">
				<br />
				<h4 class="no-margin"><?php echo $data['user_id'] ? $NeoFrag->user->link($data['user_id'], $data['username']) : '<i>Visiteur</i>'; ?></h4>
				<?php if ($data['user_id']) echo '<p>'.icon('fa-circle '.($data['online'] ? 'text-green' : 'text-gray')).' '.($data['admin'] ? 'Admin' : 'Membre').' '.($data['online'] ? 'en ligne' : 'hors ligne').'</p>'; ?>
				<img class="img-avatar-forum" src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" title="<?php echo $data['username']; ?>" alt="" />
			</td>
			<td class="text-left col-md-9">
				<div class="padding-top">
					<div class="pull-right">
					<?php if (($data['user_id'] && $NeoFrag->user('user_id') == $data['user_id']) || $NeoFrag->access('forum', 'category_modify', $data['category_id'])): ?>
						<a href="<?php echo url('forum/message/edit/'.$data['message_id'].'/'.url_title($data['title']).'.html'); ?>" class="btn btn-xs btn-primary"><?php echo icon('fa-edit'); ?></a>
						<a href="<?php echo url('forum/message/delete/'.$data['message_id'].'/'.url_title($data['title']).'.html'); ?>" class="btn btn-xs btn-primary delete"><?php echo icon('fa-close'); ?></a>
					<?php endif; ?>
					</div>
					<a name="message_<?php echo $data['message_id']; ?>"></a><?php echo icon('fa-clock-o').' '.time_span($data['date']).' '.($data['last_message_read'] && $data['date'] <= $data['last_message_read'] ? icon('fa-comment-o').' Message lu' : icon('fa-comment').' Message non lu'); ?>
				</div>
				<hr />
				<?php echo bbcode($data['message']); ?>
				<?php if (!empty($data['signature'])): ?>
				<hr />
				<?php echo bbcode($data['signature']); ?>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>
</div>
