<table class="table">
	<thead class="forum-heading">
		<tr>
			<th colspan="2">
				<div class="float-right">
					<?php echo icon('far fa-eye').' '.$this->lang('%d vue|%d vues', $views, $views) ?>
				</div>
				<h5 class="m-0"><?php echo icon('far fa-file-alt').' '.$title ?></h5>
			</th>
		</tr>
	</thead>
	<tbody class="forum-content">
		<tr>
			<td class="col-3">
				<?php echo $this->output->module()->get_profile($user_id, $profile) ?>
			</td>
			<td class="col-9">
				<div class="actions float-right">
				<?php if (($this->user() && $this->user->id == $user_id) || $this->access('forum', 'category_modify', $category_id)): ?>
					<a href="<?php echo url('forum/message/edit/'.$message_id.'/'.url_title($title)) ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="<?php echo $this->lang('Editer le sujet') ?>"><?php echo icon('fas fa-edit') ?></a>
					<a href="<?php echo url('forum/message/delete/'.$message_id.'/'.url_title($title)) ?>" class="btn btn-sm btn-primary delete" data-toggle="tooltip" title="<?php echo $this->lang('Supprimer le sujet') ?>"><?php echo icon('fas fa-times') ?></a>
				<?php endif ?>
				</div>
				<a name="<?php echo $message_id ?>"></a><?php echo icon('far fa-clock').' '.time_span($date).' '.($last_message_read && $date <= $last_message_read ? icon('far fa-comment').' '.$this->lang('Message lu') : icon('fas fa-comment').' '.$this->lang('Message non lu')) ?>
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
