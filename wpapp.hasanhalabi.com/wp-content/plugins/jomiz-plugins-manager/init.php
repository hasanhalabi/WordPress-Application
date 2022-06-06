<?php

/*
  Plugin Name: LC System Plugin Manager
  Plugin URI: http://levantcon.com
  Description: Handle The Connections Between LC Plugins
  Version: 2.0.1
  Author: LC Team
  Author URI: http://levantcon.com
  Last Updated: 2018-04-03
 * GitHub Plugin URI: hasanhalabi/jomiz-plugins-manager
 */
define('LC_PLUGIN_DIR_PATH', dirname(__FILE__));
define('LC_PLUGIN_DIR_URL', plugins_url(basename(dirname(__FILE__))));
include_once 'files-to-include.php';
include_once 'classes/PagesTemplater.php';
include_once 'classes/jomiz.plugin.settings.php';
include_once 'classes/jomiz.user.info.php';
include_once 'classes/jomizSysMenus.php';
include_once 'classes/lc.licensing.class.php';
include_once 'classes/lc.manager.engine.class.php';

// Init The Plugin
function lc_manager_script() {


    wp_register_script('lc.manager.Ajax.js', LC_PLUGIN_DIR_URL . '/js/lc.manager.Ajax.js', array());
    wp_enqueue_script('lc.manager.Ajax.js');
    wp_localize_script('lc.manager.Ajax.js', 'lc_manager_api', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('admin_enqueue_scripts', 'lc_manager_script');
$args = (object) array(
            "configuration" => file_get_contents(__DIR__ . '/jomiz-plugin-settings.json'),
            "db_script" => file_get_contents(__DIR__ . '/plugin-db-script.sql'),
            "plugin_pysical_path" => __DIR__
);

function lc_setup_menu() {
    add_menu_page('lc-accounting', 'LC Accounting', 'manage_options', 'lc-licensing-accounting', 'lc_primary_menu', '');
}

add_action('admin_menu', 'lc_setup_menu');

function lc_primary_menu() {

    require LC_PLUGIN_DIR_PATH . '/pages/lc_licensing.php';
}

function lc_manager_ajax_lc_manager_api() {

    $json = $_POST['data'];

    $requestData = json_decode(stripslashes($_POST['data']));

    lc_manager\lc_manager_engine::lc_manager_validate_request($requestData);

    lc_manager\lc_manager_engine::lc_manager_process_request($requestData);
}

add_action('wp_ajax_lc_manager_api', 'lc_manager_ajax_lc_manager_api');

$mainPluginObj = new jomiz_plugin($args);

add_action('activated_plugin', array(
    $mainPluginObj,
    'plugin_activation_hook'
        ), 10, 2);

add_action('deactivated_plugin', array(
    $mainPluginObj,
    'plugin_deactivation_hook'
        ), 10, 2);

add_action('plugins_loaded', array(
    'PageTemplater',
    'getInstance'
));
?>
