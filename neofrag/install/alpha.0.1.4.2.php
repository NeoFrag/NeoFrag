<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class i_0_1_4_2 extends Install
{
	public function up()
	{
		$this	->config('nf_captcha_private_key', '', 'string')
				->config('nf_captcha_public_key', '', 'string')
				->config('nf_email_password', '', 'string')
				->config('nf_email_port', '25', 'int')
				->config('nf_email_secure', '', 'string')
				->config('nf_email_smtp', '', 'string')
				->config('nf_email_username', '', 'string')
				->config('nf_registration_charte', '', 'string')
				->config('nf_registration_status', '0', 'string')
				->config('nf_social_behance', '', 'string')
				->config('nf_social_deviantart', '', 'string')
				->config('nf_social_dribble', '', 'string')
				->config('nf_social_facebook', '', 'string')
				->config('nf_social_flickr', '', 'string')
				->config('nf_social_github', '', 'string')
				->config('nf_social_google', '', 'string')
				->config('nf_social_instagram', '', 'string')
				->config('nf_social_steam', '', 'string')
				->config('nf_social_twitch', '', 'string')
				->config('nf_social_twitter', '', 'string')
				->config('nf_social_youtube', '', 'string')
				->config('nf_team_biographie', '', 'string')
				->config('nf_team_creation', '', 'string')
				->config('nf_team_name', '', 'string')
				->config('nf_team_type', '', 'string')
				->config('nf_welcome', '0', 'bool')
				->config('nf_welcome_content', '', 'string')
				->config('nf_welcome_title', '', 'string')
				->config('nf_welcome_user_id', '0', 'int');
	}
}

/*
NeoFrag Alpha 0.1.5
./neofrag/install/alpha.0.1.php
*/