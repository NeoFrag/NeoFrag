<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<form action="<?php echo url('search') ?>" method="get">
			<div class="input-group">
				<input type="text" class="form-control input-lg" name="q" value="<?php echo utf8_htmlentities($data['keywords']) ?>" placeholder="<?php echo $this->lang('Rechercher un ou plusieurs termes sur notre site') ?>" />
				<span class="input-group-btn btn-group-lg">
					<button class="btn btn-primary btn-lg" type="submit"><?php echo icon('fa-search').' '.$this->lang('Rechercher') ?></button>
				</span>
			</div>
		</form>
	</div>
</div>
<?php if ($data['keywords'] !== '' && !$data['results']): ?>
<h3 class="text-center"><?php echo $this->lang('Aucun résultat trouvé pour <b>%s</b>', utf8_htmlentities($data['keywords'])) ?></b></h3>
<?php endif ?>
