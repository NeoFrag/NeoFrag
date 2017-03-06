<form class="form-inline text-right" action="<?php echo url('search'); ?>" method="get">
	<div class="input-group">
		<input type="text" class="form-control" name="q" placeholder="<?php echo $this->lang('search...'); ?>" />
		<span class="input-group-btn">
			<button class="btn btn-default" type="submit"><?php echo icon('fa-search'); ?></button>
		</span>
	</div>
</form>