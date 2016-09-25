<?php
require __DIR__ . '/tracy/src/tracy.php';
use Tracy\Debugger;
Debugger::enable(Debugger::DEVELOPMENT, PLUGINDIR . DS .'debugger'. DS . 'log');
Debugger::$strictMode = TRUE;

// class Debug
// {
	
	// public static function create() {		
		// return Debugger;
	// }
	
// }



