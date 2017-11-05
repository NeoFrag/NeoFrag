<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */
 
class t_default_c_admin extends Controller
{
	public function index($theme)
	{
		$this	->js('admin')
				->form
				->add_rules([
					'background' => [
						'label'  => $this->lang('background'),
						'value'  => $this->config->{'default_background'},
						'type'   => 'file',
						'upload' => 'themes/default/backgrounds',
						'info'   => $this->lang('file_picture', file_upload_max_size() / 1024 / 1024),
						'check'  => function($filename, $ext){
							if (!in_array($ext, ['gif', 'jpeg', 'jpg', 'png']))
							{
								return $this->lang('select_image_file');
							}
						}
					],
					'repeat' => [
						'label'  => $this->lang('background_repeat'),
						'value'  => $this->config->{'default_background_repeat'},
						'values' => [
							'no-repeat' => $this->lang('no'),
							'repeat-x'  => $this->lang('horizontally'),
							'repeat-y'  => $this->lang('vertically'),
							'repeat'    => $this->lang('both')
						],
						'type'   => 'radio',
						'rules'  => 'required'
					],
					'positionX' => [
						'label'  => $this->lang('position'),
						'value'  => explode(' ', $this->config->{'default_background_position'})[0],
						'values' => [
							'left'   => $this->lang('left'),
							'center' => $this->lang('center'),
							'right'  => $this->lang('right')
						],
						'type'   => 'radio',
						'rules'  => 'required'
					],
					'positionY' => [
						'value'  => explode(' ', $this->config->{'default_background_position'})[1],
						'values' => [
							'top'    => $this->lang('top'),
							'center' => $this->lang('middle'),
							'bottom' => $this->lang('bottom')
						],
						'type'   => 'radio',
						'rules'  => 'required'
					],
					'fixed' => [
						'value'  => $this->config->{'default_background_attachment'},
						'values' => [
							'on'  => $this->lang('background_fixed')
						],
						'type'   => 'checkbox'
					],
					'color' => [
						'label' => $this->lang('background_color'),
						'value' => $this->config->{'default_background_color'},
						'type'  => 'colorpicker',
						'rules' => 'required'
					]
				])
				->add_submit($this->lang('save'));

		if ($this->form->is_valid($post))
		{
			if ($post['background'])
			{
				$this->config('default_background', $post['background'], 'int');
			}
			else
			{
				$this->db->where('name', 'default_background')->delete('nf_settings');
			}
			
			$this	->config('default_background_repeat', $post['repeat'])
					->config('default_background_attachment', in_array('on', $post['fixed']) ? 'fixed' : 'scroll')
					->config('default_background_position', $post['positionX'].' '.$post['positionY'])
					->config('default_background_color', $post['color'])
					->config('nf_version_css', time());

			redirect('#background');
		}
		
		return $this->row(
			$this	->col(
						$this	->panel()
								->body($this->view('admin/menu', [
									'theme_name' => $theme->name
								]), FALSE)
					)
					->size('col-md-4 col-lg-3'),
			$this	->col(
						$this	->panel()
								->heading($this->lang('dashboard'), 'fa-cog')
								->body($this->view('admin/index', [
									'theme'           => $theme,
									'form_background' => $this->form->display()
								]))
					)
					->size('col-md-8 col-lg-9')
		);
	}
}
