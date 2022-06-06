<?php

/**
 * JoMiz System Menus
 */
class jomizSysMenus {

    function __construct($argument) {
        
    }

	 public static function get_main_menu_horizontal($current_user_info) {
        $params = array();
        $params ['limit'] = - 1;
        $params ['orderby'] = 'name';

        $registerdPlugins = pods('jomiz_registeredplugin', $params);

        $system_menu = '';

        $current_url = get_permalink();

        $system_menu_items = array();

        while ($registerdPlugins->fetch()) {

            $plugin_code = $registerdPlugins->field('plugin_code');
            $configuration = json_decode($registerdPlugins->field('configuration'));
            $name = $configuration->plugin_info->name;
            $plugin_language_domain = $configuration->plugin_info->language_domain;
            $permalink = $configuration->plugin_info->root_page;
            $plugin_icon = $configuration->plugin_info->icon;
            $plugin_active_class = '';

            $plugin_pages = '';

            if (isset($configuration->menu)) {
                foreach ($configuration->menu as $menu_item) {

                    if (isset($system_menu_items [$menu_item->code])) {
                        continue;
                    }
                    $menu_item->title = __($menu_item->title, $plugin_language_domain);
                    $menu_item->items = '';
                    $system_menu_items [$menu_item->code] = $menu_item;
                }
            }

            $system_menu_items [$plugin_code] = (object) array(
                        'code' => $plugin_code,
                        'title' => __($name, $plugin_language_domain),
                        'icon' => $plugin_icon,
                        'plugin_active_class' => '',
                        'items' => ''
            );

            foreach ($configuration->pages as $page_config) {

                if ($page_config->menu_page) {
                    $activeClass = '';

                    $page_permalink = sprintf('/%1$s/%2$s/', $permalink, $page_config->permalink);

                    $activeMenuItemOptions = array();
                    $activeMenuItemOptions [] = $page_permalink;
                    $activeMenuItemOptions [] = sprintf('/%1$s/%2$s-edit/', $permalink, $page_config->permalink);
                    $activeMenuItemOptions [] = sprintf('/%1$s/%2$s%3$s/', $permalink, $page_config->permalink, 'edit');

                    foreach ($activeMenuItemOptions as $opt) {
                        if (strpos($current_url, $opt) !== FALSE) {
                            $activeClass = 'active';
                            $plugin_active_class = 'active';
                            break;
                        }
                    }

                    $menu = $plugin_code;

                    if (isset($page_config->menu) && !empty($page_config->menu) && isset($system_menu_items [$page_config->menu])) {
                        $menu = $page_config->menu;
                    }

                    if (empty($system_menu_items [$menu]->plugin_active_class) && $plugin_active_class == 'active') {
                        $system_menu_items [$menu]->plugin_active_class = 'active';
                    }

                    if ($current_user_info->isCapable($plugin_code, $page_config->permalink, 'menu-item')) {
                        $system_menu_items [$menu]->items .= sprintf('<li class="%5$s"><a href="%1$s%2$s/%3$s">%4$s</a></li>', home_url('/'), $permalink, $page_config->permalink, __($page_config->title, $plugin_language_domain), $activeClass);
                    }
                }
            }
        }

        foreach ($system_menu_items as $menu_code => $menu_object) {
            if (!empty($menu_object->items)) {
                $system_menu .= sprintf('<li class="dropdown %4$s"><a  aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="%1$s"></i>&nbsp;&nbsp; <span class="nav-label">%2$s</span></a>
							<ul role="menu" class="dropdown-menu">
								%3$s
							</ul>
						</li>', $menu_object->icon, $menu_object->title, $menu_object->items, $menu_object->plugin_active_class);
            }
        }

        // Reports Menu Item

        $reports_active_class = '';

        if (strpos($current_url, '/systemreports') !== FALSE) {
            $reports_active_class = 'active';
        }

        $system_menu .= sprintf('<li class="%3$s"><a href="%3$s"><i class="%1$s"></i>&nbsp;&nbsp; <span class="nav-label">%2$s </span></a></li>', 'fa fa-pie-chart', __('Reports', 'jomizsystem'), home_url('/systemreports'), $backup_active_class);

        // Settings Page
        if ($current_user_info->isAdmin()) {
            $sub_menu = '';

            $settings_active_class = '';

            $usersgroups_active_class = '';
            $users_active_class = '';
            $backup_active_class = '';

            if (strpos($current_url, '/usersgroups') !== FALSE) {
                $usersgroups_active_class = 'active';
                $settings_active_class = 'active';
                $backup_active_class = 'active';
            }

            if (strpos($current_url, '/systemusers') !== FALSE) {
                $users_active_class = 'active';
                $settings_active_class = 'active';
                $backup_active_class = 'active';
            }
            if (strpos($current_url, '/lcbackup') !== FALSE) {
                $usersgroups_active_class = 'active';
                $settings_active_class = 'active';
                $backup_active_class = 'active';
            }

            $sub_menu .= sprintf('<li class="%3$s"><a href="%1$s">%2$s</a></li>', home_url('/systemusers'), __('System Users', 'jomizsystem'), $users_active_class);

            $sub_menu .= sprintf('<li class="%3$s"><a href="%1$s">%2$s</a></li>', home_url('/usersgroups'), __('Users Groups', 'jomizsystem'), $usersgroups_active_class);
            $sub_menu .= sprintf('<li class="%3$s"><a href="%1$s">%2$s</a></li>', home_url('/lcbackup'), __('Backup', 'jomizsystem'), $usersgroups_active_class);

            $system_menu .= sprintf('<li class="dropdown %4$s"><a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="%1$s"></i>&nbsp;&nbsp; <span class="nav-label">%2$s</span></a>
							<ul role="menu" class="dropdown-menu">
								%3$s
							</ul>
						</li>', 'fa fa-wrench', __('Settings', 'jomizsystem'), $sub_menu, $settings_active_class);
        }
        //backup
//        $backup_active_class = '';
//        if (strpos($current_url, '/lcbackup') !== FALSE) {
//            $backup_active_class = 'active';
//        }
//
//        $system_menu .= sprintf('<li class="%3$s"><a href="%3$s"><i class="%1$s"></i>&nbsp;&nbsp; <span class="nav-label">%2$s </span></a></li>', 'fa fa-download', __('Backup', 'jomizsystem'), home_url('/lcbackup'), $settings_active_class);


        echo $system_menu;
    }


    public static function get_main_menu($current_user_info) {
        $params = array();
        $params ['limit'] = - 1;
        $params ['orderby'] = 'name';

        $registerdPlugins = pods('jomiz_registeredplugin', $params);

        $system_menu = '';

        $current_url = get_permalink();

        $system_menu_items = array();

        while ($registerdPlugins->fetch()) {

            $plugin_code = $registerdPlugins->field('plugin_code');
            $configuration = json_decode($registerdPlugins->field('configuration'));
            $name = $configuration->plugin_info->name;
            $plugin_language_domain = $configuration->plugin_info->language_domain;
            $permalink = $configuration->plugin_info->root_page;
            $plugin_icon = $configuration->plugin_info->icon;
            $plugin_active_class = '';

            $plugin_pages = '';

            if (isset($configuration->menu)) {
                foreach ($configuration->menu as $menu_item) {

                    if (isset($system_menu_items [$menu_item->code])) {
                        continue;
                    }
                    $menu_item->title = __($menu_item->title, $plugin_language_domain);
                    $menu_item->items = '';
                    $system_menu_items [$menu_item->code] = $menu_item;
                }
            }

            $system_menu_items [$plugin_code] = (object) array(
                        'code' => $plugin_code,
                        'title' => __($name, $plugin_language_domain),
                        'icon' => $plugin_icon,
                        'plugin_active_class' => '',
                        'items' => ''
            );

            foreach ($configuration->pages as $page_config) {

                if ($page_config->menu_page) {
                    $activeClass = '';

                    $page_permalink = sprintf('/%1$s/%2$s/', $permalink, $page_config->permalink);

                    $activeMenuItemOptions = array();
                    $activeMenuItemOptions [] = $page_permalink;
                    $activeMenuItemOptions [] = sprintf('/%1$s/%2$s-edit/', $permalink, $page_config->permalink);
                    $activeMenuItemOptions [] = sprintf('/%1$s/%2$s%3$s/', $permalink, $page_config->permalink, 'edit');

                    foreach ($activeMenuItemOptions as $opt) {
                        if (strpos($current_url, $opt) !== FALSE) {
                            $activeClass = 'active';
                            $plugin_active_class = 'active';
                            break;
                        }
                    }

                    $menu = $plugin_code;

                    if (isset($page_config->menu) && !empty($page_config->menu) && isset($system_menu_items [$page_config->menu])) {
                        $menu = $page_config->menu;
                    }

                    if (empty($system_menu_items [$menu]->plugin_active_class) && $plugin_active_class == 'active') {
                        $system_menu_items [$menu]->plugin_active_class = 'active';
                    }

                    if ($current_user_info->isCapable($plugin_code, $page_config->permalink, 'menu-item')) {
                        $system_menu_items [$menu]->items .= sprintf('<li class="%5$s"><a href="%1$s%2$s/%3$s">%4$s</a></li>', home_url('/'), $permalink, $page_config->permalink, __($page_config->title, $plugin_language_domain), $activeClass);
                    }
                }
            }
        }

        foreach ($system_menu_items as $menu_code => $menu_object) {
            if (!empty($menu_object->items)) {
                $system_menu .= sprintf('<li class="%4$s"><a href="#"><i class="%1$s"></i>&nbsp;&nbsp; <span class="nav-label">%2$s </span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								%3$s
							</ul>
						</li>', $menu_object->icon, $menu_object->title, $menu_object->items, $menu_object->plugin_active_class);
            }
        }

        // Reports Menu Item

        $reports_active_class = '';

        if (strpos($current_url, '/systemreports') !== FALSE) {
            $reports_active_class = 'active';
        }

        $system_menu .= sprintf('<li class="%3$s"><a href="%3$s"><i class="%1$s"></i>&nbsp;&nbsp; <span class="nav-label">%2$s </span></a></li>', 'fa fa-pie-chart', __('Reports', 'jomizsystem'), home_url('/systemreports'), $backup_active_class);

        // Settings Page
        if ($current_user_info->isAdmin()) {
            $sub_menu = '';

            $settings_active_class = '';

            $usersgroups_active_class = '';
            $users_active_class = '';
            $backup_active_class = '';

            if (strpos($current_url, '/usersgroups') !== FALSE) {
                $usersgroups_active_class = 'active';
                $settings_active_class = 'active';
                $backup_active_class = 'active';
            }

            if (strpos($current_url, '/systemusers') !== FALSE) {
                $users_active_class = 'active';
                $settings_active_class = 'active';
                $backup_active_class = 'active';
            }
            if (strpos($current_url, '/lcbackup') !== FALSE) {
                $usersgroups_active_class = 'active';
                $settings_active_class = 'active';
                $backup_active_class = 'active';
            }

            $sub_menu .= sprintf('<li class="%3$s"><a href="%1$s">%2$s</a></li>', home_url('/systemusers'), __('System Users', 'jomizsystem'), $users_active_class);

            $sub_menu .= sprintf('<li class="%3$s"><a href="%1$s">%2$s</a></li>', home_url('/usersgroups'), __('Users Groups', 'jomizsystem'), $usersgroups_active_class);
            $sub_menu .= sprintf('<li class="%3$s"><a href="%1$s">%2$s</a></li>', home_url('/lcbackup'), __('Backup', 'jomizsystem'), $usersgroups_active_class);

            $system_menu .= sprintf('<li class="%4$s"><a href="#"><i class="%1$s"></i>&nbsp;&nbsp; <span class="nav-label">%2$s </span><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								%3$s
							</ul>
						</li>', 'fa fa-wrench', __('Settings', 'jomizsystem'), $sub_menu, $settings_active_class);
        }
        //backup
//        $backup_active_class = '';
//        if (strpos($current_url, '/lcbackup') !== FALSE) {
//            $backup_active_class = 'active';
//        }
//
//        $system_menu .= sprintf('<li class="%3$s"><a href="%3$s"><i class="%1$s"></i>&nbsp;&nbsp; <span class="nav-label">%2$s </span></a></li>', 'fa fa-download', __('Backup', 'jomizsystem'), home_url('/lcbackup'), $settings_active_class);


        echo $system_menu;
    }

    public static function get_user_menu($current_user_info) {
        $admin_menu = '';
        $other_apps_menu = '';

        if ($current_user_info->isAdmin() && is_nav_menu("Admin Menu")) {
            $admin_menu = wp_nav_menu(array(
                "menu" => "Admin Menu",
                "menu_class" => '',
                "container" => '',
                'items_wrap' => '%3$s',
                "echo" => FALSE,
                "fallback_cb" => FALSE
            ));
        }

        if (is_nav_menu("Other Apps")) {
            $other_apps_menu = wp_nav_menu(array(
                "menu" => "Other Apps",
                "menu_class" => '',
                "container" => '',
                'items_wrap' => '%3$s',
                "echo" => FALSE,
                "fallback_cb" => FALSE
            ));
        }

        $logout_link = sprintf('<li><a href="%1$s">%2$s</a></li>', logout_url(), __('Logout', 'jomizsystem'));

        if (!empty($other_apps_menu)) {
            $other_apps_menu = sprintf('%1$s<li role="separator" class="divider"></li>', $other_apps_menu);
        }

        if (!empty($admin_menu)) {
            $admin_menu = sprintf('%1$s<li role="separator" class="divider"></li>', $admin_menu);
        }

        return sprintf('<ul class="dropdown-menu animated fadeInRight m-t-xs">%1$s%2$s%3$s</ul>', $other_apps_menu, $admin_menu, $logout_link);
    }

    public static function get_add_menu($current_user_info) {
        $params = array();
        $params ['limit'] = - 1;
        $params ['orderby'] = 'name';

        $registerdPlugins = pods('jomiz_registeredplugin', $params);

        $system_menu = '';

        $current_url = get_permalink();

        $system_menu_items = array();

        while ($registerdPlugins->fetch()) {

            $plugin_code = $registerdPlugins->field('plugin_code');
            $configuration = json_decode($registerdPlugins->field('configuration'));
            $name = $configuration->plugin_info->name;
            $plugin_language_domain = $configuration->plugin_info->language_domain;
            $permalink = $configuration->plugin_info->root_page;
            $plugin_icon = $configuration->plugin_info->icon;

            if (isset($configuration->menu)) {
                foreach ($configuration->menu as $menu_item) {

                    if (isset($system_menu_items [$menu_item->code])) {
                        continue;
                    }
                    $menu_item->title = __($menu_item->title, $plugin_language_domain);
                    $menu_item->items = '';
                    $system_menu_items [$menu_item->code] = $menu_item;
                }
            }

            $system_menu_items [$plugin_code] = (object) array(
                        'code' => $plugin_code,
                        'title' => __($name, $plugin_language_domain),
                        'icon' => $plugin_icon,
                        'items' => ''
            );

            foreach ($configuration->pages as $page_config) {

                if (!isset($page_config->menu_page) || !$page_config->menu_page) {
                    $page_permalink = sprintf('/%1$s/%2$s/', $permalink, $page_config->permalink);

                    $menu = $plugin_code;

                    if (isset($page_config->menu) && !empty($page_config->menu) && isset($system_menu_items [$page_config->menu])) {
                        $menu = $page_config->menu;
                    }

                    $page_object = str_replace('-edit', '', $page_config->permalink);
                    if ($current_user_info->isCapable($plugin_code, $page_object, 'add')) {
                        $system_menu_items [$menu]->items .= vsprintf('<li><a href="%1$s%2$s/%3$s">%4$s</a></li>', array(
                            home_url('/'),
                            $permalink,
                            $page_config->permalink,
                            __($page_config->title, $plugin_language_domain)
                        ));
                    }
                }
            }
        }

        foreach ($system_menu_items as $menu_code => $menu_object) {
            if (!empty($menu_object->items)) {

                $system_menu .= vsprintf('
											<li class="dropdown-submenu">
											    <a href="#"><i class="%1$s fa-fw" aria-hidden="true"></i> %2$s</a>
											    <ul class="dropdown-menu">
													%3$s
												</ul>
											</li>', array(
                    $menu_object->icon,
                    $menu_object->title,
                    $menu_object->items
                ));
            }
        }

        if (!empty($system_menu)) {
            $system_menu = sprintf('<li>
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="quick-add-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="fa fa-plus text-warning"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="quick-add-menu">
                                        %1$s
                                    </ul>
                                </div>
                            </li>', $system_menu);
        }

        echo $system_menu;
    }

}

?>