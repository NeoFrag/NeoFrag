<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_user_c_statistics extends Controller_Module
{
	public function statistics()
	{
		return [
			'registrations' => [
				'title' => 'Inscriptions',
				'data'  => function(){
					$this->db	->from('nf_users')
								->where('deleted', FALSE);
					
					return 'registration_date';
				}
			],
			'sessions' => [
				'title'    => 'Connections de membres',
				'group_by' => 'COUNT(DISTINCT user_id)',
				'data'     => function(){
					$this->db->from('nf_sessions_history');
					return 'date';
				}
			],
			'crawlers' => [
				'title' => 'Connections de bots',
				'data'  => function(){
					$this->db->from('nf_crawlers');
					return 'date';
				}
			]
		];
	}
}
