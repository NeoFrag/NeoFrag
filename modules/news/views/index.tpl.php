<?php if ($image): ?>
	<a href="<?php echo url('news/'.$news_id.'/'.url_title($title)) ?>">
		<img class="card-img-top" src="<?php echo NeoFrag()->model2('file', $image)->path() ?>" alt="" />
	</a>
<?php endif ?>
<div class="card-body">
	<h5 class="card-title"><a href="<?php echo url('news/'.$news_id.'/'.url_title($title)) ?>"><?php echo $title ?></a></h5>
	<p class="card-text"><?php echo $introduction ?></p>
	<blockquote class="blockquote mb-0">
		<?php if (isset($next)): ?>
		<div class="btn-group float-right">
			<a class="btn btn-light btn-sm" href="https://www.facebook.com/sharer.php?u=<?php echo $url = rawurlencode($this->url->location) ?>" target="_blank"><?php echo icon('fab fa-facebook-f text-primary') ?></a>
			<a class="btn btn-light btn-sm" href="https://twitter.com/share?url=<?php echo $url ?>" target="_blank"><?php echo icon('fab fa-twitter text-info') ?></a>
			<a class="btn btn-light btn-sm" href="https://plus.google.com/share?url=<?php echo $url ?>" target="_blank"><?php echo icon('fab fa-google-plus-g text-danger') ?></a>
		</div>
		<?php endif ?>
		<footer class="blockquote-footer"><?php echo $this->lang('Par').' '.($user_id ? $this->user->link($user_id, $username) : $this->lang('Visiteur')).' '.$this->lang('le').' '.timetostr('%e %b %Y', $date) ?> / <a href="<?php echo url('news/category/'.$category_id.'/'.$category_name) ?>"><?php echo $category_title ?></a><?php echo (($comments = $this->module('comments')) && $comments->is_enabled()) ? ' / '.$comments->link('news', $news_id, 'news/'.$news_id.'/'.url_title($title)) : '' ?></footer>
	</blockquote>
</div>
<?php if($tags || $content): ?>
<div class="card-footer">
	<?php if ($tags): ?>
		<ul class="list-inline mb-0 float-left">
			<li class="list-inline-item"><small><?php echo icon('fas fa-tag') ?></small></li>
			<?php foreach (explode(',', $tags) as $tag): ?>
				<li class="list-inline-item"><a href="<?php echo url('news/tag/'.url_title($tag)) ?>"><small><?php echo $tag ?></small></a></li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
	<?php if ($content): ?>
		<a href="<?php echo url('news/'.$news_id.'/'.url_title($title)) ?>" class="btn btn-sm btn-secondary float-right"><?php echo $this->lang('Continuer à lire') ?></a>
	<?php endif ?>
</div>
<?php endif ?>
