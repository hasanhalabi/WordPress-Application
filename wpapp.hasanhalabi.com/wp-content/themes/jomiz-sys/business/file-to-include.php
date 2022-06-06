<?php

	if (!function_exists('jomiz_include_directory')) {
		function jomiz_include_directory($path) {
			$files = glob(sprintf('%s/*.php', $path));

			foreach ($files as $filename) {
				include_once $filename;
			}
		}
	}

	include_once 'string_utilities.php';
	include_once 'languages-tools.php';
	include_once 'core.php';
	include_once 'jomiz.api.php';
	include_once 'html.templates.class.php';
