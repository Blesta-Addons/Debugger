<?php
/**
 * Debugger plugin handler
 * 
 */
class DebuggerPlugin extends Plugin {

	/**
	 * @var string The version of this plugin
	 */
	private static $version = "1.0.0";
	/**
	 * @var string The authors of this plugin
	 */
	private static $authors = [['name'=>"Mohamed Anouar Achoukhy",'url'=>"http://www.blesta-addons.com"]];
	
	public function __construct() 
	{
		Language::loadLang("debugger_plugin", null, dirname(__FILE__) . DS . "language" . DS);
	}
	
	/**
	 * Returns the name of this plugin
	 *
	 * @return string The common name of this plugin
	 */
	public function getName() {
		return "Debugger Tools";	
	}
	
	/**
	 * Returns the version of this plugin
	 *
	 * @return string The current version of this plugin
	 */
	public function getVersion() {
		return self::$version;
	}

	/**
	 * Returns the name and URL for the authors of this plugin
	 *
	 * @return array The name and URL of the authors of this plugin
	 */
	public function getAuthors() {
		return self::$authors;
	}
	
	/**
	 * Performs any necessary bootstraping actions
	 *
	 * @param int $plugin_id The ID of the plugin being installed
	 */
	public function install($plugin_id) {
	
	}
	
	
	/**
	 * Returns all events to be registered for this plugin (invoked after install() or upgrade(), overwrites all existing events)
	 *
	 * @return array A numerically indexed array containing:
	 * 	- event The event to register for
	 * 	- callback A string or array representing a callback function or class/method. If a user (e.g. non-native PHP) function or class/method, the plugin must automatically define it when the plugin is loaded. To invoke an instance methods pass "this" instead of the class name as the 1st callback element.
	 */	
	public function getEvents() {
		return array(
			array(
				'event' => "Appcontroller.preAction",
				'callback' => ["this", "debugger"]
			)
		);
	}
	
	/**
	 * Runs PHPIDS
	 *
	 * @param EventObject $event The event to process
	 */
	public function debugger($event) 
	{
		Loader::loadModels($this, ['Debugger.DebuggerSettings']);
		
        $settings = (object) $this->DebuggerSettings
            ->getSettings(Configure::get('Blesta.company_id'));

		if ($settings->enable_plugin == 'true') {

			switch ($settings->used_class) {
				case 'tracy' :
					Loader::load(dirname(__FILE__) . DS . "vendors" . DS ."tracy_autoload.php");				
				break;
				case 'kint' :
					Loader::load(dirname(__FILE__) . DS . "vendors" . DS ."kint_autoload.php");				
				break;
				
				default:
				break;				
			}
		}
	}
}
