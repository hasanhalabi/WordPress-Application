<?php
/**
 * JoMiz User Info
 */
class jomiz_user_info {
	private static $instance;
	private $_isAdmin;
	private $_capabilities;
	public $userInfo;
	private function __construct($arguments) {
		$this->_isAdmin = FALSE;
		$this->_capabilities = array ();
		
		$this->userInfo = $arguments ['current_user'];
		
		foreach ( $this->userInfo->roles as $theRole ) {
			if ($theRole == "administrator") {
				$this->_isAdmin = true;
			}
		}
		
		$jomiz_usergroup_params = array ();
		$jomiz_usergroup_params ['where'] = sprintf ( 'members.ID = %1$s', get_current_user_id () );
		
		$jomiz_usergroup = pods ( 'jomiz_usergroup', $jomiz_usergroup_params );
		
		while ( $jomiz_usergroup->fetch () ) {
			$privileges = json_decode ( $jomiz_usergroup->field ( 'privileges' ) );
			
			foreach ( $privileges->capabilities as $capability ) {
				$this->_capabilities [] = $capability;
			}
			foreach ( $privileges->reports as $report ) {
				$this->_capabilities [] = $report;
			}
			foreach ( $privileges->widgets as $widget ) {
				$this->_capabilities [] = $widget;
			}
		}
	}
	public function isAdmin() {
		return $this->_isAdmin;
	}
	public function isCapable($plugin_code, $object_code, $capability) {
		$capability_to_find = sprintf ( '%1$s-%2$s-%3$s', $plugin_code, $object_code, $capability );
		
		return in_array ( $capability_to_find, $this->_capabilities );
	}
	public function hasViewCapability($plugin_code, $object_code) {
		return ($this->isCapable ( $plugin_code, $object_code, 'view-all-data' ) || $this->isCapable ( $plugin_code, $object_code, 'view-only-my-data' ) || $this->isCapable ( $plugin_code, $object_code, 'view-data-of-team' ));
	}
	public static function register_global_object() {
		global $current_user_info;
		
		if (! isset ( self::$instance )) {
			if (class_exists ( 'jomiz_user_info' )) {
				$args = array (
						'current_user' => wp_get_current_user () 
				);
				self::$instance = new jomiz_user_info ( $args );
			}
		}
		
		$current_user_info = self::$instance;
	}
}
?>