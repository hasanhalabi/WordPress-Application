<?php
add_action ( 'init', 'block_wp_admin_init', 0 );
add_action ( 'init', 'theme_pages_install', 0 );

add_action ( 'wp_enqueue_scripts', 'theme_name_scripts' );
add_action ( 'after_setup_theme', 'jomiz_sys_theme_setup' );
add_action ( 'wp_login_failed', 'my_front_end_login_fail' ); // hook failed login
function jomiz_sys_theme_setup() {
	load_theme_textdomain ( 'jomizsystem', get_template_directory () . '/languages' );
}
function theme_name_scripts() {
	
	// Styles
	wp_enqueue_style ( 'style', get_template_directory_uri () . "/style.css", array (), "2017.07.08" );
	
	//if (is_rtl ()) {
	if (get_user_locale(get_current_user_id()) == "ar") {
		wp_enqueue_style ( 'bootstrap-rtl', get_template_directory_uri () . "/styles/css/plugins/bootstrap-rtl/bootstrap-rtl.min.css", array (
				'style' 
		), "3.3.1" );
		wp_enqueue_style ( 'style-rtl', get_template_directory_uri () . "/styles/css/style-rtl.css", array (
				'style' 
		), "1.0.0" );
	}
	
	theme_dependencies::themecore();
	theme_dependencies::angularjs ();
	theme_dependencies::selectize ();
	theme_dependencies::filesaver();
	theme_dependencies::ionRangeSlider();
	theme_dependencies::jasny();
	// Forms Plugin
	// chosen
	wp_enqueue_script ( 'chosen', get_template_directory_uri () . '/styles/js/plugins/chosen/chosen.jquery.js', array (
			"inspinia" 
	), '1.0.0', true );
	wp_enqueue_style ( 'chosen', get_template_directory_uri () . "/styles/css/plugins/chosen/chosen.css", array (
			'style' 
	), "1.0.0" );
	
	// toastr
	// wp_enqueue_script('toastr', get_template_directory_uri() . '/styles/js/plugins/toastr/toastr.min.js', array("inspinia"), '1.0.0', true);
	wp_enqueue_style ( 'toastr', get_template_directory_uri () . "/styles/css/plugins/toastr/toastr.min.css", array (
			'style' 
	), "1.0.0" );
	
	// ui-select
	wp_enqueue_script ( 'jomiz.angular.ui-select', get_template_directory_uri () . '/biz-js/select.min.js', array (
			'angular' 
	), '0.13.2', true );
	wp_enqueue_style ( 'jomiz.angular.ui-select', get_template_directory_uri () . "/styles/css/plugins/ui-select/select.min.css", array (
			'style' 
	), "0.13.2" );
	wp_enqueue_style ( 'jomiz.angular.ui-select2', get_template_directory_uri () . "/styles/css/plugins/ui-select/select2.css", array (
			'style' 
	), "0.13.2" );
	wp_enqueue_style ( 'jomiz.angular.ui-selectize', get_template_directory_uri () . "/styles/css/plugins/ui-select/selectize.default.css", array (
			'style' 
	), "0.13.2" );
	
	// iCheck
	wp_enqueue_script ( 'iCheck', get_template_directory_uri () . '/styles/js/plugins/iCheck/icheck.min.js', array (
			"inspinia" 
	), '1.0.0', true );
	wp_enqueue_style ( 'iCheck', get_template_directory_uri () . "/styles/css/plugins/iCheck/custom.css", array (
			'style' 
	), "1.0.0" );
	wp_enqueue_style ( 'iCheck-bootstrap', get_template_directory_uri () . "/styles/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css", array (
			'iCheck' 
	), "1.0.0" );
	
	// Date Picker
	wp_enqueue_script ( 'moment', get_template_directory_uri () . '/styles/js/plugins/fullcalendar/moment.min.js', array (
			"inspinia" 
	), '1.0.0', true );
	wp_enqueue_script ( 'bootstrap-datepicker', get_template_directory_uri () . '/styles/js/plugins/datapicker/bootstrap-datepicker.js', array (
			"moment" 
	), '1.0.0', true );
	// wp_enqueue_script('ui-datepicker', get_template_directory_uri() .
	// '/styles/js/plugins/ui-datepicker.js', array("ui-datepicker"), '1.0.0', true);
	wp_enqueue_script ( 'daterangepicker', get_template_directory_uri () . '/styles/js/plugins/daterangepicker/daterangepicker.js', array (
			"bootstrap-datepicker" 
	), '1.0.0', true );
	wp_enqueue_style ( 'datapicker', get_template_directory_uri () . "/styles/css/plugins/datapicker/datepicker3.css", array (
			'style' 
	), "1.0.0" );
	wp_enqueue_style ( 'daterangepicker', get_template_directory_uri () . "/styles/css/plugins/daterangepicker/daterangepicker-bs3.css", array (
			'datapicker' 
	), "1.0.0" );
	
	theme_dependencies::dropzone();
	
	// ScrollTo
	wp_enqueue_script ( 'scrollto', get_template_directory_uri () . '/styles/js/plugins/scrollto/jquery-scrollto.js', array (
			"inspinia" 
	), '1.0.0', true );
	wp_enqueue_style ( 'scrollto', get_template_directory_uri () . "/styles/css/plugins/scrollto/jquery-scrollto.css", array (
			'style' 
	), "1.0.0" );
	
	// sweetalert
	wp_enqueue_script ( 'sweetalert', get_template_directory_uri () . '/styles/js/plugins/sweetalert/sweetalert.min.js', array (
			"angular" 
	), '1.0.0', true );
	wp_enqueue_script ( 'ng.sweetalert', get_template_directory_uri () . '/styles/js/plugins/sweetalert/ng.sweetalert.min.js', array (
			"sweetalert" 
	), '1.0.0', true );
	wp_enqueue_style ( 'sweetalert', get_template_directory_uri () . "/styles/css/plugins/sweetalert/sweetalert.css", array (
			'style' 
	), "1.0.0" );
	
	// morris
	wp_enqueue_script ( 'morris', get_template_directory_uri () . '/styles/js/plugins/morris/morris.js', array (
			"inspinia" 
	), '1.0.0', true );
	wp_enqueue_script ( 'morris-raphael', get_template_directory_uri () . '/styles/js/plugins/morris/raphael-2.1.0.min.js', array (
			"morris" 
	), '1.0.0', true );
	wp_enqueue_style ( 'morris', get_template_directory_uri () . "/styles/css/plugins/morris/morris-0.4.3.min.css", array (
			'style' 
	), "0.4.3" );
	
	// flot
	wp_enqueue_script ( 'flot', get_template_directory_uri () . '/styles/js/plugins/flot/jquery.flot.js', array (
			"inspinia" 
	), '0.8.3', true );
	wp_enqueue_script ( 'flot.tooltip', get_template_directory_uri () . '/styles/js/plugins/flot/jquery.flot.tooltip.min.js', array (
			"flot" 
	), '0.8.3', true );
	wp_enqueue_script ( 'flot.spline', get_template_directory_uri () . '/styles/js/plugins/flot/jquery.flot.spline.js', array (
			"flot" 
	), '0.8.3', true );
	wp_enqueue_script ( 'flot.resize', get_template_directory_uri () . '/styles/js/plugins/flot/jquery.flot.resize.js', array (
			"flot" 
	), '0.8.3', true );
	wp_enqueue_script ( 'flot.pie', get_template_directory_uri () . '/styles/js/plugins/flot/jquery.flot.pie.js', array (
			"flot" 
	), '0.8.3', true );
	wp_enqueue_script ( 'flot.symbol', get_template_directory_uri () . '/styles/js/plugins/flot/jquery.flot.symbol.js', array (
			"flot" 
	), '0.8.3', true );
	wp_enqueue_script ( 'flot.curvedLines', get_template_directory_uri () . '/styles/js/plugins/flot/curvedLines.js', array (
			"flot" 
	), '0.8.3', true );
	
	// Peity
	wp_enqueue_script ( 'peity', get_template_directory_uri () . '/styles/js/plugins/peity/jquery.peity.min.js', array (
			"inspinia" 
	), '2.0.3', true );
	
	// Jvectormap
	wp_enqueue_script ( 'jvectormap', get_template_directory_uri () . '/styles/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js', array (
			"inspinia" 
	), '2.0.2', true );
	wp_enqueue_script ( 'jvectormap-world', get_template_directory_uri () . '/styles/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js', array (
			"jvectormap" 
	), '2.0.3', true );
	
	// Sparkline
	wp_enqueue_script ( 'sparkline', get_template_directory_uri () . '/styles/js/plugins/sparkline/jquery.sparkline.min.js', array (
			"inspinia" 
	), '2.1.2', true );
	
	// ChartJS
	wp_enqueue_script ( 'chartJs', get_template_directory_uri () . '/styles/js/plugins/chartJs/Chart.min.js', array (
			"inspinia" 
	), '2.1.2', true );
}
function block_wp_admin_init() {
	show_admin_bar ( false );
}
function my_front_end_login_fail($username) {
	$referrer = $_SERVER ['HTTP_REFERER']; // where did the post submission come from?
	                                       // if there's a valid referrer, and it's not the default log-in screen
	if (! empty ( $referrer ) && ! strstr ( $referrer, 'wp-login' ) && ! strstr ( $referrer, 'wp-admin' )) {
		wp_redirect ( login_url ( true ) ); // let's append some information (login=failed) to the URL for the theme to use
		exit ();
	}
}
class theme_dependencies {
	public static function themecore() {
		// Scripts
		wp_enqueue_script ( 'jquery213', get_template_directory_uri () . '/styles/js/jquery-2.1.3.min.js', array (), '2.1.3', FALSE );
		wp_enqueue_script ( 'angular-filter', get_template_directory_uri () . '/biz-js/angular-filter.min.js', array (
				'angular'
		), '0.5.7', FALSE );
		wp_enqueue_script ( 'bootstrap', get_template_directory_uri () . '/styles/js/bootstrap.min.js', array (
				"jquery213" 
		), '3.3.5', true );
		wp_enqueue_script ( 'viewport', get_template_directory_uri () . '/styles/js/jquery.viewport.js', array (
				"jquery213" 
		), '1.0.0', true );
		
		wp_enqueue_script ( 'metisMenu', get_template_directory_uri () . '/styles/js/plugins/metisMenu/jquery.metisMenu.js', array (
				"jquery213" 
		), '2.0.2', true );
		wp_enqueue_script ( 'slimscroll', get_template_directory_uri () . '/styles/js/plugins/slimscroll/jquery.slimscroll.min.js', array (
				"jquery213" 
		), '1.3.6', true );
		wp_enqueue_script ( 'inspinia', get_template_directory_uri () . '/styles/js/inspinia.js', array (
				"bootstrap" ,
				"angular"
		), '2.2', true );
		wp_enqueue_script ( 'pace', get_template_directory_uri () . '/styles/js/plugins/pace/pace.min.js', array (
				"inspinia" 
		), '1.0.0', true );
		wp_enqueue_script ( 'jomiz-sys-effects', get_template_directory_uri () . '/styles/js/jomiz-sys-effects.js', array (
				"pace" 
		), '1.0.0', true );
	}
	public static function angularjs() {
		// Angular Scripts
		wp_enqueue_script ( 'angular', get_template_directory_uri () . '/biz-js/angular.min.js', array (), '1.4.7', FALSE );
		wp_enqueue_script ( 'jomiz.angular.sanitize', get_template_directory_uri () . '/biz-js/angular-sanitize.min.js', array (
				'angular' 
		), '1.4.7', FALSE );
		wp_enqueue_script ( 'jomiz.angular.animate', get_template_directory_uri () . '/biz-js/angular-animate.min.js', array (
				'angular' 
		), '1.4.7', FALSE );
		wp_enqueue_script ( 'ng.toastr', get_template_directory_uri () . '/biz-js/ng.toastr.min.js', array (
				"jomiz.angular.animate" 
		), '1.0.0', true );
		wp_enqueue_script ( 'jomiz.angular.ui', get_template_directory_uri () . '/biz-js/ui-bootstrap-tpls-1.1.0.min.js', array (
				'angular' 
		), '1.1.0', FALSE );
		wp_enqueue_script ( 'ng.translate', get_template_directory_uri () . '/biz-js/ng.translate.min.js', array (
				'angular' 
		), '1.0.0', FALSE );
		wp_enqueue_script ( 'ngChartJs', get_template_directory_uri () . '/styles/js/plugins/chartJs/angles.js', array (
				"angular" 
		), '2.1.2', true );
		wp_enqueue_script ( 'jomiz.angular.app', get_template_directory_uri () . '/biz-js/ng.jomiz.app.js', array (
				'jomiz.angular.sanitize',
				'ng.toastr',
				'ng.sweetalert',
				'ng.translate',
				'ngChartJs',
				'selectize-ng' ,
				'filesaver',
				'angular-filter'
		), '1.0.0', FALSE );
		wp_enqueue_script ( 'ng.dictionary', get_template_directory_uri () . '/biz-js/ng.dictionary.js', array (
				'jomiz.angular.app' 
		), '1.0.0', FALSE );
		wp_enqueue_script ( 'jomiz.page.controller', get_template_directory_uri () . '/biz-js/ng.jomiz.page.controller.js', array (
				'jomiz.angular.app'
		), '1.0.0', FALSE );
		
		wp_enqueue_script ( 'inspinia.directives', get_template_directory_uri () . '/biz-js/ng.inspinia.directives.js', array (
				'jomiz.angular.app' 
		), '1.0.0', FALSE );
		wp_enqueue_script ( 'jomiz.app.directives', get_template_directory_uri () . '/biz-js/ng.jomiz.app.directives.js', array (
				'inspinia.directives' 
		), '1.0.0', FALSE );
		
		// Reporting ng
		
		wp_enqueue_script ( 'jomiz.rpt.controller', get_template_directory_uri () . '/biz-js/ng.jomiz.rpt.controller.js', array (
				'jomiz.angular.app' 
		), '1.0.0', FALSE );
	}
	public static function selectize() {
		wp_enqueue_style ( 'selectize', get_template_directory_uri () . "/styles/css/plugins/selectize/selectize.css", array (
				'style' 
		), "0.12.1" );
		wp_enqueue_style ( 'selectize.default', get_template_directory_uri () . "/styles/css/plugins/selectize/selectize.default.css", array (
				'selectize' 
		), "0.12.1" );
		wp_enqueue_script ( 'selectize', get_template_directory_uri () . '/styles/js/plugins/selectize/selectize.min.js', array (
				"angular" 
		), '0.12.1', true );
		wp_enqueue_script ( 'selectize-ng', get_template_directory_uri () . '/styles/js/plugins/selectize/angular-selectize.js', array (
				"selectize" 
		), '3.0.0', true );
	}
	
	public static function dropzone(){
		// DropZone
		wp_enqueue_script ( 'dropzone', get_template_directory_uri () . '/styles/js/plugins/dropzone/dropzone.js', array (
				"inspinia"
		), '1.0.0', true );
		wp_enqueue_style ( 'dropzoneBasic', get_template_directory_uri () . "/styles/css/plugins/dropzone/basic.css", array (
				'style'
		), "1.0.0" );
		wp_enqueue_style ( 'dropzoneCSS', get_template_directory_uri () . "/styles/css/plugins/dropzone/dropzone.css", array (
				'dropzoneBasic'
		), "1.0.0" );
	}
	
	public static function filesaver() {
		
		wp_enqueue_script ( 'blob', get_template_directory_uri () . '/styles/js/plugins/filesaver/Blob.js', array (
				"angular"
		), '0.0.1', true );
		wp_enqueue_script ( 'filesaver', get_template_directory_uri () . '/styles/js/plugins/filesaver/FileSaver.min.js', array (
				"blob"
		), '1.1.20160328', true );
	}
	public static function ionRangeSlider()
	{
		wp_enqueue_style ( 'ionRangeSlider', get_template_directory_uri () . "/styles/css/plugins/ionRangeSlider/ion.rangeSlider.css", array (
				'style'
		), "1.8.5" );
		wp_enqueue_style ( 'ionRangeSlider-skin', get_template_directory_uri () . "/styles/css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css", array (
				'ionRangeSlider'
		), "1.8.5" );
		wp_enqueue_script ( 'ionRangeSlider', get_template_directory_uri () . '/styles/js/plugins/ionRangeSlider/ion.rangeSlider.min.js', array (
				"inspinia"
		), '1.8.5', true );
	}
	
	public static function jasny()
	{
		wp_enqueue_style ( 'jasny', get_template_directory_uri () . "/styles/css/plugins/jasny/jasny-bootstrap.min.css", array (
				'style'
		), "3.1.2" );
		wp_enqueue_script ( 'jasny', get_template_directory_uri () . '/styles/js/plugins/jasny/jasny-bootstrap.min.js', array (
				"inspinia"
		), '3.1.2', true );
	}
}
