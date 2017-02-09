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

class i_0_1_6 extends Install
{
	public function up()
	{
		foreach ($this->db->from('nf_dispositions')->get() as $disposition)
		{
			$rows = unserialize(preg_replace('/O:\d+:"(Row|Col|Widget_View)"/', 'O:8:"stdClass"', preg_replace_callback('/s:\d+:"(.(?:Row|Col|Widget_View).+?)";/', function($a){
				return 's:'.strlen($a = preg_replace('/.*_(.+?)$/', '\1', $a[1])).':"'.$a.'";';
			}, $disposition['disposition'])));

			$new_disposition = [];

			foreach ($rows as $row)
			{
				$cols = [];

				if (!empty($row->cols))
				{
					foreach ($row->cols as $col)
					{
						$widgets = [];

						if (!empty($col->widgets))
						{
							foreach ($col->widgets as $widget)
							{
								$new_widget = $this->panel_widget($widget->widget_id);

								if (!empty($widget->style))
								{
									$new_widget->color(str_replace('panel-', '', $widget->style));
								}

								$widgets[] = $new_widget;
							}
						}

						$new_col = call_user_func_array([$this, 'col'], $widgets);

						if (!empty($col->size))
						{
							$new_col->size($col->size);
						}

						$cols[] = $new_col;
					}
				}

				$new_row = call_user_func_array([$this, 'row'], $cols);

				if (!empty($row->style))
				{
					$new_row->style($row->style);
				}

				$new_disposition[] = $new_row;
			}

			$this->db	->where('disposition_id', $disposition['disposition_id'])
						->update('nf_dispositions', [
							'disposition' => serialize($new_disposition)
						]);
		}

		$default_settings = [
			'default_background'                 => [0, 'int'],
			'nf_team_logo '                      => [0, 'int'],
			'nf_http_authentication'             => [FALSE, 'bool'],
			'nf_http_authentication_name'        => ['', 'string'],
			'nf_maintenance'                     => [FALSE, 'bool'],
			'nf_maintenance_opening'             => ['', 'string'],
			'nf_maintenance_title'               => ['', 'string'],
			'nf_maintenance_content'             => ['', 'string'],
			'nf_maintenance_logo'                => [0, 'int'],
			'nf_maintenance_background'          => [0, 'int'],
			'nf_maintenance_background_repeat'   => ['', 'string'],
			'nf_maintenance_background_position' => ['', 'string'],
			'nf_maintenance_background_color'    => ['', 'string'],
			'nf_maintenance_text_color'          => ['', 'string'],
			'nf_maintenance_facebook'            => ['', 'string'],
			'nf_maintenance_twitter'             => ['', 'string'],
			'nf_maintenance_google-plus'         => ['', 'string'],
			'nf_maintenance_steam'               => ['', 'string'],
			'nf_maintenance_twitch'              => ['', 'string']
		];

		foreach ($default_settings as $name => $setting)
		{
			list($value, $type) = $setting;

			if (!isset($this->config->$name))
			{
				$this->config($name, $value, $type);
			}
		}

		$this->db->execute('ALTER TABLE `nf_users_profiles` CHANGE `date_of_birth` `date_of_birth` DATE NULL DEFAULT NULL');
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/install/alpha.0.1.6.php
*/