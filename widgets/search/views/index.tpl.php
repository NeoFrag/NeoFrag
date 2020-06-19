<form class="form-inline <?php echo !empty($align) ? $align : 'float-right' ?>" action="<?php echo url('search') ?>" method="get">
	<div class="input-group input-group-sm">
		<input type="text" class="form-control" name="q" placeholder="<?php echo $this->lang('Rechercher...') ?>" />
		<span class="input-group-append">
			<button class="btn btn-light" type="submit"><?php echo icon('fas fa-search') ?></button>
		</span>
	</div>
</form>
