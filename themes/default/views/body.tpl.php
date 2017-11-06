<?php if ($zone = $this->output->zone(4)): ?>
<header>
	<div class="container">
		<?php echo $zone ?>
	</div>
</header>
<?php endif ?>
<div id="content">
	<?php if ($zone = $this->output->zone(3)): ?>
	<div class="container">
		<?php echo $zone ?>
	</div>
	<?php endif ?>
	<?php if ($zone = $this->output->zone(1)): ?>
	<div class="container">
		<?php echo $zone ?>
	</div>
	<?php endif ?>
	<?php if (($zone = $this->output->error()) || ($zone = $this->output->zone(0))): ?>
	<div class="container">
		<?php echo $zone ?>
	</div>
	<?php endif ?>
	<?php if ($zone = $this->output->zone(2)): ?>
	<div class="container">
		<div class="container-dark">
			<?php echo $zone ?>
		</div>
	</div>
	<?php endif ?>
	<?php if ($zone = $this->output->zone(5)): ?>
	<div class="container">
		<?php echo $zone ?>
	</div>
	<?php endif ?>
</div>