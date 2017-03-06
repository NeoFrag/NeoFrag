<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<form action="<?php echo url('search'); ?>" method="get">
			<div class="input-group">
				<input type="text" class="form-control input-lg" name="q" value="<?php echo utf8_htmlentities($data['keywords']); ?>" placeholder="<?php echo $this->lang('search_for'); ?>" />
				<span class="input-group-btn btn-group-lg">
					<button class="btn btn-primary btn-lg" type="submit"><?php echo icon('fa-search').' '.$this->lang('search'); ?></button>
				</span>
			</div>
		</form>
	</div>
</div>
<?php if ($data['keywords'] !== '' && !$data['results']): ?>
<h3 class="text-center"><?php echo $this->lang('no_results_for', utf8_htmlentities($data['keywords'])); ?></b></h3>
<?php endif; ?>
