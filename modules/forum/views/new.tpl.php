<form action="<?php echo url($NeoFrag->config->request_url.(empty($data['forum_id']) && empty($data['is_topic']) ? '#reply' : '')); ?>" method="post">
	<table class="table">
		<tbody class="forum-content">
			<?php if (!empty($data['forum_id']) || !empty($data['is_topic'])): ?>
			<tr>
				<td colspan="2"><input type="text" class="form-control" name="<?php echo $data['form_id']; ?>[title]"<?php if (!empty($data['is_topic'])) echo ' value="'.$data['title'].'"'; ?> placeholder="<?php echo i18n('title_topic'); ?>" /></td>
			</tr>
			<?php endif; ?>
			<tr>
				<td class="col-md-3 col-sm-2 hidden-xs text-center">
					<br />
					<?php if (!empty($data['topic_id']) && $data['user_id']): ?>
					<h4 class="no-margin"><?php echo $NeoFrag->user->link($data['user_id'], $data['username']); ?></h4>
					<?php elseif (empty($data['topic_id']) && $this->user()): ?>
					<h4 class="no-margin"><?php echo $NeoFrag->user->link(); ?></h4>
					<?php else: ?>
					<h4 class="no-margin"><i><?php echo i18n('guest'); ?></i></h4>
					<?php endif; ?>
					<?php if (!empty($data['topic_id']) && $data['user_id'] || empty($data['topic_id']) && $this->user()) echo '<p>'.icon('fa-circle '.(empty($data['topic_id']) || $data['online'] ? 'text-green' : 'text-gray')).' '.i18n(!empty($data['topic_id']) ? $data['admin'] : $NeoFrag->user('admin') ? 'admin' : 'member').' '.i18n(empty($data['topic_id']) || $data['online'] ? 'online' : 'offline').'</p>'; ?>
					<img class="img-avatar-forum" src="<?php echo !empty($data['topic_id']) ? $NeoFrag->user->avatar($data['avatar'], $data['sex']) : $NeoFrag->user->avatar(); ?>" title="<?php echo !empty($data['topic_id']) ? $data['username'] : $NeoFrag->user('username'); ?>" alt="" />
				</td>
				<td class="text-left col-md-9 col-sm-10">
					<div class="form-group">
						<textarea class="form-control editor" name="<?php echo $data['form_id']; ?>[message]" rows="10"><?php if (!empty($data['message'])) echo $data['message']; ?></textarea>
					</div>
					<?php if (!empty($data['forum_id']) && $NeoFrag->access('forum', 'category_announce', $data['category_id'])): ?>
					<div class="checkbox">
						<label><input type="checkbox" name="<?php echo $data['form_id']; ?>[announce][]" /> <?php echo i18n('set_announce'); ?></label>
					</div>
					<?php endif; ?>
					<?php if (!empty($data['forum_id'])): ?>
					<a href="<?php echo url($NeoFrag->session->get_back() ?: 'forum/'.$data['forum_id'].'/'.url_title($data['title']).'.html'); ?>" class="btn btn-default"><?php echo i18n('back'); ?></a>
					<button type="submit" class="btn btn-primary"><?php echo i18n('post_topic'); ?></button>
					<?php elseif (!empty($data['topic_id'])): ?>
					<a href="<?php echo url($NeoFrag->session->get_back() ?: 'forum/topic/'.$data['topic_id'].'/'.url_title($data['title']).'.html'); ?>" class="btn btn-default"><?php echo i18n('back'); ?></a>
					<button type="submit" class="btn btn-primary"><?php echo i18n($data['is_topic'] ? 'modify_topic' : 'modify_message'); ?></button>
					<?php else: ?>
					<button type="submit" class="btn btn-primary"><?php echo i18n('reply_topic'); ?></button>
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
	</table>
</form>