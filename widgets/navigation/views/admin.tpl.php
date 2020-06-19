<a id="link-delete" class="btn btn-outline-danger float-right" href="#" data-toggle="popover" title="<?php echo $this->lang('Supprimer un lien') ?>" data-content="<?php echo $this->lang('Déplacez un lien ici pour le supprimer') ?>" data-placement="top"><?php echo icon('far fa-trash-alt').$this->lang('Supprimer') ?></a>
<ul class="nav nav-pills" id="pills-tab" role="tablist">
	<li class="nav-item"><a class="nav-link active" id="pills-links-tab" data-toggle="pill" href="#pills-links" role="tab" aria-controls="pills-links" aria-selected="true"><?php echo icon('fas fa-cogs').' Liens' ?></a></li>
	<li class="nav-item"><a class="nav-link" id="pills-add-tab" data-toggle="pill" href="#pills-add" role="tab" aria-controls="pills-add" aria-selected="false"><?php echo icon('fas fa-plus').' Ajouter' ?></a></li>
</ul>
<div class="tab-content border-light" id="pills-tabContent">
	<div class="tab-pane fade show active" id="pills-links" role="tabcard" aria-labelledby="pills-links-tab">
		<ul class="list-group mb-3">
		<?php foreach (isset($links) ? $links : [] as $link): ?>
			<li class="list-group-item">
				<input type="hidden" name="settings[title][]" id="edit-title" value="<?php echo $link['title'] ?>" />
				<input type="hidden" name="settings[url][]" id="edit-url" value="<?php echo $link['url'] ?>" />
				<input type="hidden" name="settings[target][]" id="edit-target" value="<?php echo !empty($link['target']) ? $link['target'] : '_parent' ?>" />
				<ul class="list-inline m-0">
					<li class="list-inline-item"><a href="#" class="move-link" data-toggle="tooltip" title="<?php echo $this->lang('Ordonner') ?>"><?php echo icon('fas fa-arrows-alt-v') ?></a></li>
					<li class="list-inline-item"><span data-toggle="tooltip" title="<?php echo $link['url'] ?>"><?php echo icon('fas fa-link') ?></span></li>
					<li class="list-inline-item"><?php echo $link['title'] ?></li>
				</ul>
			</li>
		<?php endforeach ?>
		</ul>
	</div>
	<div class="tab-pane fade" id="pills-add" role="tabcard" aria-labelledby="pills-add-tab">
		<div id="add-link">
			<div class="card px-2 py-3">
				<div class="card-heading">
					<a class="type-collapse" role="button" data-toggle="collapse" data-parent="#add-link" href="#type-module" aria-controls="type-module">
						<?php echo icon('fas fa-edit') ?> <b>Lien vers un module</b>
					</a>
				</div>
				<div id="type-module" class="card-collapse collapse" role="tabcard">
					<?php
					$modules = [];

					foreach (NeoFrag()->model2('addon')->get('module') as $module)
					{
						if (@$module->controller('index') && !in_array($module->name, ['live_editor', 'pages']))
						{
							$modules[$module->info()->name] = $module->info()->title;
						}
					}

					array_natsort($modules);

					$modules = array_merge([
						'index' => NeoFrag()->lang('Accueil')
					], $modules);
					?>
					<div class="list-group mt-3">
						<?php foreach ($modules as $name => $title): ?>
							<a href="#" class="list-group-item link-item" data-link-title="<?php echo $title ?>" data-link-url="<?php echo $name ?>"><?php echo $title ?></a>
						<?php endforeach ?>
					</div>
					<div class="card-body" id="add-link">
						<div class="form-group">
							<label for="settings-title" class="col-sm-3 control-label"><?php echo $this->lang('Titre') ?></label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="settings-title" value="" placeholder="<?php echo $this->lang('Titre') ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="settings-url" class="col-sm-3 control-label"><?php echo $this->lang('Chemin') ?></label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="settings-url" value="" placeholder="<?php echo $this->lang('Chemin') ?>" disabled="disabled" />
							</div>
						</div>
						<div class="form-group">
							<label for="settings-target" class="col-sm-3 control-label"><?php echo $this->lang('Cible') ?></label>
							<div class="col-sm-5">
								<select class="form-control" id="settings-target">
									<option value="_parent"><?php echo $this->lang('Même fenêtre') ?></option>
									<option value="_blank"><?php echo $this->lang('Nouvelle fenêtre') ?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-5">
								<button class="btn btn-primary"><?php echo $this->lang('Ajouter') ?></button>
								<a class="btn btn-secondary cancel-link"><?php echo icon('fas fa-times').' '.$this->lang('Annuler') ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$pages = $this->db	->select('p.page_id', 'p.name', 'p.published', 'pl.title', 'pl.subtitle')
							->from('nf_pages p')
							->join('nf_pages_lang pl', 'p.page_id = pl.page_id')
							->where('p.published', TRUE)
							->where('pl.lang', $this->config->lang->info()->name)
							->order_by('pl.title ASC')
							->get();

			if ($pages): ?>
			<div class="card px-2 py-3" style="margin-top: 5px !important;">
				<div class="card-heading">
					<a class="type-collapse" role="button" data-toggle="collapse" data-parent="#add-link" href="#type-page" aria-controls="type-page">
						<?php echo icon('far fa-file-alt') ?> <b>Lien vers une page</b>
					</a>
				</div>
				<div id="type-page" class="card-collapse collapse" role="tabcard">
					<div class="list-group mt-3">
						<?php foreach ($pages as $page): ?>
							<a href="#" class="list-group-item link-item" data-link-title="<?php echo $page['title'] ?>" data-link-url="<?php echo $page['name'] ?>"><?php echo $page['title'] ?></a>
						<?php endforeach ?>
					</div>
					<div class="card-body" id="add-link">
						<div class="form-group">
							<label for="settings-title" class="col-sm-3 control-label"><?php echo $this->lang('Titre') ?></label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="settings-title" value="" placeholder="<?php echo $this->lang('Titre') ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="settings-url" class="col-sm-3 control-label"><?php echo $this->lang('Chemin') ?></label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="settings-url" value="" placeholder="<?php echo $this->lang('Chemin') ?>" disabled="disabled" />
							</div>
						</div>
						<div class="form-group">
							<label for="settings-target" class="col-sm-3 control-label"><?php echo $this->lang('Cible') ?></label>
							<div class="col-sm-5">
								<select class="form-control" id="settings-target">
									<option value="_parent"><?php echo $this->lang('Même fenêtre') ?></option>
									<option value="_blank"><?php echo $this->lang('Nouvelle fenêtre') ?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-5">
								<button class="btn btn-primary"><?php echo $this->lang('Ajouter') ?></button>
								<a class="btn btn-secondary cancel-link"><?php echo icon('fas fa-times').' '.$this->lang('Annuler') ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif ?>
			<div class="card px-2 py-3" style="margin-top: 5px !important;">
				<div class="card-heading">
					<a class="type-collapse link-item" role="button" data-link-title="" data-link-url="" data-toggle="collapse" data-parent="#add-link" href="#type-custom" aria-controls="type-custom">
						<?php echo icon('fas fa-link') ?> <b>Lien personnalisé</b>
					</a>
				</div>
				<div id="type-custom" class="card-collapse collapse" role="tabcard">
					<div class="card-body">
						<div class="form-group">
							<label for="settings-title" class="col-sm-3 control-label"><?php echo $this->lang('Title') ?></label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="settings-title" value="" placeholder="<?php echo $this->lang('Title') ?>" />
							</div>
						</div>
						<div class="form-group">
							<label for="settings-url" class="col-sm-3 control-label"><?php echo $this->lang('Chemin') ?></label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="settings-url" value="" placeholder="http://..." />
							</div>
						</div>
						<div class="form-group">
							<label for="settings-target" class="col-sm-3 control-label"><?php echo $this->lang('Cible') ?></label>
							<div class="col-sm-5">
								<select class="form-control" id="settings-target">
									<option value="_parent"><?php echo $this->lang('Même fenêtre') ?></option>
									<option value="_blank"><?php echo $this->lang('Nouvelle fenêtre') ?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-5">
								<button class="btn btn-primary"><?php echo $this->lang('Ajouter') ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		//OK
		$('#pills-links .list-group').sortable({
			connectWith: '#link-delete',
			cursor: 'move',
			intersect: 'pointer',
			revert: true
		});

		$('#link-delete').droppable({
			accept: '#pills-links .list-group li',
			tolerance: 'pointer',
			drop: function(event, ui){
				ui.draggable.remove();
			}
		});

		var active_id = '';
		$('a.type-collapse').on('click', function(){
			active_id = $(this).attr('href');

			$('.card-collapse .card-body').each(function(){
				$(this).hide();
			});

			$('.form-group').each(function(){
				$(this).removeClass('has-error');
			});

			$(active_id+' .list-group').show();
		});

		$('#add-link .link-item').on('click', function(){
			$(active_id+' #settings-title').val($(this).data('link-title'));
			$(active_id+' #settings-url').val($(this).data('link-url'));
			$(active_id+' .list-group').hide();
			$(active_id+' .card-body').show();
		});

		/*
		$(document).on('click', '.list-group li a.edit-link', function() {
			var title     = $(this).parent().find('#edit-title').val();
			var url       = $(this).parent().find('#edit-url').val();
			var target    = $(this).parent().find('#edit-target').val();

			$('#pills-links .form-edit #settings-title').val(title);
			$('#pills-links .form-edit #settings-url').val(url);
			$('#pills-links .form-edit #settings-target option[value="'+target+'"]').prop('selected', true);

			$('#pills-links .list-group').hide();
			$('#pills-links .form-edit').show();
		});
		*/

		$('.cancel-link').click(function(){
			$(active_id+' .card-body').hide();
			$(active_id+ '.list-group').show();

			active_id = '';
		});

		$('#add-link .btn-primary').click(function(){
			var title  = $(active_id+' #settings-title').val();
			var url    = $(active_id+' #settings-url').val();
			var target = $(active_id+' #settings-target').val();

			if (title && url && target){
				$('#pills-links-tab, #pills-links').addClass('active show');
				$('#pills-add-tab, #pills-add').removeClass('active show');

				$('<li class="list-group-item ui-sortable-handle">\
						<input type="hidden" name="settings[title][]" id="edit-title" value="'+title+'" />\
						<input type="hidden" name="settings[url][]" id="edit-url" value="'+url+'" />\
						<input type="hidden" name="settings[target][]" id="edit-target" value="'+target+'" />\
						<ul class="list-inline m-0">\
							<li class="list-inline-item"><a href="#" class="move-link" data-toggle="tooltip" title="<?php echo $this->lang('Ordonner') ?>"><?php echo icon('fas fa-arrows-alt-v') ?></a></li>\
							<li class="list-inline-item"><span data-toggle="tooltip" title="'+url+'"><?php echo icon('fas fa-link') ?></span></li>\
							<li class="list-inline-item">'+title+'</li>\
						</ul>\
					</li>').appendTo('#pills-links .list-group');

				$(active_id+' #settings-title').parents('.form-group:first').removeClass('is-invalid');
				$(active_id+' #settings-url').parents('.form-group:first').removeClass('is-invalid');
				$(active_id+' #settings-target').parents('.form-group:first').removeClass('is-invalid');

				$(active_id+' #settings-title').val('');
				$(active_id+' #settings-url').val('');

				$(active_id+' .card-body').hide();
				$(active_id+' .list-group').show();
			}
			else {
				if (!title){
					$(active_id+' #settings-title').parents('.form-group:first').addClass('is-invalid');
				}
				else {
					$(active_id+' #settings-title').parents('.form-group:first').removeClass('is-invalid');
				}

				if (!url){
					$(active_id+' #settings-url').parents('.form-group:first').addClass('is-invalid');
				}
				else {
					$(active_id+' #settings-url').parents('.form-group:first').removeClass('is-invalid');
				}

				if (!target){
					$(active_id+' #settings-target').parents('.form-group:first').addClass('is-invalid');
				}
				else {
					$(active_id+' #settings-target').parents('.form-group:first').removeClass('is-invalid');
				}
			}

			return false;
		});
	});
</script>
