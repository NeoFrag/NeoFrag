<div class="media">
	<?php echo $this->user->avatar($avatar, $sex, $user_id, $username) ?>
	<div class="media-body">
		<div><?php echo $this->user->link($user_id, $username) ?></div>
		<p>
			<small><?php echo icon('fa-circle '.($online ? 'text-green' : 'text-gray')).' '.$this->lang($admin ? 'Administrateur' : 'Membre').' '.$this->lang($online ? 'en ligne' : 'hors ligne') ?></small>
		</p>
	</div>
</div>
<?php if (!empty($quote)): ?>
<br />
<blockquote>
	<?php echo $quote ?>
</blockquote>
<?php endif ?>
