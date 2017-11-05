<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class Loader extends NeoFrag
{
	public $libraries   = [];
	public $helpers     = [];
	public $controllers = [];
	public $models      = [];
	public $views       = [];
	public $forms       = [];
	public $langs       = [];
	public $data        = [];
	public $caller;

	protected $_paths;

	public function __construct()
	{
		$args = func_get_args();

		$this->_paths = array_pop($args);

		if ($args)
		{
			$this->caller = array_pop($args);
		}

		$this->load = $this;
	}

	public function paths($type = NULL)
	{
		$paths = is_a($this->_paths, 'closure') ? call_user_func_array($this->_paths, []) : $this->_paths;

		if (NeoFrag() != $this)
		{
			$paths = array_merge_recursive($paths, NeoFrag()->paths());
		}

		return $type ? $paths[$type] : $paths;
	}

	public function debugbar($title = 'Loader')
	{
		$output = '<span class="label label-info">'.$title.(property_exists($this, 'override') && $this->override ? ' '.icon('fa-code-fork') : '').'</span>';

		$this->debug->timeline($output, $this->time[0], $this->time[1]);

		$output = '	<ul>
						<li>
							'.$output;

		foreach ([
			[isset($this->modules) ? $this->modules : [], 'Modules',     'default', function($a){ return $a->debug('default'); }],
			[isset($this->themes) ?  $this->themes : [],  'Themes',      'primary', function($a){ return $a->debug('primary'); }],
			[isset($this->widgets) ? $this->widgets : [], 'Widgets',     'success', function($a){ return $a->debug('success'); }],
			[$this->libraries,                            'Libraries',   'info',    function($a){ return $a->debug('info'); }],
			[$this->helpers,                              'Helpers',     'warning', function($a){ return '<span class="label label-warning">'.$a[1].'</span>'; }],
			[$this->controllers,                          'Controllers', 'danger',  function($a){ return $a->debug('danger'); }],
			[$this->models,                               'Models',      'default', function($a){ return $a->debug('default'); }],
			[$this->views,                                'Views',       'primary', function($a){ return '<span class="label label-primary">'.$a[1].'</span>'; }],
			[$this->forms,                                'Forms',       'success', function($a){ return '<span class="label label-success">'.$a[1].'</span>'; }],
			[$this->langs,                                'Locales',     'info',    function($a, $b){ return '<span class="label label-info">'.$b.'</span>'; }]
		] as $vars)
		{
			list($objects, $name, $class, $callback) = $vars;

			if ($objects = array_filter($objects))
			{
				$output .= '	<ul>
									<li>
										<span class="label label-'.$class.'">'.$name.'</span>
										<ul>';

				foreach ($objects as $key => $object)
				{
					$output .= '			<li>'.$callback($object, $key).(is_object($object) && property_exists($object, 'load') && $object->load != $this ? $object->load->debugbar() : '').'</li>';
				}

				$output .= '			</ul>
									</li>
								</ul>';
			}
		}

		return $output.'</li>
					</ul>';
	}
}
