<div class="user-profile">
	<?php echo $user->avatar() ?>
	<h2><?php echo $user->username ?></h2>
	<?php echo $this->array
					->append_if($quote = $user->profile()->quote, '<i class="text-muted">'.$quote.'</i>')
					->append($user->profile()->first_name.' '.$user->profile()->last_name)
					->append_if($user->profile()->sex || $user->profile()->date_of_birth, function() use ($user){
						$sex = $user->profile()->sex;
						$date_of_birth = $user->profile()->date_of_birth;
						return $this->label($date_of_birth ? $this->lang('%d an|%d ans', $age = $date_of_birth->interval('today')->y, $age) : ($sex == 'female' ? 'Femme' : 'Homme'), $sex ? 'fa-'.($sex == 'female' ? 'venus' : 'mars').' '.$sex : 'fa-birthday-cake')
									->tooltip_if($date_of_birth, function($date){
										return $this->no_translate($date->short_date());
									});
					})
					->append_if($user->profile()->location || $user->profile()->country, function() use ($user){
						$country = $user->profile()->country;
						return $this->label($this->no_translate($user->profile()->location) ?: get_countries()[$country], $country && ($flag = image('flags/'.$country.'.png', $this->theme('default'))) ? '<img src="'.$flag.'" alt="" />' : 'fa-map-marker');
					})
					->filter()
					->each(function($a){
						return '<h3>'.$a.'</h3>';
					}) ?>
	<?php $socials = $this	->array([
								['website',   'fa-globe',     ''],
								['linkedin',  'fa-linkedin',  'https://www.linkedin.com/in/'],
								['github',    'fa-github',    'https://github.com/'],
								['instagram', 'fa-instagram', 'https://www.instagram.com/'],
								['twitch',    'fa-twitch',    'https://www.twitch.tv/']
							])
							->filter(function($a) use ($user){
								return $user->profile()->{$a[0]};
							})
							->each(function($a) use ($user){
								return '<a href="'.$a[2].$user->profile()->{$a[0]}.'" class="btn '.$a[0].'">'.icon($a[1]).'</a>';
							});
	?>
	<?php if (!$socials->empty()): ?><div class="socials"><?php echo $socials ?></div><?php endif ?>
	<?php if ($this->user() && $this->user != $user) echo $this->button()->title('Contacter')->icon('fa-envelope-o')->color('dark btn-block')->url('user/messages/compose/'.$user->url()) ?>
</div>
