<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Comments\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax extends Controller_Module
{
	public function delete($comment_id, $module_id, $module)
	{
		$this	->title($this->lang('Confirmation de suppression'))
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer ce commentaire ?'));

		if ($this->form()->is_valid())
		{
			if ($this->db->select('id')->from('nf_comment')->where('module', $module)->where('module_id', $module_id)->order_by('id DESC')->row() == $comment_id)
			{
				$this->db	->where('id', $comment_id)
							->delete('nf_comment');
			}
			else
			{
				$this->db	->where('id', $comment_id)
							->update('nf_comment', [
								'content' => NULL
							]);
			}

			return 'OK';
		}

		return $this->form()->display();
	}
}
