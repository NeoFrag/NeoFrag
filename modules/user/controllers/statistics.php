<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Statistics extends Controller_Module
{
	public function statistics()
	{
		return [
			'registrations' => [
				'title' => 'Inscriptions',
				'data'  => function(){
					$this->db	->from('nf_user')
								->where('deleted', FALSE);

					return 'registration_date';
				}
			],
			'sessions' => [
				'title'    => 'Connections de membres',
				'group_by' => 'COUNT(DISTINCT user_id)',
				'data'     => function(){
					$this->db->from('nf_session_history');
					return 'date';
				}
			]
		];
	}
}
