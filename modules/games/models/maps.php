<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Games\Models;

use NF\NeoFrag\Loadables\Model;

class Maps extends Model
{
	public function get_maps($game_id = NULL)
	{
		if ($game_id)
		{
			$this->db->where('m.game_id', $game_id);
		}

		return $this->db->select('m.*', 'g.name', 'g.icon_id', 'gl.title as game_title')
						->from('nf_games_maps m')
						->join('nf_games g', 'g.game_id = m.game_id')
						->join('nf_games_lang gl', 'gl.game_id = m.game_id')
						->where('gl.lang', $this->config->lang->info()->name)
						->order_by('gl.title', 'm.title')
						->get();
	}

	public function check_map($map_id, $title)
	{
		$map = $this->db->select('m.*', 'g.name')
						->from('nf_games_maps m')
						->join('nf_games g', 'g.game_id = m.game_id')
						->where('m.map_id', $map_id)
						->row();

		if ($map && url_title($map['title']) == $title)
		{
			return $map;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_map($game_id, $title, $image)
	{
		return $this->db->insert('nf_games_maps', [
			'game_id'  => $game_id,
			'title'    => $title,
			'image_id' => $image
		]);
	}

	public function edit_map($map_id, $game_id, $title, $image)
	{
		$this->db	->where('map_id', $map_id)
					->update('nf_games_maps', [
						'game_id'  => $game_id,
						'title'    => $title,
						'image_id' => $image
					]);
	}

	public function delete_map($map_id)
	{
		NeoFrag()->model2('file', $this->db->select('image_id')->from('nf_games_maps')->where('map_id', $map_id)->row())->delete();

		$this->db	->where('map_id', $map_id)
					->delete('nf_games_maps');
	}
}
