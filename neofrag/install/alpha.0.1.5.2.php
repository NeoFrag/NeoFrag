<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class i_0_1_5_2 extends Install
{
	public function up()
	{
		$this->db	->execute('ALTER TABLE `nf_sessions_history` CHANGE `user_agent` `user_agent` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->config('nf_cookie_name', 'session')
					->delete('nf_sessions');
	}
}
