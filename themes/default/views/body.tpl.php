<header>
	<?php if ($zone = $this->output->zone(0)): ?>
	<div class="haut">
		<div class="container">
			<?php echo $zone ?>
		</div>
	</div>
	<?php endif ?>
	<?php if ($zone = $this->output->zone(1)): ?>
	<div class="entete">
		<div class="container">
			<?php echo $zone ?>
		</div>
	</div>
	<?php endif ?>
</header>
<section id="avant-contenu">
	<?php if ($zone = $this->output->zone(2)): ?>
	<div class="container">
		<?php echo $zone ?>
	</div>
	<?php endif ?>
</section>
<section id="contenu">
	<?php if (($zone = $this->output->error()) || ($zone = $this->output->zone(3))): ?>
	<div class="container">
		<?php echo $zone ?>
	</div>
	<?php endif ?>
</section>
<?php if ($zone = $this->output->zone(4)): ?>
<section id="avant-contenu">
	<div class="container">
		<?php echo $zone ?>
	</div>
</section>
<?php endif ?>
<?php if ($zone = $this->output->zone(5)): ?>
<footer>
	<div class="container">
		<?php echo $zone ?>
	</div>
</footer>
<?php endif ?>
