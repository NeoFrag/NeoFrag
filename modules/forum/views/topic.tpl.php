<div class="table-responsive">
<table class="table">
	<thead class="forum-heading">
		<tr>
			<th colspan="2">
				<div class="pull-right">
					<?php echo icon('fa-eye').' '.$this->lang('<b>%d</b> vue|<b>%d</b> vues', $views, $views) ?>
				</div>
				<h4 class="no-margin"><?php echo icon('fa-file-text-o').' '.$title ?></h4>
			</th>
		</tr>
	</thead>
	<tbody class="forum-content">
		<tr>
			<td class="col-md-3">
				<?php echo $this->output->module()->get_profile($user_id, $profile) ?>
			</td>
			<td class="text-left col-md-9">
				<div class="padding-top">
					<div class="pull-right">
					<?php if (($this->user->id && $this->user->id == $user_id) || $this->access('forum', 'category_modify', $category_id)): ?>
						<a href="<?php echo url('forum/message/edit/'.$message_id.'/'.url_title($title)) ?>" class="btn btn-xs btn-primary"><?php echo icon('fa-edit') ?></a>
						<a href="<?php echo url('forum/message/delete/'.$message_id.'/'.url_title($title)) ?>" class="btn btn-xs btn-primary delete"><?php echo icon('fa-close') ?></a>
					<?php endif ?>
					</div>
					<a name="<?php echo $message_id ?>"></a><?php echo icon('fa-clock-o').' '.time_span($date).' '.($last_message_read && $date <= $last_message_read ? icon('fa-comment-o').' '.$this->lang('Message lu') : icon('fa-comment').' '.$this->lang('Message non lu')) ?>
				</div>
				<hr />
				<?php echo bbcode($message) ?>
				<?php if (!empty($profile['signature'])): ?>
				<hr />
				<?php echo bbcode($profile['signature']) ?>
				<?php endif ?>
			</td>
		</tr>
	</tbody>
</table>
</div>
