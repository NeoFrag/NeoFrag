<form class="form-inline pull-right mt-3" action="<?php echo url('search') ?>" method="get">
	<div class="input-group input-group-sm">
		<input type="text" class="form-control" name="q" placeholder="<?php echo $this->lang('Rechercher...') ?>" />
		<span class="input-group-append">
			<button class="btn btn-default" type="submit"><?php echo icon('fa-search') ?></button>
		</span>
	</div>
</form>
