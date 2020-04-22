<div class="card-group">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title"><?php echo $this->lang('À propos de l\'auteur') ?></h5>
			<div class="media">
				<?php echo $this->module('user')->model2('user', $user_id)->avatar() ?>
				<div class="media-body">
					<h5 class="mb-0"><?php echo $this->user->link($user_id, $username) ?></h5>
					<?php if (!empty($quote)): ?>
						<blockquote><?php echo $quote ?></blockquote>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h5 class="card-title"><?php echo $this->lang('Autres actualités de l\'auteur') ?></h5>
			<?php if (!empty($news)): ?>
			<ul class="list-unstyled">
				<?php foreach ($news as $news): ?>
				<li><a href="<?php echo url('news/'.$news['news_id'].'/'.url_title($news['title'])) ?>"><?php echo str_shortener($news['title'], 45) ?></a></li>
				<?php endforeach ?>
			</ul>
			<?php else: ?>
			<?php echo $this->lang('L\'auteur n\'a pas publié d\'autre actualité') ?>
			<?php endif ?>
		</div>
	</div>
</div>
