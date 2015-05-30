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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class TextEditor extends Library
{
	private $_bbcode = array(
		'\>' => '&gt;',
		'\<' => '&lt;',
		'\x0D' => '',

		'\[code(=(.+?))?\]([\S\s]+?)\[/code\]' => '<pre class="prettyprint linenums">\3</pre>',

		'\[img\]([\S\s]+?)\[/img\]' => '<img src="\1" alt="" />',
		'\[url\]([\S\s]+?)\[/url\]' => '<a href="\1">\1</a>',
		'\[url(=([\S\s]+?))?\]([\S\s]+?)\[/url\]' => '<a href="\2">\3</a>',
	
		'\[(table)\]([\S\s]+?)\[/\1\]' => '<\1 class="table">\2</\1>',
		'\[(tr|td)\]([\S\s]+?)\[/\1\]' => '<\1>\2</\1>',

		'\[(b|bold|strong)\]([\S\s]+?)\[/\1\]' => '<b>\2</b>',
		'\[(i|italic)\]([\S\s]+?)\[/\1\]' => '<i>\2</i>',
		'\[(u|underline)\]([\S\s]+?)\[/\1\]' => '<u>\2</u>',
		'\[(s|strike)\]([\S\s]+?)\[/\1\]' => '<span style="text-decoration: line-through;">\2</span>',
		'\[h([1-6])\]([\S\s]+?)\[/h\1\]' => '[title=\1]\2[/title]',
		'\[title=([1-6])\]([\S\s]+?)\[/title\]' => '<h\1>\2</h\1>',
		'\[(left|center|right|justify)\]([\S\s]+?)\[/\1\]' => '[align=\1]\2[/align]',
		'\[align=(left|center|right|justify)\]([\S\s]+?)\[/align\]' => '<div style="text-align: \1;">\2</div>',
		'\[float=(left|right)\]([\S\s]+?)\[/float\]' => '<div style="float: \1;">\2</div>',
		'\[font=(.+?)\]([\S\s]+?)\[/font\]' => '<span style="font-family: \'\1\';">\2</span>',
		'\[size=(\d+?)\]([\S\s]+?)\[/size\]' => '<span style="font-size: \1%;">\2</span>',
		'\[color=([a-zA-Z]+|#([0-9a-fA-F]{3}){1,2})\]([\S\s]+?)\[/color\]' => '<span style="color: \1;">\3</span>',
		'\[(flash|swf)=([0-9]*)(\||,)([0-9]*?)\](.+?)\[/\1\]' => '<object width="\2" height="\4" data="\5"><param name="movie" value="\5" /><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><embed src="\5" type="application/x-shockwave-flash" width="\2" height="\4" allowscriptaccess="always" allowfullscreen="true" /></object>',
		'\[\*\]([\S\s]+?)\[/\*\]' => '<li>\1</li>',
		'\[\*\]([\S\s]+?)(\[(\*|/list)\])' => '<li>\1</li>\2',
		'\[list(=(none|disc|circle|square|decimal|lower-roman|upper-roman|lower-greek|lower-alpha|lower-latin|upper-alpha|upper-latin|armenian|georgian|hebrew|cjk-ideographic|hiragana|katakana|hiragana-iroha|katakana-iroha))?\]([\S\s]+?)\[/list\]' => '<ul style="list-style: \2;">\3</ul>',
		'\[quote(=(.+?))?\]([\S\s]+?)\[/quote\]' => '<blockquote>\2</blockquote>',

		'\<ul style="list-style: ;">' => '<ul>',
		'(<div style="float: (left|right);">[\S\s]+?</div>)\x0A' => '\1<br />',
		'(<(((ul|pre).*?)|(/(div|ul|li|h[1-6])))>)\x0A([^\x0A])' => '\1\7',
		'\x0A' => '<br />',
		'\</div\>\<br /\>\<div' => '</div><div'
	);

	private $_htmlcode = array(
		'<br>' => "\n",
		'<b>([\S\s]*?)</b>' => '[b]\1[/b]',
		'<i>([\S\s]*?)</i>' => '[i]\1[/i]',
		'<u>([\S\s]*?)</u>' => '[u]\1[/u]',
		'<span style="text-decoration: line-through;">([\S\s]*?)</span>' => '[s]\1[/s]',
		'<h([1-6])>([\S\s]*?)</h\1>' => '[title=\1]\2[/title]',
		'<div style="text-align: (left|center|right|justify);">([\S\s]*?)</div>' => '[align=\1]\2[/align]',
		'<div style="float: (left|right);">([\S\s]*?)</div>' => '[float=\1]\2[/float]',
		'<span style="font-family: \'(.*?)\';">([\S\s]*?)</span>' => '[font=\1]\2[/font]',
		'<span style="font-size: (\d*?)px;">([\S\s]*?)</span>' => '[size=\1]\2[/size]',
		'<span style="color: ([a-zA-Z]+|#([0-9a-fA-F]{3}){1,2});">([\S\s]*?)</span>' => '[color=\1]\3[/color]',
	);

	public $name = 'editor';

	public function bbcode2html($output)
	{
		foreach ($this->_bbcode as $regex => $replace)
		{
			$regex = '#'.str_replace('#', '\#', $regex).'#';

			while (preg_match($regex, $output))
			{
				$output = preg_replace($regex, $replace, $output);
			}
		}

		return $output;
	}
}

/*
NeoFrag Alpha 0.1
./neofrag/libraries/text_editor.php
*/