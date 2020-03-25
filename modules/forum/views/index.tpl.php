<table class="table table-hover"<?php if ($this->url->admin) echo ' data-category-id="'.$category_id.'"' ?>>
	<thead class="forum-heading">
		<tr>
			<th class="col-<?php echo $this->url->admin ? 6 : 7 ?>" colspan="2"><h5 class="m-0"><?php echo icon($this->url->admin ? 'fas fa-arrows-alt-v' : 'fas fa-bars').' '.$title ?></h5></th>
			<th class="col-2"><h5 class="m-0"><?php echo icon('fas fa-signal') ?><span class="d-none d-sm-inline-block ml-1"><?php echo $this->lang('Statistiques') ?></span></h5></th>
			<th class="col-3"><h5 class="m-0"><?php echo icon('far fa-comment') ?><span class="d-none d-sm-inline-block ml-1"><?php echo $this->lang('Dernier message') ?></span></h5></th>
			<?php if ($this->url->admin): ?>
			<th class="col-1 text-right">
				<?php echo $this->button_access($category_id, 'category') ?>
				<?php echo $this->button_update('admin/forum/categories/'.$category_id.'/'.url_title($title)) ?>
				<?php echo $this->button_delete('admin/forum/categories/delete/'.$category_id.'/'.url_title($title)) ?>
			</th>
			<?php endif ?>
		</tr>
	</thead>
	<tbody class="forum-content">
		<?php foreach ($forums as $forum): ?>
		<tr<?php if ($this->url->admin) echo ' data-forum-id="'.$forum['forum_id'].'"' ?>>
			<td class="col-1">
				<?php echo $this->url->admin ? icon('fas fa-arrows-alt-v') : $forum['icon'] ?>
			</td>
			<td class="col-<?php echo $this->url->admin ? 5 : 6 ?>">
				<h5 class="m-0"><a href="<?php echo url('forum/'.$forum['forum_id'].'/'.url_title($forum['title'])) ?>"><?php echo $forum['title'] ?></a></h5>
				<?php if ($forum['description']) echo '<div>'.$forum['description'].'</div>' ?>
				<?php
				if (!empty($forum['subforums']) || $this->url->admin):
					echo '<ul class="subforums mb-0 mt-1'.($this->url->admin ? ' list-group' : ' list-inline').'">';
					foreach ($forum['subforums'] as $subforum):
						echo '<li'.($this->url->admin ? ' data-forum-id="'.$subforum['forum_id'].'" class="list-group-item p-2"' : ' class="list-inline-item"').'>'.
								($this->url->admin ? '<div class="float-right">'.$this->button_update('admin/forum/'.$subforum['forum_id'].'/'.url_title($subforum['title'])).' '.$this->button_delete('admin/forum/delete/'.$subforum['forum_id'].'/'.url_title($subforum['title'])).'</div>' : '')
								.($this->url->admin ? icon('fas fa-arrows-alt-v') : $subforum['icon']).' <a href="'.url('forum/'.$subforum['forum_id'].'/'.url_title($subforum['title'])).'">'.$subforum['title'].'</a>'.
							'</li>';
					endforeach;
					echo '</ul>';
				endif;
				?>
			</td>
			<td class="col-2">
			<?php
				if ($forum['url'])
				{
					echo 	$this->lang('%d redirection|%d redirections', $forum['redirects'], $forum['redirects']);
				}
				else
				{
					echo 	$this->lang('%d sujet|%d sujets', $forum['count_topics'], $forum['count_topics']).'<br />'.
							$this->lang('%d réponse|%d réponses', $forum['count_messages'], $forum['count_messages']);
				}
			?>
			</td>
			<td class="col-3">
				<?php if (!$forum['url']): ?>
				<?php if ($forum['last_title']): ?>
					<div><a href="<?php echo url('forum/topic/'.$forum['topic_id'].'/'.url_title($forum['last_title']).($forum['last_count_messages'] > $this->config->forum_messages_per_page ? '/page/'.ceil($forum['last_count_messages'] / $this->config->forum_messages_per_page) : '').'#'.$forum['last_message_id']) ?>"><?php echo icon('far fa-comment').' '.str_shortener($forum['last_title'], 40) ?></a></div>
					<div><small><?php echo icon('fas fa-user').' '.($forum['user_id'] ? $this->user->link($forum['user_id'], $forum['username']) : '<i>'.$this->lang('Visiteur').'</i>').' '.icon('far fa-clock').' '.time_span($forum['last_message_date']) ?></small></div>
				<?php else: ?>
					<?php echo $this->lang('Aucun message') ?>
				<?php endif; endif ?>
			</td>
			<?php if ($this->url->admin): ?>
			<td class="col-1 text-right">
				<?php echo $this->button_update('admin/forum/'.$forum['forum_id'].'/'.url_title($forum['title'])) ?>
				<?php echo $this->button_delete('admin/forum/delete/'.$forum['forum_id'].'/'.url_title($forum['title'])) ?>
			</td>
			<?php endif ?>
		</tr>
		<?php endforeach ?>
		<?php if (empty($forums)): ?>
		<tr>
			<td colspan="<?php echo $this->url->admin ? 5 : 4 ?>"><div class="alert alert-info text-center mb-0"><?php echo $this->lang('Aucun forum') ?></div></td>
		</tr>
		<?php endif ?>
	</tbody>
</table>
