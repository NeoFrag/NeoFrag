<nav class="navbar navbar-topbar">
	<div class="container">
		<?php echo $NeoFrag->output->display_zone(4); ?>
	</div>
</nav>
<?php if ($zone = $NeoFrag->output->display_zone(3)): ?>
<div class="container">
	<?php echo $zone; ?>
</div>
<?php endif; ?>
<div class="page">
	<?php if ($zone = $NeoFrag->output->display_zone(1)): ?>
	<div class="container">
		<?php echo $zone; ?>
	</div>
	<?php endif; ?>
	<div class="container">
		<?php echo $NeoFrag->output->display_zone(0); ?>
	</div>
	<div class="container">
		<div class="container-dark">
			<?php echo $NeoFrag->output->display_zone(2); ?>
		</div>
	</div>
	<div class="container">
		<?php echo $NeoFrag->output->display_zone(5); ?>
	</div>
</div>