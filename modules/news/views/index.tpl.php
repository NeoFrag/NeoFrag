<?php if ($data['image']): ?>
	<div class="pull-left">
		<a class="thumbnail" href="<?php echo url('news/'.$data['news_id'].'/'.url_title($data['title'])); ?>"><img class="img-responsive" src="<?php echo path($data['image']); ?>" alt="" /></a>
	</div>
<?php endif; ?>
<p class="news-detail">
	<span><?php echo icon('fa-clock-o').' '.timetostr('%e %b %Y', $data['date']); ?></span>
	<span><?php echo icon('fa-user').' '.($data['user_id'] ? $this->user->link($data['user_id'], $data['username']) : $this->lang('guest')); ?></span>
	<!--<span><?php echo icon('fa-eye').' ' .$data['views']; ?></span>-->
	<span><a href="<?php echo url('news/'.$data['news_id'].'/'.url_title($data['title'])); ?>"><?php echo icon('fa-comments-o').' '.$this->comments->count_comments('news', $data['news_id']); ?></a></span>
	<?php if ($data['vote'] && $note = $this->votes->get_note('news', $data['news_id'])): ?><div class="info-votes"><a href="<?php echo url('news/'.$data['news_id'].'/'.url_title($data['title'])); ?>"><?php echo $note; ?>/5</a></div><?php endif; ?>
	<span><?php echo icon('fa-bookmark-o'); ?> <a href="<?php echo url('news/category/'.$data['category_id'].'/'.$data['category_name']); ?>"><?php echo $data['category_title']; ?></a></span>
</p>
<?php echo $data['introduction']; ?>
<?php if ($data['tags']): ?>
	<hr />
	<?php foreach (explode(',', $data['tags']) as $tag): ?>
		<a class="label label-default news-tags" href="<?php echo url('news/tag/'.url_title($tag)); ?>"><?php echo icon('fa-tag').' '.$tag; ?></a>
	<?php endforeach; ?>
<?php endif; ?>