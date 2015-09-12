<form target="live-editor-iframe" action="<?php echo url(); ?>" method="post">
	<input type="hidden" name="live_editor" value="<?php echo $live_editor = $NeoFrag->session('live_editor') ?: NeoFrag::WIDGETS; ?>" />
	<nav class="live-editor-navbar navbar navbar-fixed-top">
		<?php echo icon('fa-floppy-o live-editor-save'); ?>
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="<?php echo url('live-editor.html'); ?>"></a>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?php echo url(); ?>" class="btn-danger"><?php echo icon('fa-power-off'); ?></a></li>
				</ul>
				<div class="navbar-form navbar-right btn-group">
					<button type="button" class="live-editor-screen dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<?php echo icon('fa-desktop'); ?>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><button type="button" class="live-editor-screen active" data-width="100%" data-toggle="tooltip" data-placement="bottom" title="Ordinateur"><?php echo icon('fa-desktop'); ?></button></li>
						<li><button type="button" class="live-editor-screen" data-width="992px" data-toggle="tooltip" data-placement="bottom" title="Tablette paysage"><?php echo icon('fa-tablet fa-rotate-270'); ?></button></li>
						<li><button type="button" class="live-editor-screen" data-width="768px" data-toggle="tooltip" data-placement="bottom" title="Tablette portrait"><?php echo icon('fa-tablet'); ?></button></li>
						<li><button type="button" class="live-editor-screen" data-width="400px" data-toggle="tooltip" data-placement="bottom" title="Smartphone"><?php echo icon('fa-mobile'); ?></button></li>
					</ul>
				</div>
				<p class="navbar-text navbar-right hidden-xs hidden-sm">Simuler l'affichage</p>
				<div class="navbar-form navbar-right">
					<button type="button" class="live-editor-mode<?php echo $live_editor & NeoFrag::ZONES ? ' active' : ''; ?>" data-mode="<?php echo NeoFrag::ZONES; ?>"><?php echo icon('fa-square-o'); ?> Zones</button>
					<button type="button" class="live-editor-mode<?php echo $live_editor & NeoFrag::ROWS ? ' active' : ''; ?>" data-mode="<?php echo NeoFrag::ROWS; ?>"><?php echo icon('fa-columns fa-rotate-270'); ?></i> Rows</button>
					<button type="button" class="live-editor-mode<?php echo $live_editor & NeoFrag::COLS ? ' active' : ''; ?>" data-mode="<?php echo NeoFrag::COLS; ?>"><?php echo icon('fa-columns'); ?> Cols</button>
					<button type="button" class="live-editor-mode active" data-mode="<?php echo NeoFrag::WIDGETS; ?>"><?php echo icon('fa-th-large'); ?> Widgets</button>
				</div>
				<p class="navbar-text navbar-right hidden-xs hidden-sm">Gestion du contenu</p>
			</div>
		</div>
	</nav>
</form>
<div class="live-editor-styles-row">
	<?php echo $data['styles_row']; ?>
</div>
<div class="live-editor-styles-widget">
	<?php echo $data['styles_widget']; ?>
</div>
<div class="live-editor-iframe">
	<iframe name="live-editor-iframe" src=""></iframe>
</div>