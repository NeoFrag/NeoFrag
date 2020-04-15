<style type="text/css">
.socials-content {
	padding-top: <?php echo $settings['padding_top'] ? $settings['padding_top'] : '0' ?>px;
	padding-right: <?php echo $settings['padding_right'] ? $settings['padding_right'] : '0' ?>px;
	padding-bottom: <?php echo $settings['padding_bottom'] ? $settings['padding_bottom'] : '0' ?>px;
	padding-left: <?php echo $settings['padding_left'] ? $settings['padding_left'] : '0' ?>px;
	margin-top: <?php echo $settings['margin_top'] ? $settings['margin_top'] : '0' ?>px;
	margin-right: <?php echo $settings['margin_right'] ? $settings['margin_right'] : '0' ?>px;
	margin-bottom: <?php echo $settings['margin_bottom'] ? $settings['margin_bottom'] : '0' ?>px;
	margin-left: <?php echo $settings['margin_left'] ? $settings['margin_left'] : '0' ?>px;
}
</style>
<div class="socials-content">
<?php
	$buttons = [];

	$is_list = in_array($settings['social_display'], ['ul-inline', 'ul']);

	foreach ([
			'nf_social_facebook'   => ['Facebook',   'fa-facebook-f',    '#3B5998'],
			'nf_social_twitter'    => ['Twitter',    'fa-twitter',       '#55ACEE'],
			'nf_social_google'     => ['Google+',    'fa-google-plus-g', '#dd4b39'],
			'nf_social_steam'      => ['Steam',      'fa-steam',         '#00adee'],
			'nf_social_twitch'     => ['Twitch',     'fa-twitch',        '#772ce8'],
			'nf_social_dribble'    => ['Dribbble',   'fa-dribbble',      '#ea4c89'],
			'nf_social_behance'    => ['Behance',    'fa-behance',       '#2196f3'],
			'nf_social_deviantart' => ['DeviantArt', 'fa-deviantart',    '#00e59b'],
			'nf_social_flickr'     => ['Flickr',     'fa-flickr',        '#f40083'],
			'nf_social_github'     => ['GitHub',     'fa-github',        '#24292e'],
			'nf_social_instagram'  => ['Instagram',  'fa-instagram',     '#125688'],
			'nf_social_youtube'    => ['Youtube',    'fa-youtube',       '#bb0000']
		] as $var => list($title, $icon, $bgcolor))
	{
		if ($this->config->$var)
		{
			$button = $this	->html('a')
							->attr('href',   $this->config->$var)
							->attr('target', '_blank')
							->attr('class',  $settings['social_style'])
							->append_attr_if(!$is_list,                            'class', 'btn-block')
							->attr_if($settings['social_style'] != 'btn btn-link', 'style', 'background-color: '.$bgcolor)
							->content($this	->label()
											->icon_if(in_array($settings['content_display'], ['all', 'icon']), 'fab '.$icon.' '.$settings['icon_size'])
											->exec(function($label) use ($settings, $title){
												if (in_array($settings['content_display'], ['all', 'legend']))
												{
													$label->title($title);
												}
												else
												{
													$label->tooltip($title);
												}
											})
							);

			if ($is_list)
			{
				$button = $this	->html('li')
								->attr_if($settings['social_display'] == 'ul-inline', 'class', 'list-inline-item')
								->content($button);
			}
			else
			{
				$button = $this->col($button)->size($settings['social_display']);
			}

			$buttons[] = $button;
		}
	}

	if ($is_list)
	{
		echo $this	->html('ul')
					->attr('class', $settings['social_display'] == 'ul-inline' ? 'list-inline' : 'list-unstyled')
					->content(implode($buttons));
	}
	else
	{
		echo $this->row(implode($buttons));
	}
?>
</div>
