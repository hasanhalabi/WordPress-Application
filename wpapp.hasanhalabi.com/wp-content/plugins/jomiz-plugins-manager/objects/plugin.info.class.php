<?php

/**
 * Plugin Static Info
 */
namespace jomizSys;

class plugin_info {
	public static function get_plugin_code() {
		return 'plugins-manager';
	}
	public static function generateObjectTableName($objectName) {
		global $wpdb;
		
		return sprintf ( '%1$s%2$s_%3$s_%4$s', $wpdb->prefix, 'jsys', 'appsettings', $objectName );
	}
}
		
