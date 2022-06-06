<?php
/*
 * Load Templates From Plugin
 * Original Plugin Info
 * Name: Page Template Plugin : 'Good To Be Bad'
 * URI: http://hbt.io/
 * Author: Harri Bell-Thomas
 * Author URI: http://hbt.io/
 */
class PageTemplater {
	
	/**
	 * The array of templates that this plugin tracks.
	 */
	private $templates;
	private static $instance;
	public static function getInstance() {
		if (! isset ( self::$instance )) {
			self::$instance = new PageTemplater ();
		}
		
		return self::$instance;
	}
	
	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {
		$this->templates = array ();
		$this->init ();
	}
	private function init() {
		// Get Templates From Settings Pods
		$registeredplugin = pods ( 'jomiz_registeredplugin', array (
				'limit' => - 1 
		) );
		$this->templates = array ();
		
		while ( $registeredplugin->fetch () ) {
			$plugin_configuration = ( object ) json_decode ( $registeredplugin->field ( 'configuration' ), true );
			
			foreach ( $plugin_configuration->pages as $page ) {
				$template_path = sprintf ( '%1$s/pages/%2$s', $plugin_configuration->plugin_pysical_path, $page ['template'] );
				$this->templates [$template_path] = sprintf ( '%1$s [%2$s])', $page ['title'], $plugin_configuration->plugin_info ['title'] );
			}
			
			if (isset ( $plugin_configuration->widgets )) {
				foreach ( $plugin_configuration->widgets as $widget ) {
					$template_path = sprintf ( '%1$s/widgets/%2$s', $plugin_configuration->plugin_pysical_path, $widget ['template'] );
					$this->templates [$template_path] = sprintf ( '%1$s [%2$s](Widget))', $widget ['title'], $plugin_configuration->plugin_info ['title'] );
				}
			}
			
			if (isset ( $plugin_configuration->reports )) {
				foreach ( $plugin_configuration->reports as $report ) {
					$template_path = sprintf ( '%1$s/reports/%2$s', $plugin_configuration->plugin_pysical_path, $report ['template'] );
					$this->templates [$template_path] = sprintf ( '%1$s [%2$s])', $report ['title'], $plugin_configuration->plugin_info ['title'] );
				}
			}
		}
		
		// Add a filter to the attributes metabox to inject template into the cache.
		add_filter ( 'page_attributes_dropdown_pages_args', array (
				$this,
				'register_project_templates' 
		) );
		
		// Add a filter to the save post to inject out template into the page cache
		add_filter ( 'wp_insert_post_data', array (
				$this,
				'register_project_templates' 
		) );
		
		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path
		add_filter ( 'template_include', array (
				$this,
				'view_project_template' 
		) );
	}
	
	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public function register_project_templates($atts) {
		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5 ( get_theme_root () . '/' . get_stylesheet () );
		
		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme ()->get_page_templates ();
		if (empty ( $templates )) {
			$templates = array ();
		}
		
		// New cache, therefore remove the old one
		wp_cache_delete ( $cache_key, 'themes' );
		
		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge ( $templates, $this->templates );
		
		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add ( $cache_key, $templates, 'themes', 1800 );
		
		return $atts;
	}
	
	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template($template) {
		global $post;
		
		if (! isset ( $this->templates [get_post_meta ( $post->ID, '_wp_page_template', true )] )) {
			return $template;
		}
		
		// $file = plugin_dir_path(__FILE__) . get_post_meta($post -> ID,
		// '_wp_page_template', true);
		$file = get_post_meta ( $post->ID, '_wp_page_template', true );
		
		// Just to be safe, we check if the file exist first
		if (file_exists ( $file )) {
			return $file;
		} else {
			echo $file;
		}
		
		return $template;
	}
	private function log_to_file($content) {
		$content = var_export ( $content, TRUE );
		
		//file_put_contents ( 'e:\\temps\\mifm-log.txt', "\n--------------\n$content", FILE_APPEND );
	}
}