<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Comments;

use NF\NeoFrag\Addons\Module;

class Comments extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('Commentaires'),
			'description' => '',
			'icon'        => 'far fa-comments',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => TRUE,
			'routes'      => [
				'admin/{pages}' => 'index'
			]
		];
	}

	public function __invoke($module, $module_id = 0)
	{
		if (is_a($module, 'NF\NeoFrag\Loadables\Model2'))
		{
			$module_id = $module->id;
			$module    = $module->__table;
		}

		if ($this->user())
		{
			$new = $this->view('new', [
				'form' => $this	->form2()
								->compact()
								->rule($this->form_textarea('comment')
											->rows(4)
											->required()
								)
								->success(function($data) use ($module, $module_id){
									$this	->model2('comment')
											->set('module',    $module)
											->set('module_id', $module_id)
											->set('content',   $data['comment'])
											->create();

									notify('Commentaire envoyé');

									refresh();
								})
								->submit('Envoyer')
			]);
		}
		else
		{
			$new = '<div class="alert alert-danger" role="alert">'.icon('fas fa-ban').' '.$this->lang('Vous devez être identifié pour pouvoir poster un commentaire').'</div>';
		}

		return $this->css('comments')
					->js('comments')
					->panel()
					->style('comments')
					->heading()
					->heading($this->label($module, $module_id)->align('right'))
					->body('<a name="comments"></a>'.$new.$this->_comments($module, $module_id)->view('comment'));
	}

	public function admin($module, $module_id)
	{
		return '<a href="'.url('admin/comments/'.url_title($module).'/'.$module_id).'">'.$this->count($module, $module_id).'</a>';
	}

	public function link($module, $module_id, $url)
	{
		return '<a href="'.url($url.'#comments').'">'.$this->label($module, $module_id).'</a>';
	}

	public function count($module, $module_id)
	{
		return $this->_comments($module, $module_id)->count();
	}

	public function label($module, $module_id)
	{
		return parent::label($this->no_translate($this->count($module, $module_id)), $this->info()->icon);
	}

	public function delete($module, $module_id)
	{
		return $this->_comments($module, $module_id)->delete();
	}

	protected function _comments($module, $module_id)
	{
		static $comments = [];

		if (!isset($comments[$module][$module_id]))
		{
			$comments[$module][$module_id] = $this	->collection('comment')
													->where('module', $module)
													->where('module_id', $module_id)
													->order_by('IFNULL(parent_id, id) DESC');
		}

		return $comments[$module][$module_id];
	}
}
