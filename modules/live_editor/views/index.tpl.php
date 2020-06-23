<form target="live-editor-iframe" action="<?php echo url() ?>" method="post">
	<input type="hidden" name="live_editor" value="<?php echo $live_editor = $this->session('live_editor') ?: $this->output->live_editor() ^ \NF\NeoFrag\Core\Output::WIDGETS ?>" />
	<nav class="live-editor-navbar navbar navbar-expand-lg navbar-light bg-light py-2">
		<a class="navbar-brand" href="<?php echo url('admin/live-editor') ?>"><?php echo icon('fas fa-desktop') ?> <b>Live</b><span data-typer="Editor"></span></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#modules-links-collapse" aria-controls="modules-links-collapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="modules-links-collapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item dropdown">
					<a class="nav-link" href="#" id="navbarDropdownModules" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php echo icon('fas fa-link').' '.$this->lang('Navigation').' '.icon('fas fa-angle-down') ?>
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownModules">
						<?php foreach ($modules as $name => $title): ?>
							<a class="dropdown-item" href="<?php echo url($name) ?>"><?php echo $title ?></a>
						<?php endforeach ?>
					</div>
				</li>
				<li class="nav-item">
					<span class="d-block" id="live-editor-map"><?php echo icon('fas fa-spinner fa-spin').' '.$this->lang('Chargement en cours...') ?></span>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<div class="btn-group">
						<button type="button" class="btn btn-light live-editor-mode<?php echo $live_editor & \NF\NeoFrag\Core\Output::ZONES ? ' active' : '' ?>" data-mode="<?php echo \NF\NeoFrag\Core\Output::ZONES ?>"><?php echo icon('far fa-square').' '.$this->lang('Zones') ?></button>
						<button type="button" class="btn btn-light live-editor-mode<?php echo $live_editor & \NF\NeoFrag\Core\Output::ROWS ? ' active' : '' ?>" data-mode="<?php echo \NF\NeoFrag\Core\Output::ROWS ?>"><?php echo icon('fas fa-columns fa-rotate-270').' '.$this->lang('Lignes') ?></button>
						<button type="button" class="btn btn-light live-editor-mode<?php echo $live_editor & \NF\NeoFrag\Core\Output::COLS ? ' active' : '' ?>" data-mode="<?php echo \NF\NeoFrag\Core\Output::COLS ?>"><?php echo icon('fas fa-columns').' '.$this->lang('Colonnes') ?></button>
						<button type="button" class="btn btn-light live-editor-mode active" data-mode="<?php echo \NF\NeoFrag\Core\Output::WIDGETS ?>"><?php echo icon('fas fa-th-large').' '.$this->lang('Widgets') ?></button>
					</div>
				</li>
				<li class="nav-item dropdown ml-2">
					<a class="nav-link btn btn-light live-editor-screen" href="#" id="navbarDropdownScreen" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php echo icon('fas fa-desktop').icon('fas fa-angle-down') ?>
					</a>
					<div class="dropdown-menu screen" aria-labelledby="navbarDropdownScreen">
						<ul class="list-unstyled mx-2">
							<li><button type="button" class="btn btn-light live-editor-screen active" data-width="100%" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang('Ordinateur') ?>"><?php echo icon('fas fa-desktop') ?></button></li>
							<li><button type="button" class="btn btn-light live-editor-screen mt-1" data-width="992px" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang('Tablette paysage') ?>"><?php echo icon('fas fa-tablet-alt fa-rotate-270') ?></button></li>
							<li><button type="button" class="btn btn-light live-editor-screen mt-1" data-width="768px" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang('Tablette portrait') ?>"><?php echo icon('fas fa-tablet-alt') ?></button></li>
							<li><button type="button" class="btn btn-light live-editor-screen mt-1" data-width="400px" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang('Smartphone') ?>"><?php echo icon('fas fa-mobile-alt') ?></button></li>
						</ul>
					</div>
				</li>
				<li class="nav-item">
					<a href="<?php echo url('admin') ?>" class="nav-link"><?php echo $this->label('Tableau de bord', 'fas fa-tachometer-alt') ?></a>
				</li>
				<li class="nav-item">
					<a href="<?php echo url() ?>" class="nav-link text-danger"><?php echo $this->label('Quitter', 'fas fa-power-off') ?></a>
				</li>
			</ul>
		</div>
	</nav>
</form>
<?php echo icon('far fa-save live-editor-save') ?>
<div class="live-editor-styles-row">
	<?php echo $styles_row ?>
</div>
<div class="live-editor-styles-widget">
	<?php echo $styles_widget ?>
</div>
<div class="live-editor-iframe">
	<iframe name="live-editor-iframe" src=""></iframe>
</div>
