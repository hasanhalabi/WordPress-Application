<?php
if (! function_exists ( 'jomiz_include_directory' )) {
	function jomiz_include_directory($path) {
		$files = glob ( sprintf ( '%s/*.php', $path ) );
		
		foreach ( $files as $filename ) {
			include_once $filename;
		}
	}
}

include_once 'classes/jomiz_plugin.php';
include_once 'classes/jomiz.api.object.type.php';
include_once 'classes/jomiz.api.data.source.php';
include_once 'classes/jomizSys.utilities.class.php';

jomiz_include_directory ( "wp-content/plugins/jomiz-plugins-manager/objects" );
