<form action="<?php echo url($NeoFrag->url->request.(empty($data['forum_id']) && empty($data['is_topic']) ? '#reply' : '')); ?>" method="post">
	<table class="table">
		<tbody class="forum-content">
			<?php if (!empty($data['forum_id']) || !empty($data['is_topic'])): ?>
			<tr>
				<td colspan="2"><input type="text" class="form-control" name="<?php echo $data['form_id']; ?>[title]"<?php if (!empty($data['is_topic'])) echo ' value="'.$data['title'].'"'; ?> placeholder="<?php echo i18n('title_topic'); ?>" /></td>
			</tr>
			<?php endif; ?>
			<tr>
				<td class="col-md-3 col-sm-2 hidden-xs">
					<?php echo $NeoFrag->module->get_profile(!empty($data['user_id']) ? $data['user_id'] : $this->user('user_id')); ?>
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