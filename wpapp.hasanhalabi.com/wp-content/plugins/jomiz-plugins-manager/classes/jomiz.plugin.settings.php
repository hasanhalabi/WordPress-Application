<?php

/**
 * JoMiz System Settings
 */
class jomiz_plugin_system_settings {

    // Member
    private static $instance;
    private $settings;

    private function __construct() {
        $settings = array();

        $systemSettingsPods = pods("jomiz_systemsettings");

        $this->__set('logoSmall', ($systemSettingsPods->field('logo_small._src') == '' ? get_template_directory_uri() . '/img/logo-small.png' : $systemSettingsPods->field('logo_small._src')));
        $this->__set('logoLogin', ($systemSettingsPods->field('logo_login._src') == '' ? get_template_directory_uri() . '/img/logo-300-1.png' : $systemSettingsPods->field('logo_login._src')));
        $this->__set('compLogo', ($systemSettingsPods->field('company_logo._src') == '' ? $this->__get('logoLogin') : $systemSettingsPods->field('company_logo._src')));
        $this->__set('compName', ($systemSettingsPods->field('company_name._text') == '' ? get_bloginfo("name") : $systemSettingsPods->field('company_name._text')));
        $this->__set('page_size', ($systemSettingsPods->field('page_size') == '' ? 25 : $systemSettingsPods->field('page_size')));
        $this->printsHeader = $systemSettingsPods->field('prints_header._src');
    }

    public function __get($key) {
        return $this->settings[$key];
    }

    public function __set($key, $value) {
        $this->settings[$key] = $value;
    }

    public static function register_global_object() {
        global $jomizSystemSettings;

        if (!isset(self::$instance)) {
            if (class_exists('jomiz_plugin_system_settings')) {
                self::$instance = new jomiz_plugin_system_settings();
            }
        }

        $jomizSystemSettings = self::$instance;
    }

}

?>