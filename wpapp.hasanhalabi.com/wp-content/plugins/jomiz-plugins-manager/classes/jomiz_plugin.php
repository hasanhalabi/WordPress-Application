<?php


class jomiz_plugin {
	// Members
	private $plugin_configuration;
	private $plugin_db_script;
	private $plugin_pysical_path;
	private $plugin_admin_notices;
	
	// Constructors
	function __construct($args) {
		$this->plugin_admin_notices = array ();
		
		$this->plugin_configuration = ( object ) json_decode ( $args->configuration );
		
		
		$this->plugin_db_script = $args->db_script;
		$this->plugin_pysical_path = str_replace ( '\\', '/', $args->plugin_pysical_path );
		$this->plugin_configuration->plugin_pysical_path = $this->plugin_pysical_path;
	}
	private function init() {
		// Check Dependencies
		$all_dependencies_active = TRUE;
		
		foreach ( $this->plugin_configuration->plugin_info->dependencies as $value ) {
			
			$key = $value->plugin_path;
			if (! is_plugin_active ( $key )) {
				deactivate_plugins ( $this->plugin_configuration->plugin_info->plugin_path );
				
				$this->plugin_admin_notices [] = sprintf ( '%1$s: This plugin requires <a href="%2$s">%3$s</a> plugin to be active!', $this->plugin_configuration->plugin_info->title, $value->url, $value->title );
				
				$all_dependencies_active = FALSE;
			}
		}
		
		if ($all_dependencies_active) {
			
			$this->registerPlugin ();
			$this->install_plugin_pages ();
			$this->install_plugin_widgets ();
			$this->install_plugin_reports ();
			$this->execute_database ();
		} elseif (sizeof ( $this->plugin_admin_notices ) > 0) {
			add_action ( 'admin_notices', array (
					$this,
					'show_admin_notices' 
			) );
		}
	}
	private function execute_database() {
		/*
		 * global $wpdb;
		 *
		 * $sql = str_replace('WPDBPFIX_', $wpdb -> prefix, $this -> plugin_db_script);
		 *
		 *
		 * require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
		 * dbDelta($sql);
		 */
	}
	private function registerPlugin() {
		$plugin_data = array (
				'name' => $this->plugin_configuration->plugin_info->title,
				'plugin_code' => $this->plugin_configuration->plugin_info->code,
				'version' => $this->plugin_configuration->plugin_info->version,
				'configuration' => json_encode ( $this->plugin_configuration ),
				'db_script' => $this->plugin_db_script 
		);
		
		$params = array ();
		$params ['where'] = sprintf ( 'plugin_code = \'%1$s\' ', $this->plugin_configuration->plugin_info->code );
		$registerdPlugins = pods ( 'jomiz_registeredplugin', $params );
		
		if ($registerdPlugins->total_found () == 0) {
			pods ( 'jomiz_registeredplugin' )->add ( $plugin_data );
		} elseif ($plugin_params ['version'] != $registerdPlugins->field ( 'version' )) {
			$registerdPlugins->save ( $plugin_data );
		}
	}
	private function add_page($p_args, $page_type) {
		$the_page = get_page_by_path ( $p_args ['name'] );
		$the_page_id = - 1;
		
		if (! $the_page) {
			// Create post object
			$_p = array ();
			$_p ['post_title'] = $p_args ['name'];
			$_p ['post_content'] = '';
			$_p ['post_status'] = 'publish';
			$_p ['post_type'] = 'page';
			$_p ['comment_status'] = 'closed';
			$_p ['ping_status'] = 'closed';
			$_p ['post_category'] = array (
					1 
			);
			$_p ['post_parent'] = $p_args ['page_parent'];
			// the default 'Uncatrgorised'
			
			// Insert the post into the database
			$the_page_id = wp_insert_post ( $_p );
		} else {
			$the_page_id = $the_page->ID;
			
			// make sure the page is not trashed...
			$the_page->post_status = 'publish';
			$the_page_id = wp_update_post ( $the_page );
		}
		
		if ($the_page_id > 0 && $p_args ['template'] != "") {
			$template_path = sprintf ( '%1$s/%2$s/%3$s', $this->plugin_pysical_path, $page_type, $p_args ['template'] );
			
			delete_post_meta ( $the_page_id, '_wp_page_template' );
			add_post_meta ( $the_page_id, '_wp_page_template', $template_path, true );
		}
		
		return $the_page_id;
	}
	private function install_plugin_pages() {
		// Install Plugin Main Page
		$plugin_main_page_id = $this->add_page ( array (
				"name" => $this->plugin_configuration->plugin_info->root_page,
				"title" => "",
				"template" => "",
				"page_parent" => - 1 
		), 'pages' );
		
		foreach ( $this->plugin_configuration->pages as $plugin_page ) {
			$this->add_page ( array (
					"name" => $plugin_page->permalink,
					"title" => $plugin_page->title,
					"template" => $plugin_page->template,
					"page_parent" => $plugin_main_page_id 
			), 'pages' );
		}
	}
	private function install_plugin_reports() {
		$plugin_root_page = get_page_by_path ( $this->plugin_configuration->plugin_info->root_page );
		$plugin_root_page_id = $plugin_root_page->ID;
		
		// Install Plugin Reports Main Page
		$plugin_reports_page_id = $this->add_page ( array (
				"name" => 'reports',
				"title" => "",
				"template" => "",
				"page_parent" => $plugin_root_page_id 
		), 'reports' );
		
		foreach ( $this->plugin_configuration->reports as $plugin_page ) {
			$this->add_page ( array (
					"name" => $plugin_page->permalink,
					"title" => $plugin_page->title,
					"template" => $plugin_page->template,
					"page_parent" => $plugin_reports_page_id 
			), 'reports' );
		}
	}
	private function install_plugin_widgets() {
		$plugin_root_page = get_page_by_path ( $this->plugin_configuration->plugin_info->root_page );
		$plugin_root_page_id = $plugin_root_page->ID;
		
		// Install Plugin Reports Main Page
		$plugin_widgets_page_id = $this->add_page ( array (
				"name" => 'widgets',
				"title" => "",
				"template" => "",
				"page_parent" => $plugin_root_page_id 
		), 'widgets' );
		
		foreach ( $this->plugin_configuration->widgets as $plugin_page ) {
			$this->add_page ( array (
					"name" => $plugin_page->permalink,
					"title" => $plugin_page->title,
					"template" => $plugin_page->template,
					"page_parent" => $plugin_widgets_page_id 
			), 'widgets' );
		}
	}
	private function delete_pages($pages_to_delete) {
		foreach ( $pages_to_delete as $page_to_delete ) {
			wp_delete_post ( $page_to_delete, true );
		}
	}
	private function uninstall() {
		$pages_to_delete = array ();
		
		$params = array ();
		$params ['where'] = sprintf ( 'plugin_code = \'%1$s\' ', $this->plugin_configuration->plugin_info->code );
		$registerdPlugins = pods ( 'jomiz_registeredplugin', $params );
		
		$plugin_configuration = ( object ) json_decode ( $registerdPlugins->field ( 'configuration' ), true );
		
		$plugin_rootpage = $this->plugin_configuration->plugin_info->root_page;
		
		foreach ( $plugin_configuration->pages as $page ) {
			$page = ( object ) $page;
			
			$page_path = sprintf ( '%1$s/%2$s/', $plugin_rootpage, $page->permalink );
			
			$page_to_delete = get_page_by_path ( $page_path );
			$pages_to_delete [] = $page_to_delete->ID;
		}
		
		if (isset ( $plugin_configuration->widgets )) {
			foreach ( $plugin_configuration->widgets as $widget ) {
				$widget = ( object ) $widget;
				$page_path = sprintf ( '%1$s/%2$s/%3$s/', $plugin_rootpage, 'widgets', $widget->permalink );
				
				$page_to_delete = get_page_by_path ( $page_path );
				$pages_to_delete [] = $page_to_delete->ID;
			}
		}
		
		if (isset ( $plugin_configuration->reports )) {
			foreach ( $plugin_configuration->reports as $report ) {
				$report = ( object ) $report;
				$page_path = sprintf ( '%1$s/%2$s/%3$s/', $plugin_rootpage, 'reports', $report->permalink );
				
				$page_to_delete = get_page_by_path ( $page_path );
				$pages_to_delete [] = $page_to_delete->ID;
			}
		}
		
		$page_to_delete = get_page_by_path ( sprintf ( '%1$s/%2$s', $plugin_rootpage, 'widgets' ) );
		$pages_to_delete [] = $page_to_delete->ID;
		$page_to_delete = get_page_by_path ( sprintf ( '%1$s/%2$s', $plugin_rootpage, 'reports' ) );
		$pages_to_delete [] = $page_to_delete->ID;
		$page_to_delete = get_page_by_path ( sprintf ( '%1$s', $plugin_rootpage ) );
		$pages_to_delete [] = $page_to_delete->ID;
		
		// Delete The Plugin From The Settings
		$this->delete_pages ( $pages_to_delete );
		$registerdPlugins->delete ();
	}
	
	// Plugin Activation Functions
	public function plugin_activation_hook($plugin, $network_activation) {
		if ($this->plugin_configuration->plugin_info->plugin_path == $plugin) {
			$this->init ();
		}
	}
	public function plugin_deactivation_hook($plugin, $network_activation) {
		if ($this->plugin_configuration->plugin_info->plugin_path == $plugin) {
			$this->uninstall ();
		}
	}
	
	// Admin Notifications
	public function show_admin_notices() {
		$notices = '';
		
		foreach ( $this->plugin_admin_notices as $admin_notice ) {
			$notices .= sprintf ( '<div class="error"> <p>%1$s</p></div>', $admin_notice );
		}
		
		echo $notices;
	}
}
?>