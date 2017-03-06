<div class="table-responsive">
	<table class="table table-hover">
		<thead class="forum-heading">
			<tr>
				<th colspan="2" class="col-md-7"><h4 class="no-margin"><?php echo icon($data['icon']).' '.$data['title']; ?></h4></th>
				<th class="col-md-2"><h4 class="no-margin"><?php echo icon('fa-signal'); ?><span class="hidden-xs"> <?php echo $this->lang('statistics'); ?></span></h4></th>
				<th class="col-md-3"><h4 class="no-margin"><?php echo icon('fa-comment-o'); ?><span class="hidden-xs"> <?php echo $this->lang('last_message'); ?></span></h4></th>
			</tr>
		</thead>
		<tbody class="forum-content">
			<?php foreach ($data['topics'] as $topic): ?>
			<tr>
				<td class="col-md-1 text-center">
					<?php echo $topic['icon']; ?>
				</td>
				<td class="col-md-6">
					<?php
						if ($topic['count_messages'] > $this->config->forum_messages_per_page)
						{
							echo '<div class="pull-right">'.$this->pagination->display(url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title'])), ceil($topic['count_messages'] / $this->config->forum_messages_per_page), 'xs').'</div>';
						}
					?>
					<h4 class="no-margin"><a href="<?php echo url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title'])); ?>"><?php echo $topic['title']; ?></a></h4>
					<div><?php echo icon('fa-user').' '.($topic['user_id'] ? $this->user->link($topic['user_id'], $topic['username']) : '<i>'.$this->lang('guest').'</i>').' '.icon('fa-clock-o').' '.time_span($topic['date']); ?></div>
				</td>
				<td>
					<?php echo $this->lang('messages', $topic['count_messages'], $topic['count_messages']); ?><br />
					<?php echo $this->lang('views', $topic['views'], $topic['views']); ?>
				</td>
				<td>
					<?php if ($topic['count_messages']): ?>
					<div><a href="<?php echo url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title']).($topic['count_messages'] > $this->config->forum_messages_per_page ? '/page/'.ceil($topic['count_messages'] / $this->config->forum_messages_per_page) : '').'#'.$topic['last_message_id']); ?>"><?php echo icon('fa-comment-o').' '.str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($topic['message']))), 35); ?></a></div>
						<div><?php echo icon('fa-user').' '.($topic['last_user_id'] ? $this->user->link($topic['last_user_id'], $topic['last_username']) : '<i>'.$this->lang('guest').'</i>').' '.icon('fa-clock-o').' '.time_span($topic['last_message_date']); ?></div>
					<?php else: ?>
						<?php echo $this->lang('no_answer'); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php if (empty($data['topics'])): ?>
			<tr>
				<td colspan="4" class="text-center"><h4><?php echo $this->lang('no_message'); ?></h4></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>