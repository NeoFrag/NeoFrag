<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<form action="" method="post">
			<div class="input-group">
				<input type="text" class="form-control input-lg" name="a86e16bac4c992732c3f7c6f1fdd159b[keywords]" value="<?php echo utf8_htmlentities($data['keywords']); ?>" placeholder="<?php echo i18n('search_for'); ?>" />
				<span class="input-group-btn btn-group-lg">
					<button class="btn btn-primary btn-lg" type="submit"><?php echo icon('fa-search').' '.i18n('search'); ?></button>
				</span>
			</div>
		</form>
	</div>
</div>
<?php if ($data['keywords'] !== '' && !$data['results']): ?>
<h3 class="text-center"><?php echo i18n('no_results_for', $data['keywords']); ?></b></h3>
<?php endif; ?>
