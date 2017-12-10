<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Comments\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin extends Controller_Module
{
	public function index($comments, $modules, $tab)
	{
		$this->tab->add_tab('', $this->lang('Tous les commentaires'), function() use ($comments){
			return $this->_tab_index($comments);
		});

		foreach ($modules as $module_name => $module)
		{
			list($title, $icon) = $module;
			$this->tab->add_tab($module_name, icon($icon).' '.$title, function() use ($comments, $title){
				return $this->_tab_index($comments, $title);
			});
		}

		return $this->panel()->body($this->tab->display($tab));
	}

	private function _tab_index($comments, $title = NULL)
	{
		$this->subtitle($title === NULL ? $this->lang('Tous les commentaires') : $title);

		if ($title === NULL)
		{
			$this->table()->add_columns([
				[
					'title'   => $this->lang('Module'),
					'content' => function($data){
						return '<a href="'.url('admin/comments/'.$data['module']).'">'.icon($data['icon']).' '.$data['module_title'].'</a>';
					},
					'size'    => '25%',
					'sort'    => function($data){
						return $data['module_title'];
					},
					'search'  => function($data){
						return $data['module_title'];
					}
				]
			]);
		}

		return $this->table()->add_columns([
			[
				'title'   => $this->lang('Nom'),
				'content' => function($data){
					return $data['title'];
				},
				'sort'    => function($data){
					return $data['title'];
				},
				'search'  => function($data){
					return $data['title'];
				}
			],
			[
				'title'   => '<i class="fa fa-comments-o" data-toggle="tooltip" title="'.$this->lang('Nombre de commentaires').'"></i>',
				'content' => function($data){
					return NeoFrag()->comments->admin_comments($data['module'], $data['module_id'], FALSE);
				},
				'size'    => TRUE
			],
			[
				'content' => function($data){
					return $this->button()
								->tooltip($this->lang('Voir les commentaires'))
								->icon('fa-eye')
								->url($data['url'])
								->color('info')
								->compact()
								->outline();
				},
				'size'    => TRUE
			]
		])
		->data($comments)
		->no_data($this->lang('Il n\'y a pas de commentaire'))
		->sort_by(1)
		->display();
	}
}
