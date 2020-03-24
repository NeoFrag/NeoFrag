<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Bbcode extends Library
{
	public function bbcode2html($output)
	{
		$bbcode = [
			'\[(b|bold|strong)\](.*?)\[/\1\]'                   => '<b>\2</b>',
			'\[(i|italic)\](.*?)\[/\1\]'                        => '<i>\2</i>',
			'\[(u|underline)\](.*?)\[/\1\]'                     => '<u>\2</u>',
			'\[(s|strike)\](.*?)\[/\1\]'                        => '<strike>\2</strike>',
			'\[(sup|sub|tr|td)\](.*?)\[/\1\]'                   => '<\1>\2</\1>',
			'\[img\](.*?)\[/img\]'                              => '<img class="img-fluid" src="\1" alt="" />',
			'\[video\]([a-zA-Z0-9_-]{6,11})\[/video\]'          => '<iframe src="https://www.youtube.com/embed/\1" width="640" height="480" frameborder="0"></iframe>',
			'\[url\](.*?)\[/url\]'                              => '<a href="\1">\1</a>',
			'\[url=(.*?)\](.*?)\[/url\]'                        => '<a href="\1">\2</a>',
			'\[list\](.*?)\[/list\]'                            => function($a){
				return '<ul>'.trim($a[1]).'</ul>';
			},
			'\[list=1\](.*?)\[/list\]'                          => function($a){
				return '<ol>'.trim($a[1]).'</ol>';
			},
			'\[\*\](.*?)\[/\*\]'."(?:[\r\n]*)*"                 => '<li>\1</li>',
			'\[color=(#([0-9a-fA-F]{3}){1,2})\](.*?)\[/color\]' => '<span style="color: \1;">\3</span>',
			'\[size=(\d*?)\](.*?)\[/size\]'                     => '<span style="font-size: \1%;">\2</span>',
			'\[font=(.*?)\](.*?)\[/font\]'                      => '<span style="font-family: \'\1\';">\2</span>',
			'\[(left|center|right)\](.*?)\[/\1\]'               => '<div style="text-align: \1;">\2</div>',
			'\[quote\](.*?)\[/quote\]'                          => '<blockquote>\1</blockquote>',
			'\[code\](.*?)\[/code\]'                            => '<code>\1</code>',
			'\[(table)\](.*?)\[/\1\]'                           => '<\1 class="table table-bordered">\2</\1>',
			'\[(h[1-6])\](.*?)\[/\1\]'."(?:\r\n|\n)?"           => '<\1>\2</\1>'
		];

		foreach ($bbcode as $regex => $replace)
		{
			$regex = '#'.str_replace('#', '\#', $regex).'#s';

			while (preg_match($regex, $output))
			{
				if (is_a($replace, 'closure'))
				{
					$output = preg_replace_callback($regex, $replace, $output);
				}
				else
				{
					$output = preg_replace($regex, $replace, $output);
				}
			}
		}

		return $output;
	}
}
