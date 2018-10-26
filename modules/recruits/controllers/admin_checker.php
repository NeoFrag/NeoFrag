<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Recruits\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [$this->module->pagination->get_data($this->model()->get_recruits(), $page)];
	}

	public function add()
	{
		if (!$this->is_authorized('add_recruit'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _edit($recruit_id, $title)
	{
		if (!$this->is_authorized('modify_recruit'))
		{
			$this->error->unauthorized();
		}

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
	}

	public function delete($recruit_id, $title)
	{
		if (!$this->is_authorized('delete_recruit'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($recruit = $this->model()->check_recruit($recruit_id, $title))
		{
			return [$recruit['recruit_id'], $recruit['title']];
		}
	}

	public function _candidacies($recruit_id, $title)
	{
		if(!$this->is_authorized('candidacy_vote') && !$this->is_authorized('candidacy_reply'))
		{
			$this->error->unauthorized();
		}

		if ($recruit = $this->model()->check_recruit($recruit_id, $title))
		{
			return [$recruit['recruit_id'], $recruit['title']];
		}
	}

	public function _candidacies_edit($candidacy_id, $title)
	{
		if(!$this->is_authorized('candidacy_vote') && !$this->is_authorized('candidacy_reply'))
		{
			$this->error->unauthorized();
		}

		if ($candidacy = $this->model()->check_candidacy($candidacy_id, $title))
		{
			return [
				$candidacy['candidacy_id'],
				$candidacy['date'],
				$candidacy['user_id'],
				$candidacy['pseudo'],
				$candidacy['email'],
				$candidacy['date_of_birth'],
				$candidacy['presentation'],
				$candidacy['motivations'],
				$candidacy['experiences'],
				$candidacy['status'],
				$candidacy['reply'],
				$candidacy['recruit_id'],
				$candidacy['title'],
				$candidacy['icon'],
				$candidacy['role'],
				$candidacy['team_id'],
				$candidacy['team_name'],
				$candidacy['username'],
				$candidacy['avatar'],
				$candidacy['sex']
			];
		}
	}

	public function _candidacies_delete($candidacy_id, $title)
	{
		if (!$this->is_authorized('candidacy_delete'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($candidacy = $this->model()->check_candidacy($candidacy_id, $title))
		{
			return [$candidacy['candidacy_id'], $candidacy['pseudo'], $candidacy['title']];
		}
	}
}
