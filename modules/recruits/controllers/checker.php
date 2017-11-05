<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class m_recruits_c_checker extends Controller_Module
{
	public function index($page = '')
	{
		$recruits = $this->model()->get_recruits();

		foreach ($recruits as $key => $recruit)
		{
			if (($recruit['closed'] || ($recruit['candidacies_accepted'] >= $recruit['size']) || ($recruit['date_end'] && strtotime($recruit['date_end']) < time())) && $this->config->recruits_hide_unavailable)
			{
				unset($recruits[$key]);
			}
		}

		return [$this->pagination->fix_items_per_page($this->config->recruits_per_page)->get_data($recruits, $page)];
	}

	public function _recruit($recruit_id, $title)
	{
		if ($recruit = $this->model()->check_recruit($recruit_id, $title))
		{
			return [
				'recruit_id'           => $recruit['recruit_id'],
				'title'                => $recruit['title'],
				'introduction'         => $recruit['introduction'],
				'description'          => $recruit['description'],
				'requierments'         => $recruit['requierments'],
				'date'                 => $recruit['date'],
				'user_id'              => $recruit['user_id'],
				'size'                 => $recruit['size'],
				'role'                 => $recruit['role'],
				'icon'                 => $recruit['icon'],
				'date_end'             => $recruit['date_end'],
				'closed'               => $recruit['closed'],
				'team_id'              => $recruit['team_id'],
				'image_id'             => $recruit['image_id'],
				'username'             => $recruit['username'],
				'avatar'               => $recruit['avatar'],
				'sex'                  => $recruit['sex'],
				'candidacies'          => $recruit['candidacies'],
				'candidacies_pending'  => $recruit['candidacies_pending'],
				'candidacies_accepted' => $recruit['candidacies_accepted'],
				'candidacies_declined' => $recruit['candidacies_declined'],
				'team_name'            => $recruit['team_name']
			];
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _postulate($recruit_id, $title)
	{
		if ($recruit = $this->model()->check_recruit($recruit_id, $title))
		{
			if ($this->access('recruits', 'recruit_postulate', $recruit['recruit_id']) && !$recruit['closed'] && ($recruit['candidacies_accepted'] < $recruit['size']) && (!$recruit['date_end'] || strtotime($recruit['date_end']) > time()))
			{
				return [
					'recruit_id'           => $recruit['recruit_id'],
					'title'                => $recruit['title'],
					'introduction'         => $recruit['introduction'],
					'description'          => $recruit['description'],
					'requierments'         => $recruit['requierments'],
					'date'                 => $recruit['date'],
					'user_id'              => $recruit['user_id'],
					'size'                 => $recruit['size'],
					'role'                 => $recruit['role'],
					'icon'                 => $recruit['icon'],
					'date_end'             => $recruit['date_end'],
					'closed'               => $recruit['closed'],
					'team_id'              => $recruit['team_id'],
					'image_id'             => $recruit['image_id'],
					'username'             => $recruit['username'],
					'avatar'               => $recruit['avatar'],
					'sex'                  => $recruit['sex'],
					'candidacies'          => $recruit['candidacies'],
					'candidacies_pending'  => $recruit['candidacies_pending'],
					'candidacies_accepted' => $recruit['candidacies_accepted'],
					'candidacies_declined' => $recruit['candidacies_declined'],
					'team_name'            => $recruit['team_name']
				];
			}

			throw new Exception(NeoFrag::UNAUTHORIZED);
		}

		throw new Exception(NeoFrag::UNFOUND);
	}

	public function _candidacy($candidacy_id, $title)
	{
		if ($this->user())
		{
			if ($candidacy = $this->model()->check_candidacy($candidacy_id, $title))
			{
				if ($this->user('user_id') == $candidacy['user_id'])
				{
					return $candidacy;
				}

				throw new Exception(NeoFrag::UNAUTHORIZED);
			}

			throw new Exception(NeoFrag::UNFOUND);
		}

		throw new Exception(NeoFrag::UNCONNECTED);
	}
}
