<?php if ($image): ?>
	<a href="<?php echo url('news/'.$news_id.'/'.url_title($title)) ?>">
		<img class="card-img-top" src="<?php echo NeoFrag()->model2('file', $image)->path() ?>" alt="" />
	</a>
<?php endif ?>
<div class="card-body">
	<h5 class="card-title"><a href="<?php echo url('news/'.$news_id.'/'.url_title($title)) ?>"><?php echo $title ?></a></h5>
	<p class="card-text"><?php echo $introduction ?></p>
	<blockquote class="blockquote mb-0">
		<footer class="blockquote-footer"><?php echo $this->lang('Par').' '.($user_id ? $this->user->link($user_id, $username) : $this->lang('Visiteur')).' '.$this->lang('le').' '.timetostr('%e %b %Y', $date) ?> / <a href="<?php echo url('news/category/'.$category_id.'/'.$category_name) ?>"><?php echo $category_title ?></a><?php echo (($comments = $this->module('comments')) && $comments->is_enabled()) ? ' / '.$comments->link('news', $news_id, 'news/'.$news_id.'/'.url_title($title)) : '' ?></footer>
	</blockquote>
</div>
<?php if($tags || $content): ?>
<div class="card-footer">
	<?php if ($tags): ?>
		<ul class="list-inline mb-0 pull-left">
			<li class="list-inline-item"><small><?php echo icon('fa-tag') ?></small></li>
			<?php foreach (explode(',', $tags) as $tag): ?>
				<li class="list-inline-item"><a href="<?php echo url('news/tag/'.url_title($tag)) ?>"><small><?php echo $tag ?></small></a></li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
	<?php if ($content): ?>
		<a href="<?php echo url('news/'.$news_id.'/'.url_title($title)) ?>" class="btn btn-sm btn-secondary pull-right"><?php echo $this->lang('Continuer à lire') ?></a>
	<?php endif ?>
</div>
<?php endif ?>
