<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries\Forms;

class File extends Labelable
{
	protected $_mimes = [];
	protected $_thumbnail;
	protected $_uploaded;
	protected $_precheck;
	protected $_temp;

	public function __invoke($name, $upload_dir = '')
	{
		$this->_template[] = function(&$input){
			$input = parent	::html('input', TRUE)
							->attr('type', 'file')
							->attr_if($this->_disabled, 'disabled');
		};

		parent::__invoke($name);

		$this->_template[] = function(&$input){
			if ($this->_thumbnail)
			{
				$input = parent	::html()
								->content(call_user_func_array($this->_thumbnail, []))
								->append($input);
			}
		};

		$this->_check[1] = function($post, &$data) use ($upload_dir){
			if (($this->_required && !$this->_value) || !empty($_FILES[$this->_name]['name']))
			{
				if (!empty($_FILES[$this->_name]['error']))
				{
					$errors = [
						1 => 'La taille du fichier téléchargé excède la valeur de upload_max_filesize, configurée dans le php.ini',
						2 => 'La taille du fichier téléchargé excède la valeur de MAX_FILE_SIZE, qui a été spécifiée dans le formulaire HTML',
						3 => 'Le fichier n\'a été que partiellement téléchargé',
						4 => 'Aucun fichier n\'a été téléchargé',
						6 => 'Un dossier temporaire est manquant',
						7 => 'Échec de l\'écriture du fichier sur le disque',
						8 => 'Une extension PHP a arrêté l\'envoi de fichier'
					];

					$this->_errors[] = $this->lang($errors[$_FILES[$this->_name]['error']]);
				}
				else if ($this->_mimes && !in_array($_FILES[$this->_name]['type'], $this->_mimes))
				{
					$this->_errors[] = NeoFrag()->lang('Type de fichier non autorisé');
				}
				else if (!empty($_FILES[$this->_name]['tmp_name']))
				{
					if (($this->_precheck && call_user_func_array($this->_precheck, [$_FILES[$this->_name]['tmp_name']])) || $this->_temp)
					{
						$data[$this->_name] = $_FILES[$this->_name]['tmp_name'];
					}
					else if (!$this->_errors)
					{
						$data[$this->_name] = NeoFrag()->model2('file')->static_uploaded_file($_FILES[$this->_name], $upload_dir, $this->_value ? $this->_value->id : NULL);

						if ($data[$this->_name]->path())
						{
							if ($this->_uploaded)
							{
								call_user_func_array($this->_uploaded, [$data[$this->_name]]);
							}
						}
						else
						{
							$this->_errors[] = NeoFrag()->lang('Erreur de transfert');
						}
					}
				}
			}
		};

		return $this;
	}

	public function value($file, $erase = FALSE)
	{
		if (!is_a($file, 'NF/NeoFrag/Models/File') && ($file = NeoFrag()->model2('file', $file)) && !$file())
		{
			$file = NULL;
		}

		return parent::value($file, $erase);
	}

	public function mime($mime)
	{
		$this->_mimes[] = $mime;
		return $this;
	}

	public function uploaded($uploaded)
	{
		$this->_uploaded = $uploaded;
		return $this;
	}

	public function temp()
	{
		$this->_temp = TRUE;
		return $this;
	}
}
