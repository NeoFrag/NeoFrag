<table class="table table-hover">
	<thead class="forum-heading">
		<tr class="row">
			<th class="col-7" colspan="2"><h4 class="m-0"><?php echo icon($icon).' '.$title ?></h4></th>
			<th class="col-2"><h4 class="m-0"><?php echo icon('fa-signal') ?><span class="hidden-xs"> <?php echo $this->lang('Statistiques') ?></span></h4></th>
			<th class="col-3"><h4 class="m-0"><?php echo icon('fa-comment-o') ?><span class="hidden-xs"> <?php echo $this->lang('Dernier message') ?></span></h4></th>
		</tr>
	</thead>
	<tbody class="forum-content">
		<?php foreach ($topics as $topic): ?>
		<tr class="row">
			<td class="col-1 text-center">
				<?php echo $topic['icon'] ?>
			</td>
			<td class="col-6">
				<?php
					if ($topic['count_messages'] > $this->config->forum_messages_per_page)
					{
						echo '<div class="pull-right">'.$this->pagination->display(url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title'])), ceil($topic['count_messages'] / $this->config->forum_messages_per_page), 'xs').'</div>';
					}
				?>
				<h4 class="m-0"><a href="<?php echo url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title'])) ?>"><?php echo $topic['title'] ?></a></h4>
				<div><?php echo icon('fa-user').' '.($topic['user_id'] ? $this->user->link($topic['user_id'], $topic['username']) : '<i>'.$this->lang('Visiteur').'</i>').' '.icon('fa-clock-o').' '.time_span($topic['date']) ?></div>
			</td>
			<td class="col-2">
				<?php echo $this->lang('<b>%d</b> réponse|<b>%d</b> réponses', $topic['count_messages'], $topic['count_messages']) ?><br />
				<?php echo $this->lang('<b>%d</b> vue|<b>%d</b> vues', $topic['views'], $topic['views']) ?>
			</td>
			<td class="col-3">
				<?php if ($topic['count_messages']): ?>
				<div><a href="<?php echo url('forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title']).($topic['count_messages'] > $this->config->forum_messages_per_page ? '/page/'.ceil($topic['count_messages'] / $this->config->forum_messages_per_page) : '').'#'.$topic['last_message_id']) ?>"><?php echo icon('fa-comment-o').' '.str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($topic['message']))), 35) ?></a></div>
					<div><?php echo icon('fa-user').' '.($topic['last_user_id'] ? $this->user->link($topic['last_user_id'], $topic['last_username']) : '<i>'.$this->lang('Visiteur').'</i>').' '.icon('fa-clock-o').' '.time_span($topic['last_message_date']) ?></div>
				<?php else: ?>
					<?php echo $this->lang('Pas de réponse') ?>
				<?php endif ?>
			</td>
		</tr>
		<?php endforeach ?>
		<?php if (empty($topics)): ?>
		<tr>
			<td colspan="4" class="text-center"><h6><?php echo $this->lang('Aucun message') ?></h6></td>
		</tr>
		<?php endif ?>
	</tbody>
</table>
