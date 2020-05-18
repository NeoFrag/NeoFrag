<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Addons\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Admin_Ajax extends Controller_Module
{
	public function install()
	{
		return $this->form2()
					->rule($this->form_file('addon')
								->mime('application/x-zip-compressed')
								->temp()
					)
					->success(function($data){
						if ($zip = zip_open($tmp_file = $data['addon']))
						{
							dir_create($tmp = dir_temp());

							while ($zip_entry = zip_read($zip))
							{
								$entry_name = zip_entry_name($zip_entry);

								if (zip_entry_open($zip, $zip_entry, 'r'))
								{
									if (($dir = dirname($entry_name)) && $dir != '.')
									{
										dir_create($tmp.'/'.$dir);
									}

									file_put_contents($tmp.'/'.$entry_name, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
								}

								zip_entry_close($zip_entry);
							}

							zip_close($zip);

							$folders = array_filter(scandir($tmp), function($a) use ($tmp){
								return !in_array($a, ['.', '..']) && is_dir($tmp.'/'.$a);
							});

							$install_addon = function ($dir, $types = NULL){
								if ($types === NULL)
								{
									$types = ['Module', 'Widget', 'Theme'];
								}
								else if (!is_array($types))
								{
									$types = (array)$types;
								}

								foreach (scandir($dir) as $filename)
								{
									if (!is_dir($file = $dir.'/'.$filename) &&
										preg_match('/^(.+?)\.php$/', $filename, $match) &&
										preg_match('/use NF\\\NeoFrag\\\Addons\\\('.implode('|', $types).');/m', $content = file_get_contents($file), $match2))
									{
										file_put_contents($file, preg_replace('/^(namespace )NF\\\/m', '\1NF_Temp\\', $content));

										require_once $file;

										try
										{
											$class = new \ReflectionClass('NF_Temp\\'.$match2[1].'s\\'.$match[1].'\\'.$match[1]);
										}
										catch (\ReflectionException $e)
										{
											break;
										}

										$addon = $class->newInstanceArgs([NeoFrag()]);

										$version = $addon->info()->version;
										$depends = $addon->info()->depends;

										$nf_version = $depends['neofrag'];

										if (!empty($version) && !empty($nf_version))
										{
											$type = strtolower($match2[1]);

											$addon = NeoFrag()->$type($name = strtolower($match[1]));

											if ($addon)
											{
												$update = TRUE;

												if (($cmp = version_compare($version, version_format($addon->info()->version))) === 0)
												{
													return [
														'warning' => 'Le '.$type.' '.$addon->info()->title.' est déjà installé en version '.$version
													];
												}
												else if ($cmp === -1)
												{
													return [
														'danger' => 'Le '.$type.' '.$addon->info()->title.' est déjà installé avec une version supérieure'
													];
												}
											}

											if (($cmp = version_compare($nf_version, version_format(NEOFRAG_VERSION))) !== 1)
											{
												file_put_contents($file, $content);
												dir_copy($dir, $type.'s/'.$name);

												if (!NeoFrag()->collection('addon')->where('name', $name)->where('type_id', $type_id = NeoFrag()->collection('addon_type')->where('name', $type)->row()->id)->row()->id)
												{
													NeoFrag()	->model2('addon')
																->set('name', $name)
																->set('type', $type_id)
																->set('data', [
																	'enabled' => TRUE
																])
																->create();
												}

												if ($addon = NeoFrag()->$type($name))
												{
													$addon->reset();

													return [
														'success' => 'Le '.$type.' '.$addon->info()->title.' a été '.(empty($update) ? 'installé' : 'mis-à-jour')
													];
												}

												return [
													'danger' => 'Le '.$type.' '.($addon ? $addon->info()->title : $name).' n\'a pas pu être '.(empty($update) ? 'installé' : 'mis-à-jour')
												];
											}

											return [
												'danger' => 'Le '.$type.' '.($addon ? $addon->info()->title : $name).' nécessite la version '.$nf_version.' de NeoFrag, veuillez mettre jour votre site'
											];
										}

										return [
											'danger' => 'Le composant ne peut pas être installé, veuillez vérifier la présence des numéros de version'
										];
									}
								}

								return [
									'danger' => 'Le composant ne peut pas être installé, veuillez vérifier son contenu'
								];
							};

							$types   = ['modules', 'widgets', 'themes'];

							$results = [
								'danger'  => [],
								'success' => [],
								'warning' => []
							];

							if (count($folders) == 1 && !in_array($folder = current($folders), $types))
							{
								$results = array_merge_recursive($results, $install_addon($tmp.'/'.$folder));
							}
							else
							{
								foreach (array_intersect($folders, $types) as $folder)
								{
									foreach (scandir($tmp.'/'.$folder) as $dir)
									{
										if (!in_array($dir, ['.', '..']) && is_dir($dir = $tmp.'/'.$folder.'/'.$dir))
										{
											$results = array_merge_recursive($results, $install_addon($dir, substr(ucfirst($folder), 0, -1)));
										}
									}
								}
							}

							dir_remove($tmp);
							unlink($tmp_file);

							foreach (array_filter($results) as $type => $messages)
							{
								foreach ($messages as $message)
								{
									notify($message, $type);
								}
							}

							$this->modal->dispose();
						}
					})
					->submit('Ajouter')
					->modal('Ajouter', 'fas fa-plus')
					->cancel();
	}
}
