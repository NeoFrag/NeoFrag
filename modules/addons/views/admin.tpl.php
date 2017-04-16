<?php /* //TODO <div class="row">
	<div class="col-12">
		<button type="button" class="btn btn-default btn-outline control" data-filter="all">Tous</button>
		<button type="button" class="btn btn-primary btn-outline control" data-toggle=".module">Modules</button>
		<button type="button" class="btn btn-success btn-outline control" data-toggle=".theme">Thèmes</button>
		<button type="button" class="btn btn-warning btn-outline control" data-toggle=".widget">Widgets</button>
		<button type="button" class="btn btn-danger  btn-outline control" data-toggle=".language">Langues</button>
		<button type="button" class="btn btn-info    btn-outline control" data-toggle=".authenticator">Authentificateurs</button>
	</div>
</div>*/ ?>
<div id="addons" class="row">
	<?php foreach ($addons as $addon): ?>
		<div class="col-4 col-md-2 col-sm-3 mix <?php echo $addon->type ? $addon->type->name : 'addon' ?>">
			<div class="card">
				<div class="card-body">
					<div class="dropdown">
						<a href="#" class="fa fa-ellipsis-v dropdown-toggle" data-toggle="dropdown"></a>
						<div class="dropdown-menu">
							<?php foreach ($addon->addon()->__actions as $name => $action): ?>
								<?php if (list($title, $icon, $color, $modal) = $action): ?>
									<?php $url = url('admin/addons/'.$name.'/'.$addon->id.'/'.url_title($addon->name)) ?>
									<a class="dropdown-item" <?php echo $modal ? 'href="#" data-modal-ajax="'.$url.'"' : 'href="'.$url.'"' ?>"><?php echo $this->html('span')->attr('class', 'text-'.$color)->content(icon($icon)).' '.$title ?></a>
								<?php else: ?>
									<div class="dropdown-divider"></div>
								<?php endif ?>
							<?php endforeach ?>
						</div>
					</div>
					<?php $label = $addon->controller()->__label ?>
					<?php echo $this->label($label[1], '', $label[3]) ?>
					<?php if ($path = $addon->addon()->__path('', 'thumbnail.png')): ?>
						<div class="image" style="background-image: url(<?php echo url($path) ?>);"></div>
					<?php else: ?>
						<div class="icon"><?php echo icon(isset($addon->addon()->info()->icon) ? $addon->addon()->info()->icon : icon($label[2])) ?></div>
					<?php endif ?>
					<h6<?php if (!$addon->addon()->is_enabled()) echo ' class="disabled"' ?>><?php echo $addon->addon()->info()->title ?></h6>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>
