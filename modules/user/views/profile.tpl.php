<div class="user-profile">
	<?php echo $user->avatar() ?>
	<h4 class="mb-3"><?php echo $user->username ?></h4>
	<?php
		if (($profile = $user->profile()) && $profile())
		{
			echo $this	->array
						->append_if($quote = $profile->quote, '<i class="text-muted">'.$quote.'</i>')
						->append($profile->first_name.' '.$profile->last_name)
						->append_if($profile->sex || $profile->date_of_birth, function() use ($profile){
							$sex = $profile->sex;
							$date_of_birth = $profile->date_of_birth;
							return $this->label($date_of_birth ? $this->lang('%d an|%d ans', $age = $date_of_birth->interval('today')->y, $age) : ($sex == 'female' ? 'Femme' : 'Homme'), $sex ? ($sex == 'female' ? 'fas fa-venus' : 'fas fa-mars').' '.$sex : 'fas fa-birthday-cake')
										->tooltip_if($date_of_birth, function($date){
											return $this->no_translate($date->short_date());
										});
						})
						->append_if($profile->location || $profile->country, function() use ($profile){
							$country = $profile->country;
							return $this->label($this->no_translate($profile->location) ?: get_countries()[$country], $country && ($flag = image('flags/'.$country.'.png', $this->theme('default'))) ? '<img src="'.$flag.'" alt="" />' : 'fas fa-map-marker-alt');
						})
						->filter()
						->each(function($a){
							return '<h6>'.$a.'</h6>';
						});

			$socials = $this	->array([
									['website',   'fas fa-globe',       ''],
									['linkedin',  'fab fa-linkedin-in', 'https://www.linkedin.com/in/'],
									['github',    'fab fa-github',      'https://github.com/'],
									['instagram', 'fab fa-instagram',   'https://www.instagram.com/'],
									['twitch',    'fab fa-twitch',      'https://www.twitch.tv/']
								])
								->filter(function($a) use ($profile){
									return $profile->{$a[0]};
								})
								->each(function($a) use ($profile){
									return '<a href="'.$a[2].$profile->{$a[0]}.'" class="btn '.$a[0].'" target="_blank">'.icon($a[1]).'</a>';
								});
		}
	?>
	<?php if (isset($socials) && !$socials->empty()): ?><div class="socials"><?php echo $socials ?></div><?php endif ?>
	<?php if ($this->user() && $this->user != $user) echo $this->button()->title('Contacter')->icon('far fa-envelope')->color('dark btn-block')->url('user/messages/compose/'.$user->url()) ?>
</div>
