<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-9">
						<ul class="list-inline my-0">
							<li class="list-inline-item"><?php echo icon('fas fa-filter') ?> Filtrer :</li>
							<li class="list-inline-item"><a href="#" data-filter="all"><?php echo $this->lang('Tous') ?></a></li>
							<li class="list-inline-item"><a href="#" data-filter=".addon-module"><?php echo $this->lang('Modules') ?></a></li>
							<li class="list-inline-item"><a href="#" data-filter=".addon-theme"><?php echo $this->lang('Thèmes') ?></a></li>
							<li class="list-inline-item"><a href="#" data-filter=".addon-widget"><?php echo $this->lang('Widgets') ?></a></li>
							<li class="list-inline-item"><a href="#" data-filter=".addon-language"><?php echo $this->lang('Langues') ?></a></li>
							<li class="list-inline-item"><a href="#" data-filter=".addon-authenticator"><?php echo $this->lang('Authentificateurs') ?></a></li>
						</ul>
					</div>
					<div class="col-3 text-right">
						<ul class="list-inline my-0">
							<li class="list-inline-item"><a href="#" data-filter=".activated"><?php echo $this->lang('Actifs') ?></a></li>
							<li class="list-inline-item"><a href="#" data-filter=".deactivated"><?php echo $this->lang('Inactifs') ?></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="addons" class="row">
	<?php foreach ($addons as $addon): ?>
		<div class="col-12 col-lg-3 col-md-4 col-sm-4 mix <?php echo ($addon->type ? 'addon-'.$addon->type->name : 'addon').' '.($addon->addon()->is_enabled() ? 'activated' : 'deactivated') ?>">
			<div class="card">
				<div class="card-body">
					<div class="dropdown">
						<a href="#" class="fas fa-cog dropdown-toggle" data-toggle="dropdown"></a>
						<div class="dropdown-menu">
							<?php foreach ($addon->addon()->__actions as $name => $action): ?>
								<?php if (list($title, $icon, $color, $modal) = $action): ?>
									<?php $url = url('admin/addons/'.$name.'/'.$addon->url()) ?>
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
						<div class="addon"><?php echo icon(isset($addon->addon()->info()->icon) ? $addon->addon()->info()->icon : $label[2]) ?></div>
					<?php endif ?>
					<h6<?php if (!$addon->addon()->is_enabled()) echo ' class="disabled"' ?>><?php echo $addon->addon()->info()->title ?></h6>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</div>
