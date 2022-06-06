<?php
	add_theme_support('menus');

	add_filter('nav_menu_css_class', 'special_nav_class', 10, 2);
	add_filter('nav_menu_link_attributes', 'filter_function_name', 10, 3);

	function special_nav_class($classes, $item) {
		if (in_array('current-menu-item', $classes)) {
			$classes[] = 'active jomiz-active-menu-item ';
		}
		return $classes;
	}

	function filter_function_name($atts, $item, $args) {
		if (empty($atts['title']) && empty($item->attr_title )) {
			$atts['title'] = $item->title;
		}

		return $atts;
	}

	class jomizSysMenus____ {
		public static function get_simple_links_menu($menu, $container_class) {
			$defaults = array(
				'theme_location' => '',
				'menu' => $menu,
				'container' => 'div',
				'container_class' => $container_class,
				'container_id' => '',
				'menu_class' => 'menu',
				'menu_id' => '',
				'echo' => true,
				'fallback_cb' => 'wp_page_menu',
				'before' => '',
				'after' => '',
				'link_before' => '',
				'link_after' => '',
				'items_wrap' => '<ul id="%1$s" class="simple-links-menu %2$s">%3$s</ul>',
				'depth' => 0,
				'walker' => ''
			);

			wp_nav_menu($defaults);
		}

		/*
		 * No Items wrapper
		 */
		public static function get_menu_links($menu) {
			$defaults = array(
				'theme_location' => '',
				'menu' => $menu,
				'container' => '',
				'container_class' => '',
				'container_id' => '',
				'menu_class' => '',
				'menu_id' => '',
				'echo' => true,
				'fallback_cb' => 'wp_page_menu',
				'before' => '',
				'after' => '',
				'link_before' => '<i class="fa fa-cubes"></i> <span class="nav-label">',
				'link_after' => '</span>',
				'items_wrap' => '<li class="disabled"><a title="Current Fiscal Year"><i class="fa fa-calendar-o"></i> <span class="nav-label">السنة المالية 2015</span></a></li>%3$s',
				'depth' => 0,
				'walker' => ''
			);

			wp_nav_menu($defaults);
		}


		public static function get_accounting_menu($menu)
		{
			$defaults = array(
				'theme_location' => '',
				'menu' => $menu,
				'container' => '',
				'container_class' => '',
				'container_id' => '',
				'menu_class' => '',
				'menu_id' => '',
				'echo' => 0,
				'fallback_cb' => 'wp_page_menu',
				'before' => '',
				'after' => '',
				'link_before' => '',
				'link_after' => '',
				'items_wrap' => '%3$s',
				'depth' => 0,
				'walker' => ''
			);

			return wp_nav_menu($defaults);
		}
	}
?>