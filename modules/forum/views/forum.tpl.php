<table class="table table-hover table-responsive">
	<thead class="forum-heading">
		<tr>
			<th colspan="2" class="col-md-7"><h4 class="no-margin"><?php echo $NeoFrag->assets->icon($data['icon']); ?> <?php echo $data['title']; ?></h4></th>
			<th class="col-md-2"><h4 class="no-margin"><i class="fa fa-signal"></i><span class="hidden-xs"> Statistiques</span></h4></th>
			<th class="col-md-3"><h4 class="no-margin"><i class="fa fa-comment-o"></i><span class="hidden-xs"> Dernier message</span></h4></th>
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
					if ($topic['count_messages'] > $NeoFrag->config->forum_messages_per_page)
					{
						echo '<div class="pull-right">'.$loader->pagination->display('{base_url}forum/topic/'.$topic['topic_id'].'/'.url_title($topic['title']), ceil($topic['count_messages'] / $NeoFrag->config->forum_messages_per_page), 'xs').'</div>';
					}
				?>
				<h4 class="no-margin"><a href="{base_url}forum/topic/<?php echo $topic['topic_id']; ?>/<?php echo url_title($topic['title']); ?>.html"><?php echo $topic['title']; ?></a></h4>
				<div>{fa-icon user} <?php echo $NeoFrag->user->link($topic['user_id'], $topic['username']); ?> {fa-icon clock-o} <?php echo time_span($topic['date']); ?></div>
			</td>
			<td>
				<b><?php echo $topic['count_messages']; ?></b> <?php echo $topic['count_messages'] > 1 ? 'réponses' : 'réponse'; ?><br />
				<b><?php echo $topic['views']; ?></b> <?php echo $topic['views'] > 1 ? 'vues' : 'vue'; ?>
			</td>
			<td>
				<?php if ($topic['last_user_id']): ?>
				<div><a href="{base_url}forum/topic/<?php echo $topic['topic_id']; ?>/<?php echo url_title($topic['title']).($topic['count_messages'] > $NeoFrag->config->forum_messages_per_page ? '/page/'.ceil($topic['count_messages'] / $NeoFrag->config->forum_messages_per_page) : ''); ?>.html#message_<?php echo $topic['last_message_id']; ?>">{fa-icon comment-o} <?php echo str_shortener(strip_tags(str_replace('<br />', ' ', bbcode($topic['message']))), 35); ?></a></div>
				<div>{fa-icon user} <?php echo $NeoFrag->user->link($topic['last_user_id'], $topic['last_username']); ?> {fa-icon clock-o} <?php echo time_span($topic['last_message_date']); ?></div>
				<?php else: ?>
				Pas de réponse
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		<?php if (empty($data['topics'])): ?>
		<tr>
			<td colspan="4" class="text-center"><h4>Aucun message</h4></td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>