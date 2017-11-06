<form action="<?php echo url($this->url->request.(empty($data['forum_id']) && empty($data['is_topic']) ? '#reply' : '')) ?>" method="post">
	<table class="table">
		<tbody class="forum-content">
			<?php if (!empty($data['forum_id']) || !empty($data['is_topic'])): ?>
			<tr>
				<td colspan="2"><input type="text" class="form-control" name="<?php echo $data['form_id'] ?>[title]" value="<?php echo isset($data['post']['title']) ? $data['post']['title'] : (isset($data['title']) && !empty($data['is_topic']) ? $data['title'] : '') ?>" placeholder="<?php echo $this->lang('Titre du sujet') ?>" /></td>
			</tr>
			<?php endif ?>
			<tr>
				<td class="col-md-3 col-sm-2 hidden-xs">
					<?php echo NeoFrag()->module->get_profile(!empty($data['user_id']) ? $data['user_id'] : $this->user('user_id')) ?>
				</td>
				<td class="text-left col-md-9 col-sm-10">
					<div class="form-group">
						<textarea class="form-control editor" name="<?php echo $data['form_id'] ?>[message]" rows="10"><?php echo isset($data['post']['message']) ? $data['post']['message'] : (isset($data['message']) ? $data['message'] : '') ?></textarea>
					</div>
					<?php if (!empty($data['forum_id']) && $this->access('forum', 'category_announce', $data['category_id'])): ?>
					<div class="checkbox">
						<label><input type="checkbox" name="<?php echo $data['form_id'] ?>[announce][]"<?php if (!empty($data['post']['announce']) && in_array('on', $data['post']['announce'])) echo ' checked="checked"' ?> /> <?php echo $this->lang('Mettre en annonce') ?></label>
					</div>
					<?php endif ?>
					<?php if (!empty($data['forum_id'])): ?>
					<a href="<?php echo url($this->session->get_back() ?: 'forum/'.$data['forum_id'].'/'.url_title($data['title'])) ?>" class="btn btn-default"><?php echo $this->lang('Retour') ?></a>
					<button type="submit" class="btn btn-primary"><?php echo $this->lang('Poster le sujet') ?></button>
					<?php elseif (!empty($data['topic_id'])): ?>
					<a href="<?php echo url($this->session->get_back() ?: 'forum/topic/'.$data['topic_id'].'/'.url_title($data['title'])) ?>" class="btn btn-default"><?php echo $this->lang('Retour') ?></a>
					<button type="submit" class="btn btn-primary"><?php echo $this->lang($data['is_topic'] ? 'modify_topic' : 'modify_message') ?></button>
					<?php else: ?>
					<button type="submit" class="btn btn-primary"><?php echo $this->lang('Répondre au sujet') ?></button>
					<?php endif ?>
				</td>
			</tr>
		</tbody>
	</table>
</form>
