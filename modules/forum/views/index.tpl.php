<table class="table table-hover table-responsive"<?php if ($NeoFrag->config->admin_url) echo ' data-category-id="'.$data['category_id'].'"'; ?>>
	<thead class="forum-heading">
		<tr>
			<th colspan="2">
				<h4 class="no-margin"><?php echo icon('fa-navicon').' '.$data['title']; ?></h4>
			</th>
			<th class="col-md-2"><h4 class="no-margin"><?php echo icon('fa-signal'); ?><span class="hidden-xs"> Statistiques</span></h4></th>
			<th class="col-md-3"><h4 class="no-margin"><?php echo icon('fa-comment-o'); ?><span class="hidden-xs"> Dernier message</span></h4></th>
			<?php if ($NeoFrag->config->admin_url): ?>
			<th class="col-md-1 text-right">
				<?php echo button_edit('admin/forum/categories/'.$data['category_id'].'/'.url_title($data['title']).'.html'); ?>
				<?php echo button_delete('admin/forum/categories/delete/'.$data['category_id'].'/'.url_title($data['title']).'.html'); ?>
			</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody class="forum-content">
		<?php foreach ($data['forums'] as $forum): ?>
		<tr<?php if ($NeoFrag->config->admin_url) echo ' data-forum-id="'.$forum['forum_id'].'"'; ?>>
			<td class="text-center">
				<?php echo $forum['icon']; ?>
			</td>
			<td class="col-md-6">
				<h4 class="no-margin"><a href="<?php echo url('forum/'.$forum['forum_id'].'/'.url_title($forum['title']).'.html'); ?>"><?php echo $forum['title']; ?></a></h4>
				<?php if ($forum['description']) echo '<div>'.$forum['description'].'</div>'; ?>
				<?php
				if (!empty($forum['subforums']) || $NeoFrag->config->admin_url):
					echo '<ul class="subforums">';
					foreach ($forum['subforums'] as $subforum):
						echo '<li'.($NeoFrag->config->admin_url ? ' data-forum-id="'.$subforum['forum_id'].'"' : '').'>'.
								($NeoFrag->config->admin_url ? '<div class="pull-right">'.button_edit('admin/forum/'.$subforum['forum_id'].'/'.url_title($subforum['title']).'.html').' '.button_delete('admin/forum/delete/'.$subforum['forum_id'].'/'.url_title($subforum['title']).'.html').'</div>' : '')
								.$subforum['icon'].' <a href="'.url('forum/'.$subforum['forum_id'].'/'.url_title($subforum['title']).'.html').'">'.$subforum['title'].'</a>'.
							'</li>';
					endforeach;
					echo '</ul>';
				endif;
				?>
			</td>
			<td>
				<?php if ($forum['url']): ?>
				<b><?php echo $forum['redirects']; ?></b> <?php echo $forum['redirects'] > 1 ? 'redirections' : 'redirection'; ?>
				<?php else: ?>
				<b><?php echo $forum['count_topics']; ?></b> <?php echo $forum['count_topics'] > 1 ? 'sujets' : 'sujet'; ?><br />
				<b><?php echo $forum['count_messages']; ?></b> <?php echo $forum['count_messages'] > 1 ? 'réponses' : 'réponse'; ?>
				<?php endif; ?>
			</td>
			<td>
				<?php if (!$forum['url']): ?>
				<?php if ($forum['last_title']): ?>
				<div><a href="<?php echo url('forum/topic/'.$forum['topic_id'].'/'.url_title($forum['last_title']).($forum['last_count_messages'] > $NeoFrag->config->forum_messages_per_page ? '/page/'.ceil($forum['last_count_messages'] / $NeoFrag->config->forum_messages_per_page) : '').'.html#message_'.$forum['last_message_id']); ?>"><?php echo icon('fa-comment-o').' '.str_shortener($forum['last_title'], 40); ?></a></div>
				<div><?php echo icon('fa-user').' '.$NeoFrag->user->link($forum['user_id'], $forum['username']).' '.icon('fa-clock-o').' '.time_span($forum['last_message_date']); ?></div>
				<?php else: ?>
				Pas de message
				<?php endif; endif; ?>
			</td>
			<?php if ($NeoFrag->config->admin_url): ?>
			<td class="col-md-1 text-right">
					<?php echo button_edit('admin/forum/'.$forum['forum_id'].'/'.url_title($forum['title']).'.html'); ?>
					<?php echo button_delete('admin/forum/delete/'.$forum['forum_id'].'/'.url_title($forum['title']).'.html'); ?>
			</td>
			<?php endif; ?>
		</tr>
		<?php endforeach; ?>
		<?php if (empty($data['forums'])): ?>
		<tr>
			<td colspan="4" class="text-center"><h4>Aucun forum</h4></td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>