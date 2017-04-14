<?php
	$avatar = $user->profile()->avatar() ? $user->profile()->avatar->path() : image($user->profile()->sex == 'female' ? 'default_avatar_female.jpg' : 'default_avatar_male.jpg');
?>
<div class="inner-ring"></div>
<figure>
	<?php if ($user->id): ?>
		<a href="<?php echo url('///user/'.$user->id.'/'.url_title($user->username)) ?>">
			<img class="img-fluid" src="<?php echo $avatar ?>" alt="" />
		</a>
	<?php else: ?>
		<img class="img-fluid" src="<?php echo $avatar ?>" alt="" />
	<?php endif ?>
</figure>
