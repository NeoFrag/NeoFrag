<div role="tabpanel">
	<a id="link-delete" class="btn btn-danger pull-right" href="#" data-toggle="popover" title="Supprimer un lien" data-content="Déplacez un lien ici pour le supprimer" data-placement="top"><?php echo icon('fa-trash-o'); ?> Supprimer</a>
	<ul id="navigation-tabs" class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#links" aria-controls="links" role="tab" data-toggle="tab">Liens</a></li>
		<li role="presentation"><a href="#add-link" aria-controls="add-link" role="tab" data-toggle="tab"><?php echo icon('fa-plus'); ?> Ajouter</a></li>
		<li role="presentation"><a href="#navigation-options" aria-controls="navigation-options" role="tab" data-toggle="tab"><?php echo icon('fa-cogs'); ?> Options</a></li>
	</ul>
	<div class="tab-content">
		<div id="links" class="tab-pane active" role="tabpanel">
			<ul class="nav nav-pills nav-stacked">
			<?php foreach ($data['links'] as $link): ?>
				<li>
					<input type="hidden" name="settings[title][]" value="<?php echo $link['title']; ?>" />
					<input type="hidden" name="settings[url][]" value="<?php echo $link['url']; ?>" />
					<a href="#"><?php echo $link['title']; ?></a>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div id="add-link" class="tab-pane" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-title" class="col-sm-3 control-label">Titre</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="settings-title" placeholder="Titre" />
					</div>
				</div>
				<div class="form-group">
					<label for="settings-url" class="col-sm-3 control-label">Chemin</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="settings-url" placeholder="Chemin" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button class="btn btn-primary">Ajouter</button>
					</div>
				</div>
			</div>
		</div>
		<div id="navigation-options" class="tab-pane" role="tabpanel">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="settings-title" class="col-sm-3 control-label">Orientation</label>
					<div class="col-sm-5">
						<label class="radio-inline">
							<input type="radio" name="settings[display]" value="0"<?php if ($data['display'] == 0) echo ' checked="checked"'; ?>> Verticale
						</label>
						<label class="radio-inline">
							<input type="radio" name="settings[display]" value="1"<?php if ($data['display'] == 1) echo ' checked="checked"'; ?>> Horizontale
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#links .nav').sortable({
			connectWith: '#link-delete',
			cursor: 'move',
			intersect: 'pointer',
			revert: true
		});
		
		$('#link-delete').droppable({
			accept: '#links .nav li',
			tolerance: 'pointer',
			drop: function(event, ui){
				ui.draggable.remove();
			}
		});
		
		$('#add-link button').click(function(){
			var title = $('#settings-title').val();
			var url   = $('#settings-url').val();
			
			if (title && url){
				$('#navigation-tabs a:first').tab('show');
				
				$('<li>\
						<input type="hidden" name="settings[title][]" value="'+title+'" />\
						<input type="hidden" name="settings[url][]" value="'+url+'" />\
						<a href="#">'+title+'</a>\
					</li>').appendTo('#links .nav');
					
				$('#settings-title').parents('.form-group:first').removeClass('has-error');
				$('#settings-url').parents('.form-group:first').removeClass('has-error');

				$('#settings-title').val('');
				$('#settings-url').val('');
			}
			else {
				if (!title){
					$('#settings-title').parents('.form-group:first').addClass('has-error');
				}
				else {
					$('#settings-title').parents('.form-group:first').removeClass('has-error');
				}
			
				if (!url){
					$('#settings-url').parents('.form-group:first').addClass('has-error');
				}
				else {
					$('#settings-url').parents('.form-group:first').removeClass('has-error');
				}
			}
			
			return false;
		});
	});
</script>
