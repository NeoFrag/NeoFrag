<?php if ($NeoFrag->user()): ?>
<div class="media">
	<div class="media-left">
		<?php echo $NeoFrag->user->avatar(); ?>
	</div>
	<div class="media-body">
		<form action="" method="post">
			<input type="hidden" name="<?php echo $data['form_id']; ?>[comment_id]" value="" />
			<div class="form-group">
				<label for="<?php echo $data['form_id']; ?>[comment]"><?php echo i18n('my_comment'); ?></label>
				<textarea name="<?php echo $data['form_id']; ?>[comment]" class="form-control" rows="3"></textarea>
			</div>
			<button type="submit" class="btn btn-primary"><?php echo i18n('send'); ?></button>
		</form>
	</div>
</div>
<?php else: ?>
	<div class="alert alert-danger no-margin" role="alert">
		<?php echo icon('fa-ban').' '.i18n('comment_unlogged'); ?>
	</div>
<?php endif; ?>
