<?php
$languages = $NeoFrag->db	->select('code', 'name', 'flag')
						->from('nf_settings_languages')
						->get();

if (!empty($languages))
{
	$tmp     = [];
	$current = '';

	foreach ($languages as $lang)
	{
		if ($NeoFrag->config->lang == $lang['code'])
		{
			$current = $lang['name'];
		}

		$tmp[$lang['name']] = [
			'code' => $lang['code'],
			'flag' => $lang['flag']
		];
	}

	ksort($tmp);
	$languages = $tmp;
	unset($languages[$current]);

	echo '<ul>';

	foreach (array_merge([$current => $tmp[$current]], $languages) as $name => $lang)
	{
		echo '<li data-lang="'.$lang['code'].'"><img src="'.image('flags/'.$lang['flag'])." alt="" />'.$name.'</li>';
	}

	echo '</ul>';
}
?>