<form action="<?php echo url($this->url->request.(empty($forum_id) && empty($is_topic) ? '#reply' : '')) ?>" method="post">
	<table class="table">
		<tbody class="forum-content">
			<?php if (!empty($forum_id) || !empty($is_topic)): ?>
			<tr>
				<td colspan="2"><input type="text" class="form-control form-control-lg" name="<?php echo $form_id ?>[title]" value="<?php echo isset($post['title']) ? $post['title'] : (isset($title) && !empty($is_topic) ? $title : '') ?>" placeholder="<?php echo $this->lang('Titre du sujet') ?>" /></td>
			</tr>
			<?php endif ?>
			<tr>
				<td class="col-3 hidden-xs">
					<?php echo $this->output->module()->get_profile(!empty($user_id) ? $user_id : $this->user->id) ?>
				</td>
				<td class="col-9">
					<div class="form-group">
						<textarea class="form-control editor" name="<?php echo $form_id ?>[message]" rows="10"><?php echo isset($post['message']) ? $post['message'] : (isset($message) ? $message : '') ?></textarea>
					</div>
					<?php if (!empty($forum_id) && $this->access('forum', 'category_announce', $category_id)): ?>
					<div class="checkbox">
						<label><input type="checkbox" name="<?php echo $form_id ?>[announce][]"<?php if (!empty($post['announce']) && in_array('on', $post['announce'])) echo ' checked="checked"' ?> /> <?php echo $this->lang('Mettre en annonce') ?></label>
					</div>
					<?php endif ?>
					<?php if (!empty($forum_id)): ?>
					<a href="<?php echo url($this->url->back() ?: 'forum/'.$forum_id.'/'.url_title($title)) ?>" class="btn btn-secondary"><?php echo $this->lang('Retour') ?></a>
					<button type="submit" class="btn btn-primary"><?php echo $this->lang('Poster le sujet') ?></button>
					<?php elseif (!empty($topic_id)): ?>
					<a href="<?php echo url($this->url->back() ?: 'forum/topic/'.$topic_id.'/'.url_title($title)) ?>" class="btn btn-secondary"><?php echo $this->lang('Retour') ?></a>
					<button type="submit" class="btn btn-primary"><?php echo $this->lang($is_topic ? 'Modifier le sujet' : 'Modifier le message') ?></button>
					<?php else: ?>
					<button type="submit" class="btn btn-primary"><?php echo $this->lang('Répondre au sujet') ?></button>
					<?php endif ?>
				</td>
			</tr>
		</tbody>
	</table>
</form>
