<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_members_m_members extends Model
{
	public function get_members($users = NULL)
	{
		if (is_array($users))
		{
			$this->db->where('u.user_id', $users);
		}
		
		return $this->db->select('u.user_id', 'u.username', 'u.email', 'u.registration_date', 'u.last_activity_date', 'u.admin', 'u.language', 'u.deleted', 'up.avatar', 'up.sex', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online')
						->from('nf_users u')
						->join('nf_users_profiles up', 'u.user_id = up.user_id')
						->join('nf_sessions       s',  'u.user_id = s.user_id')
						->where('u.deleted', FALSE)
						->group_by('u.username')
						->order_by('u.username')
						->get();
	}
}
