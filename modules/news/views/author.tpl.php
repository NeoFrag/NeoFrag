<div class="media">
	<div class="media-left">
		<?php echo $this->user->avatar($avatar, $sex, $user_id, $username) ?>
	</div>
	<div class="media-body">
		<div><?php echo $this->user->link($user_id, $username) ?></div>
		<p>
			<small><?php echo icon('fa-circle '.($online ? 'text-green' : 'text-gray')).' '.$this->lang($admin ? 'admin' : 'member').' '.$this->lang($online ? 'online' : 'offline') ?></small>
		</p>
	</div>
</div>
<?php if (!empty($quote)): ?>
<br />
<blockquote>
	<?php echo $quote ?>
</blockquote>
<?php endif ?>
