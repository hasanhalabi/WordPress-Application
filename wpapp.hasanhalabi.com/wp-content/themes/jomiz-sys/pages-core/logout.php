<?php
	/*
	 * Template Name: Logout page
	 */
	wp_logout();
	wp_redirect(login_url());
?>