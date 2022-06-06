<?php
function login_url($error = false) {
	$error_qs = '';
	
	if ($error)
	{
		$error_qs = '?failed';
	}
	return home_url ( '/login'.$error_qs );
}
function logout_url() {
	return home_url ( '/logout' );
}
function theme_pages_install() {
	$theme_pages = array ();
	
	$theme_pages [] = array (
			'title' => 'Login',
			'name' => 'login',
			'template' => 'pages-core/login.php' 
	);
	
	$theme_pages [] = array (
			'title' => 'Logout',
			'name' => 'logout',
			'template' => 'pages-core/logout.php' 
	);
	
	$theme_pages [] = array (
			'title' => 'UsersGroups',
			'name' => 'usersgroups',
			'template' => 'pages-core/page-users-groups.php' 
	);
	
	$theme_pages [] = array (
			'title' => 'systemusers',
			'name' => 'systemusers',
			'template' => 'pages-core/page-users.php' 
	);
	
	$theme_pages [] = array (
			'title' => 'UsersGroupsEdit',
			'name' => 'usersgroupsedit',
			'template' => 'pages-core/page-users-groups-edit.php' 
	);
	
	$theme_pages [] = array (
			'title' => 'jomizapi',
			'name' => 'jomizapi',
			'template' => 'pages-core/page-api.php' 
	);
	
	$theme_pages [] = array (
			'title' => 'systemreports',
			'name' => 'systemreports',
			'template' => 'pages-core/page-system-reports.php' 
	);
	
	$theme_pages [] = array (
			'title' => 'upload',
			'name' => 'upload',
			'template' => 'pages-core/page-upload.php' 
	);
	
	$theme_pages [] = array (
			'title' => 'download',
			'name' => 'download',
			'template' => 'pages-core/page-download.php' 
	);
	
	$theme_pages [] = array (
			'title' => 'dashboard',
			'name' => 'dashboard',
			'template' => '' 
	);
	
	$theme_pages [] = array (
			'title' => 'help',
			'name' => 'help',
			'template' => '' 
	);
	
	global $wpdb;
	
	foreach ( $theme_pages as $theme_page ) {
		
		$the_page = get_page_by_path ( $theme_page ['name'] );
		$the_page_id = - 1;
		
		if (! $the_page) {
			
			// Create post object
			$_p = array ();
			$_p ['post_title'] = $theme_page ['title'];
			$_p ['post_content'] = '';
			$_p ['post_status'] = 'publish';
			$_p ['post_type'] = 'page';
			$_p ['comment_status'] = 'closed';
			$_p ['ping_status'] = 'closed';
			$_p ['post_category'] = array (
					1 
			);
			// the default 'Uncatrgorised'
			
			// Insert the post into the database
			$the_page_id = wp_insert_post ( $_p );
		} else {
			// the plugin may have been previously active and the page may just be trashed...
			
			$the_page_id = $the_page->ID;
			
			// make sure the page is not trashed...
			$the_page->post_status = 'publish';
			$the_page_id = wp_update_post ( $the_page );
		}
		
		if ($the_page_id > 0 && $theme_page ['template'] != "") {
			add_post_meta ( $the_page_id, '_wp_page_template', $theme_page ['template'], true );
		}
	}
}
