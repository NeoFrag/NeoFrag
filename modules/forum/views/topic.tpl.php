<table class="table">
	<thead class="forum-heading">
		<tr>
			<th colspan="2">
				<div class="pull-right">
					{fa-icon eye} {views} <?php echo $data['views'] > 1 ? 'vues' : 'vue'; ?>
				</div>
				<h4 class="no-margin">{fa-icon file-text-o} {title}</h4>
			</th>
		</tr>
	</thead>
	<tbody class="forum-content">
		<tr>
			<td class="col-md-3 text-center">
				<br />
				<h4 class="no-margin"><?php echo $NeoFrag->user->link($data['user_id'], $data['username']); ?></h4>
				<p><i class="fa fa-circle <?php echo $data['online'] ? 'text-green' : 'text-gray'; ?>"></i> <?php echo $data['admin'] ? 'Admin' : 'Membre'; ?> <?php echo $data['online'] ? 'en ligne' : 'hors ligne'; ?></p>
				<img class="img-avatar-forum" src="<?php echo $NeoFrag->user->avatar($data['avatar'], $data['sex']); ?>" title="{username}" alt="" />
			</td>
			<td class="text-left col-md-9">
				<div class="padding-top">
					<div class="pull-right">
					<?php if ($NeoFrag->user('user_id') == $data['user_id'] || is_authorized('forum', 'category_modify', $data['category_id'])): ?>
						<a href="{base_url}forum/message/edit/{message_id}/{url_title(title)}.html" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
						<a href="{base_url}forum/message/delete/{message_id}/{url_title(title)}.html" class="btn btn-xs btn-primary delete"><i class="fa fa-close"></i></a>
					<?php endif; ?>
					</div>
					<a name="message_{message_id}"></a>{fa-icon clock-o} <?php echo time_span($data['date']); ?> <?php echo $data['last_message_read'] && $data['date'] <= $data['last_message_read'] ? $NeoFrag->assets->icon('fa-comment-o').' Message lu' : $NeoFrag->assets->icon('fa-comment').' Message non lu'; ?>
				</div>
				<hr />
				{bbcode(message)}
				<?php if (!empty($data['signature'])): ?>
				<hr />
				{bbcode(signature)}
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>
