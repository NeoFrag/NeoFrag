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

class m_forum extends Module
{
	public $name        = 'Forum';
	public $description = '';
	public $icon        = 'fa-comments';
	public $link        = 'http://www.neofrag.com';
	public $author      = 'Michaël Bilcot <michael.bilcot@neofrag.com>';
	public $licence     = 'http://www.neofrag.com/license.html LGPLv3';
	public $version     = 'Alpha 0.1';
	public $nf_version  = 'Alpha 0.1';
	public $path        = __FILE__;
	public $routes      = array(
		//Index
		'{id}/{url_title}{page}'                   => '_forum',
		'new/{id}/{url_title}'                     => '_new',
		'topic/{id}/{url_title}{page}'             => '_topic',
		'delete/{id}/{url_title}'                  => '_topic_delete',
		'announce/{id}/{url_title}'                => '_topic_announce',
		'lock/{id}/{url_title}'                    => '_topic_lock',
		'message/edit/{id}/{url_title}'            => '_message_edit',
		'message/delete/{id}/{url_title}'          => '_message_delete',
		'mark-all-as-read/{id}/{url_title}'        => '_mark_all_as_read',
		                                           
		//Admin                                    
		'admin/{id}/{url_title}'                   => '_edit',
		'admin/categories/add'                     => '_categories_add',
		'admin/categories/{id}/{url_title}'        => '_categories_edit',
		'admin/categories/delete/{id}/{url_title}' => '_categories_delete',
		'admin/ajax/categories/move'               => '_categories_move'
	);

	public static function access()
	{
		return array(
			'category' => array(
				'get_all' => function(){
					return NeoFrag::loader()->db->select('category_id', 'CONCAT_WS(" ", "Catégorie", title)')->from('nf_forum_categories')->get();
				},
				'check'   => function($category_id){
					if (($category = NeoFrag::loader()->db->select('title')->from('nf_forum_categories')->where('category_id', $category_id)->row()) !== array())
					{
						return 'Catégorie '.$category;
					}
				},
				'init'    => array(
					'category_write' => array(
						array('visitors', FALSE)
					),
					'category_modify' => array(
						array('admins', TRUE)
					),
					'category_delete' => array(
						array('admins', TRUE)
					),
					'category_announce' => array(
						array('admins', TRUE)
					),
					'category_lock' => array(
						array('admins', TRUE)
					)
				),
				'access'  => array(
					array(
						'title'  => 'Catégorie',
						'icon'   => 'fa-navicon',
						'access' => array(
							'category_read' => array(
								'title' => 'Lire',
								'icon'  => 'fa-eye'
							),
							'category_write' => array(
								'title' => 'Écrire',
								'icon'  => 'fa-reply'
							)
						)
					),
					array(
						'title'  => 'Modération',
						'icon'   => 'fa-user',
						'access' => array(
							'category_modify' => array(
								'title' => 'Éditer un sujet / message',
								'icon'  => 'fa-edit'
							),
							'category_delete' => array(
								'title' => 'Supprimer un sujet / message',
								'icon'  => 'fa-trash-o'
							),
							'category_announce' => array(
								'title' => 'Mettre un sujet en annonce',
								'icon'  => 'fa-flag'
							),
							'category_lock' => array(
								'title' => 'Vérouiller un sujet',
								'icon'  => 'fa-lock'
							)
						)
					)
				)
			)
		);
	}
	
	public function load()
	{
		if (!$this->config->admin_url && !$this->config->ajax_url)
		{
			$this->css('forum');
		}
	}
}

/*
NeoFrag Alpha 0.1
./modules/forum/forum.php
*/