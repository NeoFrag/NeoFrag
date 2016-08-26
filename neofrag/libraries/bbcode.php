<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class BBCode extends Library
{
	private $_bbcode = [
		'\[(b|bold|strong)\](.*?)\[/\1\]'                   => '<b>\2</b>',
		'\[(i|italic)\](.*?)\[/\1\]'                        => '<i>\2</i>',
		'\[(u|underline)\](.*?)\[/\1\]'                     => '<u>\2</u>',
		'\[(s|strike)\](.*?)\[/\1\]'                        => '<strike>\2</strike>',
		'\[(sup|sub|tr|td)\](.*?)\[/\1\]'                   => '<\1>\2</\1>',
		'\[img\](.*?)\[/img\]'                              => '<img class="img-responsive" src="\1" alt="" />',
		'\[video\]([a-zA-Z0-9_-]{6,11})\[/video\]'          => '<iframe src="https://www.youtube.com/embed/\1" width="640" height="480" frameborder="0"></iframe>',
		'\[url\](.*?)\[/url\]'                              => '<a href="\1">\1</a>',
		'\[url=(.*?)\](.*?)\[/url\]'                        => '<a href="\1">\2</a>',
		'\[list\](.*?)\[/list\]'                            => '<ul>\1</ul>',
		'\[list=1\](.*?)\[/list\]'                          => '<ol>\1</ol>',
		'\[\*\](.*?)\[/\*\]'                                => '<li>\1</li>',
		'\[color=(#([0-9a-fA-F]{3}){1,2})\](.*?)\[/color\]' => '<span style="color: \1;">\3</span>',
		'\[size=(\d*?)\](.*?)\[/size\]'                     => '<span style="font-size: \1%;">\2</span>',
		'\[font=(.*?)\](.*?)\[/font\]'                      => '<span style="font-family: \'\1\';">\2</span>',
		'\[(left|center|right)\](.*?)\[/\1\]'               => '<div style="text-align: \1;">\2</div>',
		'\[quote\](.*?)\[/quote\]'                          => '<blockquote>\1</blockquote>',
		'\[code\](.*?)\[/code\]'                            => '<code>\1</code>',
		'\[(table)\](.*?)\[/\1\]'                           => '<\1 class="table table-bordered">\2</\1>'
	];

	public function bbcode2html($output)
	{
		foreach ($this->_bbcode as $regex => $replace)
		{
			$regex = '#'.str_replace('#', '\#', $regex).'#s';

			while (preg_match($regex, $output))
			{
				$output = preg_replace($regex, $replace, $output);
			}
		}

		return $output;
	}
}

/*
NeoFrag Alpha 0.1.4.1
./neofrag/libraries/bbcode.php
*/