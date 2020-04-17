<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Install;

use NF\NeoFrag\Loadables\Install;

class Alpha_0_2_2 extends Install
{
	public function up()
	{
		if (function_exists('gzinflate'))
		{
			$icons = json_decode(gzinflate(base64_decode('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')), TRUE);

			foreach ([
					'nf_events_types' => 'type_id',
					'nf_groups'       => 'group_id',
					'nf_recruits'     => 'recruit_id'
				] as $table => $id)
			{
				foreach ($this->db()->select($id, 'icon')->from($table)->where('icon <>', '')->get() as $data)
				{
					if (array_key_exists($data['icon'], $icons))
					{
						$this->db()	->where($id, $data[$id])
									->update($table, [
										'icon' => $icons[$data['icon']]
									]);
					}
				}
			}
		}

		$this->db	->execute('ALTER TABLE `nf_events_types` CHANGE `icon` `icon` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_groups` CHANGE `icon` `icon` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');

		$this->config('nf_copyright', preg_replace('/pull-(left|right)/', 'float-\1', $this->config->nf_copyright));

		dir_remove('install');
		@rename('.htaccess_old', '.htaccess');

		$config = file_get_contents('config/email.php');

		foreach ([
					'smtp'     => 'host',
					'username' => '',
					'password' => '',
					'secure'   => '',
					'port'     => ''
				]
			as $key => $name)
		{
			$value = addcslashes(utf8_html_entity_decode($this->config->{'nf_email_'.$key}), '\'');
			$this->config->unset('nf_email_'.$key);

			if ($key == 'secure' && !in_array($value, ['tls', 'ssl']))
			{
				$value = $value ? 'tls' : ($this->config->nf_email_port != 25 ? 'ssl' : '');
			}
			else if ($key == 'port')
			{
				$value = intval($value);
			}

			$config = preg_replace_callback('/(\''.($name ?: $key).'\' +=> (\'?))(.*?)(\2,?)$/m', function($match) use ($value){
				unset($match[0], $match[2]);
				$match[3] = $value;
				return implode($match);
			}, $config);
		}

		file_put_contents('config/email.php', $config);

		$this->db->execute('ALTER TABLE `nf_user_profile` CHANGE `user_id` `id` INT(11) UNSIGNED NOT NULL');

		$this->config('nf_registration_status', !$this->nf_registration_status, 'bool');
		$this->config('nf_favicon',             0, 'int');

		$this->db	->execute('ALTER TABLE `nf_user` DROP INDEX `username`, ADD INDEX `username` (`username`)')
					->execute('ALTER TABLE `nf_user` DROP INDEX `email`, ADD INDEX `email` (`email`)');

		$this->db	->execute('ALTER TABLE `nf_user_profile` ADD INDEX( `cover`)')
					->execute('ALTER TABLE `nf_user_profile` ADD FOREIGN KEY (`cover`) REFERENCES `nf_file`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');

		$this->config('nf_analytics', preg_match('/UA-\d+-\d+/', $this->config->nf_analytics, $match) ? $match[0] : '');

		$this->db->execute('ALTER TABLE `nf_settings` CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL');

		$this->db()->insert('nf_addon', [
			'type_id' => 3,
			'name'    => 'socials',
			'data'    => 'a:1:{s:7:"enabled";b:1;}'
		]);

		foreach ($this->db()->select('widget_id', 'settings')->from('nf_widgets')->where('widget', 'header')->where('type', 'index')->get() as $widget)
		{
			$settings = [];

			foreach (unserialize($widget['settings']) as $key => $value)
			{
				$settings[str_replace('-', '_', $key)] = $value;
			}

			$this->db()	->where('widget_id', $widget['widget_id'])
						->update('nf_widgets', [
							'settings' => serialize($settings)
						]);
		}

		foreach ($this->db()->select('disposition_id', 'disposition')->from('nf_dispositions')->where('theme', 'azuro')->where('disposition LIKE', '%card-default%')->get() as $disposition)
		{
			$disposition['disposition'] = unserialize($disposition['disposition']);

			$disposition['disposition']->each($f = function($a) use (&$f){
				if (is_a($a, 'NF\NeoFrag\Displayables\Widget'))
				{
					if ($a->style() == 'card-default')
					{
						$a->style('');
					}
				}
				else if ($a)
				{
					$a->each($f);
				}

				return $a;
			});

			$this->db()	->where('disposition_id', $disposition['disposition_id'])
						->update('nf_dispositions', [
							'disposition' => serialize($disposition['disposition'])
						]);
		}

		@array_map('unlink', [
			'css/icons/font-awesome.min.css',
			'fonts/font-awesome/FontAwesome.otf',
			'fonts/font-awesome/fontawesome-webfont.eot',
			'fonts/font-awesome/fontawesome-webfont.svg',
			'fonts/font-awesome/fontawesome-webfont.ttf',
			'fonts/font-awesome/fontawesome-webfont.woff',
			'fonts/font-awesome/fontawesome-webfont.woff2',
			'js/bootstrap-iconpicker.min.js',
			'js/bootstrap-iconpicker-iconset-fontawesome-4.7.0.min.js',
			'js/user-badge.js',
			'lib/geshi/geshi/sql.php',
			'lib/geshi/geshi.php',
			'lib/phpmailer/class.phpmailer.php',
			'lib/phpmailer/class.smtp.php'
		]);
	}
}
