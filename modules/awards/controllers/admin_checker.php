<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Awards\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module_Checker;

class Admin_Checker extends Module_Checker
{
	public function index($page = '')
	{
		return [$this->module->pagination->get_data($this->model()->get_awards(), $page)];
	}

	public function add()
	{
		if (!$this->is_authorized('add_awards'))
		{
			$this->error->unauthorized();
		}

		return [];
	}

	public function _edit($award_id, $name)
	{
		if (!$this->is_authorized('modify_awards'))
		{
			$this->error->unauthorized();
		}

		if ($award = $this->model()->check_awards($award_id, $name))
		{
			return [
				$award['award_id'],
				$award['team_id'],
				$award['date'],
				$award['location'],
				$award['name'],
				$award['platform'],
				$award['game_id'],
				$award['ranking'],
				$award['participants'],
				$award['description'],
				$award['image_id'],
				$award['team_name'],
				$award['team_title'],
				$award['game_name'],
				$award['game_title']
			];
		}
	}

	public function delete($award_id, $name)
	{
		if (!$this->is_authorized('delete_awards'))
		{
			$this->error->unauthorized();
		}

		$this->ajax();

		if ($award = $this->model()->check_awards($award_id, $name))
		{
			return [$award['award_id'], $award['name']];
		}
	}
}
