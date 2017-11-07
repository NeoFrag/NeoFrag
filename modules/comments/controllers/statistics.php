<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_comments_c_statistics extends Controller_Module
{
	public function statistics()
	{
		return [
			'comments' => [
				'title' => 'Commentaires',
				'data'  => function(){
					$this->db->from('nf_comments');
					return 'date';
				}
			]
		];
	}
}
