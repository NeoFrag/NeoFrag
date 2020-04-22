<div class="media">
	<?php echo $this->module('user')->model2('user', $user_id)->avatar() ?>
	<div class="media-body">
		<div><?php echo $this->user->link($user_id, $username) ?></div>
		<p>
			<small><?php echo icon('fas fa-circle '.($online ? 'text-green' : 'text-gray')).' '.$this->lang($admin ? 'Administrateur' : 'Membre').' '.$this->lang($online ? 'en ligne' : 'hors ligne') ?></small>
		</p>
	</div>
</div>
<?php if (!empty($quote)): ?>
<br />
<blockquote>
	<?php echo $quote ?>
</blockquote>
<?php endif ?>
