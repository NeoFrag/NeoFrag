<div class="forum-profile">
	<?php if (!empty($user_id)): ?>
		<h5><?php echo $this->user->link($user_id, $username) ?></h5>
		<?php echo $this->module('user')->model2('user', $user_id)->avatar()->append_attr('class', 'm-auto') ?>
		<p><i><?php echo $this->lang('%d sujet|%d sujets', $topics, $topics).', '.$this->lang('%d réponse|%d réponses', $replies, $replies) ?></i></p>
		<div class="forum-groups">
			<?php echo $this->groups->user_groups($user_id) ?>
		</div>
	<?php else: ?>
		<h5><i><?php echo $this->lang('Visiteur') ?></i></h5>
		<?php echo $this->module('user')->model2('user')->avatar() ?>
	<?php endif ?>
</div>
