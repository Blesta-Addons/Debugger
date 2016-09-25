<?php
/**
 * Debugger manage plugin controller
 * 
 */
class AdminManagePlugin extends AppController 
{
	
	/**
	 * Performs necessary initialization
	 */
	private function init() 
	{
		// Require login
		$this->parent->requireLogin();
		$this->uses(["Debugger.DebuggerSettings"]);

		Language::loadLang("debugger_manage_plugin", null, PLUGINDIR . "debugger" . DS . "language" . DS);

		$this->view->setView(null, "Debugger.default");

		// Set the page title
		$this->parent->structure->set(
			"page_title", 
			Language::_(
				'DebuggerManagePlugin.' . Loader::fromCamelCase($this->action ? $this->action : 'index') . '.page_title', 
				true
			)
		);
	}

	/**
	 * Returns the view to be rendered when managing this plugin
	 */
	public function index() 
	{
        $this->init();

        $vars = (object) $this->DebuggerSettings
            ->getSettings($this->parent->company_id);

        if (!empty($this->post)) {
			
            if (!isset($this->post['enable_plugin'])) {
                $this->post['enable_plugin'] = 'false';
            }
			
            $this->DebuggerSettings->setSettings(
                $this->parent->company_id,
                $this->post
            );

            if (($error = $this->DebuggerSettings->errors())) {
                $this->parent->setMessage('error', $error);
            } else {
                $this->parent->setMessage(
                    'message',
                    Language::_('AdminManagePlugin.!success.settings_saved', true)
                );
            }

            $vars = (object) $this->post;
        }

        $debuggers = $this->getDebuggers();
        // Set the view to render
        return $this->partial(
            'admin_manage_plugin',
            compact('vars', 'debuggers')
        );
	}

	
	/**
	 * Returns an array of key/value time intervals where the key is the number
	 * of seconds representing the interval and the value is the description of
	 * that interval.
	 * 
	 * @return array An array of temporal intervals (key/value pairs)
	 */
	private function getDebuggers() {
		$debuggers = [
			'tracy' => 'Tracy, The Easy !!!',
			'kint' => 'Kint -experimental-',
			// 'php-debugbar' => 'PHP DebugBar (Not Supported)',
		];

		return $debuggers;
	}
}
