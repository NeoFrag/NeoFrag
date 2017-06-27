<?php if ($image): ?>
	<div class="pull-left mr-4">
		<a href="<?php echo url('news/'.$news_id.'/'.url_title($title)) ?>"><img class="img-thumbnail" src="<?php echo NeoFrag()->model2('file', $image)->path() ?>" alt="" /></a>
	</div>
<?php endif ?>
<p class="news-detail">
	<span><?php echo icon('fa-clock-o').' '.timetostr('%e %b %Y', $date) ?></span>
	<span><?php echo icon('fa-user').' '.($user_id ? $this->user->link($user_id, $username) : $this->lang('Visiteur')) ?></span>
	<!--<span><?php echo icon('fa-eye').' ' .$views ?></span>-->
	<?php if (($comments = $this->module('comments')) && $comments->is_enabled()): ?>
		<span><?php echo $comments->link('news', $news_id, 'news/'.$news_id.'/'.url_title($title)) ?></span>
	<?php endif ?>
	<span><?php echo icon('fa-bookmark-o') ?> <a href="<?php echo url('news/category/'.$category_id.'/'.$category_name) ?>"><?php echo $category_title ?></a></span>
</p>
<?php echo $introduction ?>
<?php if ($tags): ?>
	<hr />
	<?php foreach (explode(',', $tags) as $tag): ?>
		<a class="badge badge-default news-tags" href="<?php echo url('news/tag/'.url_title($tag)) ?>"><?php echo icon('fa-tag').' '.$tag ?></a>
	<?php endforeach ?>
<?php endif ?>
