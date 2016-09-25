<?php
/**
 * PHPIDS Settings
 */
class DebuggerSettings extends DebuggerModel
{
    public function __construct()
    {
        parent::__construct();

        if (!isset($this->SettingsCollection)) {
            Loader::loadComponents($this, ['SettingsCollection']);
        }
		
       Language::loadLang(
            'debugger_settings',
            null,
            PLUGINDIR . 'debugger' . DS . 'language' . DS
        );
		
    }
    /**
     * Fetches settings
     *
     * @param int $company_id
     * @return array
     */
    public function getSettings($company_id)
    {
        $supported = $this->supportedSettings();
        $company_settings = $this->SettingsCollection
            ->fetchSettings(null, $company_id);
        $settings = [];
        foreach ($company_settings as $setting => $value) {
            if (($index = array_search($setting, $supported)) !== false) {
                $settings[$index] = $value;
            }
        }
        return $settings;
    }

    /**
     * Set settings
     *
     * @param int $company_id
     * @param array $settings Key/value pairs
     */
    public function setSettings($company_id, array $settings)
    {
        if (!isset($this->Companies)) {
            Loader::loadModels($this, ['Companies']);
        }

        $valid_settings = [];
        foreach ($this->supportedSettings() as $key => $name) {
            if (array_key_exists($key, $settings)) {
                $valid_settings[$name] = $settings[$key];
            }
        }

        $this->Input->setRules($this->getRules($valid_settings));
        if ($this->Input->validates($valid_settings)) {
            $this->Companies->setSettings($company_id, $valid_settings);
        }
    }
	
    /**
     * Fetch supported settings
     *
     * @return array
     */
    public function supportedSettings()
    {
        return [
            'enable_plugin' => 'debugger.enable_plugin',
            'used_class' => 'debugger.used_class'
        ];
    }
	
    /**
     * Input validate rules
     *
     * @param array $vars
     * @return array
     */
    private function getRules($vars)
    {
        return [
            'debugger.enable_plugin' => [
				'format' => [
					'if_set' => true,
					'rule' => ["in_array", ["true", "false"]],	
                    'message' => $this->_('DebuggerSettings.!error.enable_plugin.valid')
                ]
            ],
            'debugger.used_class' => [
                'valid' => [		
                    'rule' => [[$this, 'isValidClass']],
                    'message' => $this->_('DebuggerSettings.!error.used_class.valid')
                ]
            ],
        ];
    }

	/**
     * Validate the class given
     *
     * @param string $day
     * @return boolean
     */
    public function isValidClass($class)
    {
		if(is_dir(PLUGINDIR . DS . 'debugger'. DS .'vendors' . DS . $class)) {
			return true;
		} else {
			return false;
		}
    }
}
