<?php
// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain('hbd-theme', TEMPLATEPATH . '/languages');

//$locale = get_locale();
$locale = get_user_locale(get_current_user_id());


$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable($locale_file) )
	require_once($locale_file);
?>