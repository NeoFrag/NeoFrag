<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Votes extends Library
{
	public function get_note($module_name, $module_id)
	{
		$this->db	->select('AVG(note)')
					->from('nf_votes')
					->where('module', $module_name)
					->where('module_id', (int)$module_id);

		return round($this->db->row(), 1);
	}
}
