<form target="live-editor-iframe" action="{base_url}" method="post">
	<input type="hidden" name="live_editor" value="<?php echo $live_editor = $NeoFrag->session('live_editor') ?: NeoFrag::WIDGETS; ?>" />
	<nav class="live-editor-navbar navbar navbar-fixed-top">
		<i class="fa fa-floppy-o live-editor-save"></i>
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="{base_url}live-editor.html"></a>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="{base_url}" class="btn-danger"><i class="fa fa-power-off"></i></a></li>
				</ul>
				<div class="navbar-form navbar-right btn-group">
					<button type="button" class="live-editor-screen dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-desktop"></i>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><button type="button" class="live-editor-screen active" data-width="100%" data-toggle="tooltip" data-placement="bottom" title="Ordinateur"><i class="fa fa-desktop"></i></button></li>
						<li><button type="button" class="live-editor-screen" data-width="992px" data-toggle="tooltip" data-placement="bottom" title="Tablette paysage"><i class="fa fa-tablet fa-rotate-270"></i></button></li>
						<li><button type="button" class="live-editor-screen" data-width="768px" data-toggle="tooltip" data-placement="bottom" title="Tablette portrait"><i class="fa fa-tablet"></i></button></li>
						<li><button type="button" class="live-editor-screen" data-width="400px" data-toggle="tooltip" data-placement="bottom" title="Smartphone"><i class="fa fa-mobile"></i></button></li>
					</ul>
				</div>
				<p class="navbar-text navbar-right hidden-xs hidden-sm">Simuler l'affichage</p>
				<div class="navbar-form navbar-right">
					<button type="button" class="live-editor-mode<?php echo $live_editor & NeoFrag::ZONES ? ' active' : ''; ?>" data-mode="<?php echo NeoFrag::ZONES; ?>"><i class="fa fa-square-o"></i> Zones</button>
					<button type="button" class="live-editor-mode<?php echo $live_editor & NeoFrag::ROWS ? ' active' : ''; ?>" data-mode="<?php echo NeoFrag::ROWS; ?>"><i class="fa fa-columns fa-rotate-270"></i> Rows</button>
					<button type="button" class="live-editor-mode<?php echo $live_editor & NeoFrag::COLS ? ' active' : ''; ?>" data-mode="<?php echo NeoFrag::COLS; ?>"><i class="fa fa-columns"></i> Cols</button>
					<button type="button" class="live-editor-mode active" data-mode="<?php echo NeoFrag::WIDGETS; ?>"><i class="fa fa-th-large"></i> Widgets</button>
				</div>
				<p class="navbar-text navbar-right hidden-xs hidden-sm">Gestion du contenu</p>
			</div>
		</div>
	</nav>
</form>
<div class="live-editor-styles-row">
	{styles_row}
</div>
<div class="live-editor-styles-widget">
	{styles_widget}
</div>
<div class="live-editor-iframe">
	<iframe name="live-editor-iframe" src=""></iframe>
</div>