<nav class="navbar navbar-topbar">
	<div class="container">
		<?php echo $this->output->zone(4); ?>
	</div>
</nav>
<?php if ($zone = $this->output->zone(3)): ?>
<div class="container">
	<?php echo $zone; ?>
</div>
<?php endif; ?>
<div class="page">
	<?php if ($zone = $this->output->zone(1)): ?>
	<div class="container">
		<?php echo $zone; ?>
	</div>
	<?php endif; ?>
	<div class="container">
		<?php echo $this->output->zone(0); ?>
	</div>
	<div class="container">
		<div class="container-dark">
			<?php echo $this->output->zone(2); ?>
		</div>
	</div>
	<div class="container">
		<?php echo $this->output->zone(5); ?>
	</div>
</div>