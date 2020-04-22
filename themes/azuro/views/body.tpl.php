<header class="header">
	<?php if ($zone = (string)$this->output->zone(0)): ?>
	<div class="haut azuro-bg-dark py-1">
		<div class="container">
			<?php echo $zone ?>
		</div>
	</div>
	<?php endif ?>
	<?php if ($zone = (string)$this->output->zone(1)): ?>
	<div class="entete py-5">
		<div class="container">
			<?php echo $zone ?>
		</div>
	</div>
	<?php endif ?>
	<?php if ($zone = (string)$this->output->zone(2)): ?>
		<div class="menu bg-white">
			<div class="container">
				<?php echo $zone ?>
			</div>
		</div>
		<?php if (!empty($this->url->request) && ($breadcrumb = $this->widget('breadcrumb'))): ?>
		<div class="header-breadcrumb azuro-bg-gradient text-white">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<?php echo $breadcrumb->output() ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif ?>
	<?php endif ?>
</header>
<?php if ($zone = (string)$this->output->zone(3)): ?>
<section id="slider">
	<div class="container-fluid">
		<?php echo $zone ?>
	</div>
</section>
<?php endif ?>
<?php if ($zone = (string)$this->output->zone(4)): ?>
<section class="bg-white border-top border-bottom py-5" id="avant-contenu">
	<div class="container">
		<?php echo $zone ?>
	</div>
</section>
<?php endif ?>
<?php
if (count($this->url->segments) == 3 && $this->url->segments[0] == 'user' && isset($this->url->segments[1]) && isset($this->url->segments[2]))
{
	if (($user = $this->module('user')->model2('user', $this->url->segments[1])->check($this->url->segments[2])) && !$user->deleted)
	{
		echo $user->view('cover');
	}
}
?>
<?php if (($zone = (string)$this->output->error()) || ($zone = (string)$this->output->zone(5))): ?>
<section class="py-5" id="contenu">
	<div class="container">
		<?php echo $zone ?>
	</div>
</section>
<?php endif ?>
<?php if ($zone = (string)$this->output->zone(6)): ?>
<section class="bg-dark py-5" id="post-contenu">
	<div class="container">
		<?php echo $zone ?>
	</div>
</section>
<?php endif ?>
<?php if ($zone = (string)$this->output->zone(7)): ?>
<footer class="footer azuro-bg-dark py-4">
	<div class="container">
		<?php echo $zone ?>
	</div>
</footer>
<?php endif ?>
