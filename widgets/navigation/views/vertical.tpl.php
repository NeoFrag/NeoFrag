<ul class="nav flex-column">
<?php
	array_walk($links, $f = function(&$link) use (&$f){
		$link = array_merge([
			'title'  => '',
			'url'    => '',
			'icon'   => '',
			'access' => TRUE
		], $link);

		if (is_array($link['url']))
		{
			array_walk($link['url'], $f);
		}
	});

	$actives = [];

	$is_active = function($link){
		return (($url = ltrim(preg_replace('_^'.preg_quote($this->url(), '_').'_', '', $this->url($link)), '/')) == $this->url->request) || ($url && strpos($this->url->request, $url) === 0);
	};

	$show_link = function($link, &$active = FALSE) use (&$actives){
		if ($link['access'])
		{
			return $this->html('li')
						->attr('class', 'nav-item')
						->append_attr_if($actives && $actives[0] == $link['url'] && ($active = TRUE), 'class', 'active')
						->content('<a class="nav-link" href="'.url($link['url']).'">'.icon($link['icon']).' '.$this->lang($link['title']).'</a>');
		}
	};

	foreach ($links as $link)
	{
		if (is_array($link['url']))
		{
			foreach ($link['url'] as $link)
			{
				if ($is_active($link['url']))
				{
					$actives[] = $link['url'];
				}
			}
		}
		else if ($is_active($link['url']))
		{
			$actives[] = $link['url'];
		}
	}

	usort($actives, function($a, $b){
		return strlen($a) < strlen($b);
	});

	foreach ($links as $link)
	{
		if (is_array($link['url']))
		{
			$active  = FALSE;
			$submenu = '';

			foreach ($link['url'] as $link2)
			{
				$submenu .= $show_link($link2, $active);
			}

			if ($submenu)
			{
				echo $this	->html('li')
							->attr('class', 'nav-item')
							->append_attr_if($active, 'class', 'active')
							->content('<a data-toggle="collapse" href="#" class="nav-link">'.icon($link['icon']).' <span class="hidden-xs">'.$this->lang($link['title']).'</span><span class="fa arrow"></span></a><ul class="nav flex-column">'.$submenu.'</ul>');
			}
		}
		else
		{
			echo $show_link($link);
		}
	}
?>
</ul>
