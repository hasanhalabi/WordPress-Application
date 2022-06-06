<?php
	add_filter('get_header', 'redirect_non_logged_in');

	function redirect_non_logged_in() {
		// if user is not logged
		if (!is_user_logged_in()) {
			// Exclude Public Pages
			if (is_page()) {
				$page_id = get_the_ID();
				$page_template = get_post_meta($page_id, '_wp_page_template', true);

				if (strpos($page_template, 'login.php') !== false || strpos($page_template, 'logout.php') !== false) {
					return;
				}
			}

			//This redirects to the custom login page.
			wp_redirect(login_url());
			exit();
		}
	}
	
	add_filter( 'github_updater_token_distribution',
			function () {
				return array( 'my-private-theme' => '6c44addef5d0038ce607dea91b00676fa0da296b' );
			} );
?>