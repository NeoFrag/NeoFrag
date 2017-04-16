<div class="row">
	<div class="col-md-12">
		<button type="button" class="btn btn-default btn-outline control" data-filter="all">Tous</button>
		<button type="button" class="btn btn-primary btn-outline control" data-toggle=".module">Modules</button>
		<button type="button" class="btn btn-success btn-outline control" data-toggle=".theme">Thèmes</button>
		<button type="button" class="btn btn-warning btn-outline control" data-toggle=".widget">Widgets</button>
		<button type="button" class="btn btn-danger  btn-outline control" data-toggle=".language">Langues</button>
		<button type="button" class="btn btn-info    btn-outline control" data-toggle=".library">Librairies</button>
		<button type="button" class="btn btn-primary btn-outline control" data-toggle=".package">Packages</button>
		<button type="button" class="btn btn-primary btn-outline control" data-toggle=".override">Overrides</button>
	</div>
</div>
<div id="addons" class="row">
	<?php foreach ($addons as $addon): ?>
		<div class="col-lg-1 col-md-2 col-sm-3 col-xs-4 mix <?php echo $addon->type ? $addon->type->name : 'addon' ?>">
			<div class="thumbnail">
				<div class="dropdown">
					<a href="#" class="fa fa-ellipsis-v dropdown-toggle" data-toggle="dropdown"></a>
					<ul class="dropdown-menu">
						<?php foreach ($addon->addon()->__actions() as $action): ?>
							<?php if (list($name, $title, $icon, $color) = $action): ?>
								<li><a href="#" data-action="<?php echo $name ?>"><?php echo $this->html('span')->attr('class', 'text-'.$color)->content(icon($icon)).' '.$title ?></a></li>
							<?php else: ?>
								<li role="separator" class="divider"></li>
							<?php endif ?>
						<?php endforeach ?>
					</ul>
				</div>
				<?php echo $addon->type->label()->icon('') ?>
				<?php if (check_file($path = dirname($addon->addon()->info()->path).'/thumbnail.png')): ?>
					<div class="image" style="background-image: url(<?php echo url($path) ?>);"></div>
				<?php else: ?>
					<div class="icon"><?php echo icon(isset($addon->addon()->info()->icon) ? $addon->addon()->info()->icon : $addon->type->icon()) ?></div>
				<?php endif ?>
				<h6<?php if (!$addon->addon()->is_enabled()) echo ' class="disabled"' ?>><?php echo $addon->addon()->title() ?></h6>
			</div>
		</div>
	<?php endforeach ?>
</div>
