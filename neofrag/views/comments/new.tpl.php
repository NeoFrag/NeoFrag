<?php if ($NeoFrag->user()): ?>
<div class="media">
	<div class="media-left">
		<a href="<?php echo url('members/'.$NeoFrag->user('user_id').'/'.url_title($NeoFrag->user('username').'.html')); ?>"><img class="media-object" style="width: 64px; height: 64px;" src="<?php echo $NeoFrag->user->avatar(); ?>" data-toggle="tooltip" title="<?php echo $NeoFrag->user('username'); ?>" alt="" /></a>
	</div>
	<div class="media-body">
		<form action="" method="post">
			<input type="hidden" name="<?php echo $data['form_id']; ?>[comment_id]" value="" />
			<div class="form-group">
				<label for="<?php echo $data['form_id']; ?>[comment]">Mon commentaire</label>
				<textarea name="<?php echo $data['form_id']; ?>[comment]" class="form-control" rows="3"></textarea>
			</div>
			<button type="submit" class="btn btn-primary">Envoyer</button>
		</form>
	</div>
</div>
<?php else: ?>
	<div class="alert alert-danger no-margin" role="alert">
		<?php echo icon('fa-ban'); ?> Vous devez être identifié pour pouvoir poster un commentaire.
	</div>
<?php endif; ?>
