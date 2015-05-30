<?php if ($data['image']): ?>
	<div class="pull-left">
		<a class="thumbnail" href="{base_url}news/{news_id}/{url_title(title)}.html"><img class="img-responsive" src="{image {image}}" alt="" /></a>
	</div>
<?php endif; ?>
<p class="news-detail">
	<span>{fa-icon clock-o} <?php echo timetostr('%e %b %Y', $data['date']); ?></span>
	<span>{fa-icon user} <?php echo $NeoFrag->user->link($data['user_id'], $data['username']); ?></span>
	<!--<span>{fa-icon eye} {views}</span>-->
	<span><a href="{base_url}news/{news_id}/{url_title(title)}.html#comments">{fa-icon comments-o} <?php echo $NeoFrag->library('comments')->count_comments('news', $data['news_id']); ?></a></span>
	<?php if ($data['vote'] && $note = $NeoFrag->library('votes')->get_note('news', $data['news_id'])): ?><div class="info-votes"><a href="{base_url}news/<?php echo $data['news_id'].'/'.url_title($data['title']); ?>.html#vote"><?php echo $note; ?>/5</a></div><?php endif; ?>
	<span>{fa-icon bookmark-o} <a href="{base_url}news/category/{category_id}/{category_name}.html">{category_title}</a></span>
</p>
{introduction}
<?php if ($data['tags']): ?>
	<hr />
	<?php foreach (explode(',', $data['tags']) as $tag): ?>
		<a class="label label-default news-tags" href="{base_url}news/tag/<?php echo url_title($tag); ?>.html">{fa-icon tag} <?php echo $tag; ?></a>
	<?php endforeach; ?>
<?php endif; ?>