<div class="container-fluid">
	<div class="row">
		<nav class="col-md-2 d-none d-md-block sidebar">
			<a class="logo" href="<?php echo url('admin') ?>">
				<svg><use xlink:href="#logo"></use></svg>
				<span class="badge badge-primary"><?php echo NEOFRAG_VERSION ?></span>
			</a>
			<?php if ($update = $this->__caller->update()): ?>
				<div class="row p-4 text-white">
					<div class="col-12 text-center">
						<h6>
							Nouvelle mise à jour !
							<small>NeoFrag <?php echo $update->version ?></small>
						</h6>
						<a href="#" class="btn btn-primary btn-block" data-modal-ajax="<?php echo url('admin/monitoring/update') ?>">Installer !</a>
					</div>
				</div>
			<?php endif ?>
			<?php echo $this->widget('navigation')->output('vertical', $this->__caller->data->get('sidebar')) ?>
		</nav>
		<main class="col-md-9 ml-sm-auto col-lg-10">
			<nav>
				<?php echo $this->view('navigation') ?>
			</nav>
			<div class="pb-4 px-4">
				<header class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1>
						<?php echo $this->label($this->output->data->get('module', 'title'), $this->output->data->get('module', 'icon')) ?>
						<?php if ($subtitle = $this->output->data->get('module', 'subtitle')): ?>
						<small><?php echo $subtitle ?></small>
						<?php endif ?>
					</h1>
					<?php if ($actions = $this->output->data->get('module', 'actions')): ?>
						<div class="actions">
							<?php
								echo implode($actions);
							?>
						</div>
					<?php endif ?>
					<?php //echo $this->widget('breadcrumb')->output() ?>
				</header>
				<?php if ($error = $this->output->error()): ?>
					<div class="module module-admin module-error"><?php echo $error ?></div>
				<?php else: ?>
					<div class="module module-admin module-<?php echo $this->output->module()->info()->name ?>"><?php echo $this->output->module() ?></div>
				<?php endif ?>
			</div>
		</main>
	</div>
</div>
<?php echo $this->theme('default')->view('logo') ?>
