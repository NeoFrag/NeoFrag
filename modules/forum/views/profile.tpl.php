<div class="forum-profile">
	<?php if (!empty($user_id)): ?>
		<h4><?php echo $this->user->link($user_id, $username) ?></h4>
		<p><?php echo icon('fa-circle '.($online ? 'text-green' : 'text-gray')).' '.$this->lang($admin ? 'Administrateur' : 'Membre').' '.$this->lang($online ? 'en ligne' : 'hors ligne') ?></p>
		<?php echo NeoFrag()->model2('user', $user_id)->avatar() ?>
		<p><i><?php echo $this->lang('<b>%d</b> sujet|<b>%d</b> sujets', $topics, $topics).' '.$this->lang('<b>%d</b> réponse|<b>%d</b> réponses', $replies, $replies) ?></i></p>
		<div class="forum-groups">
			<?php echo $this->groups->user_groups($user_id) ?>
		</div>
	<?php else: ?>
		<h4><i><?php echo $this->lang('Visiteur') ?></i></h4>
		<?php echo NeoFrag()->model2('user')->avatar() ?>
	<?php endif ?>
</div>
