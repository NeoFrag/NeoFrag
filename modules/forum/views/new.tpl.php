<form action="<?php echo url($NeoFrag->config->request_url.(empty($data['forum_id']) && empty($data['is_topic']) ? '#reply' : '')); ?>" method="post">
	<table class="table">
		<tbody class="forum-content">
			<?php if (!empty($data['forum_id']) || !empty($data['is_topic'])): ?>
			<tr>
				<td colspan="2"><input type="text" class="form-control" name="<?php echo $data['form_id']; ?>[title]"<?php if (!empty($data['is_topic'])) echo ' value="'.$data['title'].'"'; ?> placeholder="Titre du sujet" /></td>
			</tr>
			<?php endif; ?>
			<tr>
				<td class="col-md-3 text-center">
					<br />
					<h4 class="no-margin"><?php echo !empty($data['topic_id']) ? $NeoFrag->user->link($data['user_id'], $data['username']) : $NeoFrag->user->link(); ?></h4>
					<p><?php echo icon('fa-circle '.(empty($data['topic_id']) || $data['online'] ? 'text-green' : 'text-gray')).' '.(!empty($data['topic_id']) ? $data['admin'] : $NeoFrag->user('admin') ? 'Admin' : 'Membre').' '.(empty($data['topic_id']) || $data['online'] ? 'en ligne' : 'hors ligne'); ?></p>
					<img class="img-avatar-forum" src="<?php echo !empty($data['topic_id']) ? $NeoFrag->user->avatar($data['avatar'], $data['sex']) : $NeoFrag->user->avatar(); ?>" title="<?php echo !empty($data['topic_id']) ? $data['username'] : $NeoFrag->user('username'); ?>" alt="" />
				</td>
				<td class="text-left col-md-9">
					<div class="form-group">
						<textarea class="form-control editor" name="<?php echo $data['form_id']; ?>[message]" rows="10"><?php if (!empty($data['message'])) echo $data['message']; ?></textarea>
					</div>
					<?php if (!empty($data['forum_id']) && is_authorized('forum', 'category_announce', $data['category_id'])): ?>
					<div class="checkbox">
						<label><input type="checkbox" name="<?php echo $data['form_id']; ?>[announce][]" /> Mettre en annonce</label>
					</div>
					<?php endif; ?>
					<?php if (!empty($data['forum_id'])): ?>
					<a href="<?php echo url($NeoFrag->session->get_back() ?: 'forum/'.$data['forum_id'].'/'.url_title($data['title']).'.html'); ?>" class="btn btn-default">Retour</a>
					<button type="submit" class="btn btn-primary">Poster le sujet</button>
					<?php elseif (!empty($data['topic_id'])): ?>
					<a href="<?php echo url($NeoFrag->session->get_back() ?: 'forum/topic/'.$data['topic_id'].'/'.url_title($data['title']).'.html'); ?>" class="btn btn-default">Retour</a>
					<button type="submit" class="btn btn-primary">Modifier le <?php echo $data['is_topic'] ? 'sujet' : 'message'; ?></button>
					<?php else: ?>
					<button type="submit" class="btn btn-primary">Répondre au sujet</button>
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
	</table>
</form>