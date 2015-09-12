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

function button($url, $icon, $title = '', $color = 'default', $class = '')
{
	return '<a class="'.($class ? $class.' ' : '').'btn btn-outline btn-'.$color.' btn-xs" href="'.url($url).'"'.($title ? ' data-toggle="tooltip" title="'.$title.'"' : '').'>'.icon($icon).'</a>';
}

function button_edit($url, $title = 'Éditer')
{
	return button($url, 'fa-pencil', $title, 'info');
}

function button_delete($url, $title = 'Supprimer')
{
	NeoFrag::loader()	->css('neofrag.delete')
						->js('neofrag.delete');
						
	return button($url, 'fa-remove', $title, 'danger', 'delete');
}

function button_add($url, $title, $icon = 'fa-plus')
{
	return '<a class="btn btn-outline btn-success" href="'.url($url).'">'.icon($icon).' '.$title.'</a>';
}

/*
NeoFrag Alpha 0.1
./neofrag/helpers/buttons.php
*/