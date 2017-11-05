<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class i_0_1_6_1 extends Install
{
	public function up()
	{
		foreach ($this->db->from('nf_dispositions')->get() as $disposition)
		{
			$this->db	->where('disposition_id', $disposition['disposition_id'])
						->update('nf_dispositions', [
							'disposition' => preg_replace_callback('/s:\d+:"((.)\*\2_color)";(.*?;)/', function($a){
								$style = unserialize($a[3]);

								if ($style && !preg_match('/^panel-/', $style))
								{
									$style = 'panel-'.$style;
								}

								return 's:'.strlen($a = $a[2].'*'.$a[2].'_style').':"'.$a.'";'.serialize($style);
							}, $disposition['disposition'])
						]);
		}
	}
}
