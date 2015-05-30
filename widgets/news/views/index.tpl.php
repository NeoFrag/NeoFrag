<?php foreach ($data['news'] as $news): ?>
<div class="media">
	<a href="{base_url}members/<?php echo $news['user_id']; ?>/<?php echo url_title($news['username']); ?>.html" class="media-left">
		<img style="width: 48px; height: 48px;" src="<?php echo $NeoFrag->user->avatar($news['avatar'], $news['sex']); ?>" data-toggle="tooltip" title="<?php echo $news['username']; ?>" alt="" />
	</a>
	<div class="media-body">
		<h4 class="media-heading"><a href="{base_url}news/<?php echo $news['news_id']; ?>/<?php echo url_title($news['title']); ?>.html"><?php echo $news['title']; ?></a></h4>
		<i class="fa fa-clock-o"></i> <?php echo time_span($news['date']); ?> <a href="{base_url}news/<?php echo $news['news_id']; ?>/<?php echo url_title($news['title']); ?>.html#comments"><i class="fa fa-comment-o"></i> <?php echo $NeoFrag->load->library('comments')->count_comments('news', $news['news_id']); ?></a>
	</div>
</div>
<?php endforeach; ?>