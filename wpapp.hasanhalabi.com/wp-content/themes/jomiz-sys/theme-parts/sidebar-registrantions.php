<?php
register_sidebar(array('name' => 'Primary Widget Area', 'id' => 'primary_widget_area', 'before_widget' => '<li id="%1$s" class="widget-container %2$s">', 'after_widget' => "</li>", 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>', ));

register_sidebar(array('name' => 'Secondary Widget Area', 'id' => 'secondary_widget_area', 'before_widget' => '<li id="%1$s" class="widget-container %2$s">', 'after_widget' => "</li>", 'before_title' => '<h3 class="widget-title">', 'after_title' => '</h3>', ));

$preset_widgets = array('primary_widget_area' => array('search', 'pages', 'categories', 'archives'), 'secondary_widget_area' => array('links', 'meta'));
if (isset($_GET['activated'])) {
	update_option('sidebars_widgets', $preset_widgets);
}
// update_option( 'sidebars_widgets', NULL );

// Check for static widgets in widget-ready areas
function is_sidebar_active($index) {
	global $wp_registered_sidebars;

	$widgetcolums = wp_get_sidebars_widgets();

	if ($widgetcolums[$index])
		return true;

	return false;
} // end is_sidebar_active
?>