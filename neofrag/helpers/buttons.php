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

function button($url, $icon, $title = '', $color = 'default', $data = [], $small = TRUE, $tooltip = NULL)
{
	array_walk($data, function(&$value, $name){
		$value = ' data-'.$name.'="'.$value.'"';
	});
	
	$output = ' class="btn btn-outline btn-'.$color.($small ? ' btn-xs' : '').'"'.($title && ($tooltip || $small)? ' data-toggle="tooltip" title="'.$title.'"' : '').($data ? implode($data) : '').'>'.icon($icon);
	
	if ($title && ($tooltip === FALSE || $tooltip === NULL && !$small))
	{
		$output .= ' '.$title;
	}
	
	return $url ? '<a href="'.url($url).'"'.$output.'</a>' : '<span'.$output.'</span>';
}

function button_sort($id, $url, $parent = 'table', $items = 'tr', $title = NULL)
{
	NeoFrag::loader()->js('neofrag.sortable');
	
	if ($title === NULL)
	{
		$title = NeoFrag::loader()->lang('sort');
	}

	return button(NULL, 'fa-arrows-v', $title, 'link btn-sortable', [
		'id'     => $id,
		'update' => url($url),
		'parent' => $parent,
		'items'  => $items
	]);
}

function button_access($id, $access = NULL, $module = NULL, $title = NULL)
{
	if ($title === NULL)
	{
		$title = NeoFrag::loader()->lang('permissions');
	}

	return button('admin/access/edit/'.($access ? ($module ?: NeoFrag::loader()->module->name).'/'.$id.'-'.$access : $id).'.html', 'fa-unlock-alt', $title, 'success');
}

function button_edit($url, $title = NULL)
{
	if ($title === NULL)
	{
		$title = NeoFrag::loader()->lang('edit');
	}

	return button($url, 'fa-pencil', $title, 'info');
}

function button_delete($url, $title = NULL)
{
	NeoFrag::loader()	->css('neofrag.delete')
						->js('neofrag.delete');

	if ($title === NULL)
	{
		$title = NeoFrag::loader()->lang('remove');
	}

	return button($url, 'fa-remove', $title, 'danger delete');
}

function button_add($url, $title, $icon = 'fa-plus')
{
	return button($url, $icon, $title, 'primary', [], FALSE);
}

/*
NeoFrag Alpha 0.1.4
./neofrag/helpers/buttons.php
*/