<div class="table-responsive">
<table class="table">
	<thead class="forum-heading">
		<tr>
			<th colspan="2">
				<div class="pull-right">
					<?php echo icon('fa-eye').' '.$this->lang('views', $data['views'], $data['views']) ?>
				</div>
				<h4 class="no-margin"><?php echo icon('fa-file-text-o').' '.$data['title'] ?></h4>
			</th>
		</tr>
	</thead>
	<tbody class="forum-content">
		<tr>
			<td class="col-md-3">
				<?php echo NeoFrag()->module->get_profile($data['user_id'], $profile) ?>
			</td>
			<td class="text-left col-md-9">
				<div class="padding-top">
					<div class="pull-right">
					<?php if (($this->user() && $this->user('user_id') == $data['user_id']) || $this->access('forum', 'category_modify', $data['category_id'])): ?>
						<a href="<?php echo url('forum/message/edit/'.$data['message_id'].'/'.url_title($data['title'])) ?>" class="btn btn-xs btn-primary"><?php echo icon('fa-edit') ?></a>
						<a href="<?php echo url('forum/message/delete/'.$data['message_id'].'/'.url_title($data['title'])) ?>" class="btn btn-xs btn-primary delete"><?php echo icon('fa-close') ?></a>
					<?php endif ?>
					</div>
					<a name="<?php echo $data['message_id'] ?>"></a><?php echo icon('fa-clock-o').' '.time_span($data['date']).' '.($data['last_message_read'] && $data['date'] <= $data['last_message_read'] ? icon('fa-comment-o').' '.$this->lang('message_read') : icon('fa-comment').' '.$this->lang('message_unread')) ?>
				</div>
				<hr />
				<?php echo bbcode($data['message']) ?>
				<?php if (!empty($profile['signature'])): ?>
				<hr />
				<?php echo bbcode($profile['signature']) ?>
				<?php endif ?>
			</td>
		</tr>
	</tbody>
</table>
</div>
