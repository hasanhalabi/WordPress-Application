<?php
	// Register JoMiz System Settings

	jomiz_plugin_system_settings::register_global_object();
	jomiz_user_info::register_global_object();
	
	global $jomizSystemSettings;
	global $current_user_info;
?>
  <!DOCTYPE html>
  <html dir="ltr" lang="<?php bloginfo('language') ?>" class="no-js" <?php language_attributes(); ?>>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="user-local" content="<?php echo get_user_locale(get_current_user_id())	?>" />
    <title>
      <?php wp_title() ?>
    </title>
    <?php wp_head(); ?>
      <script>
        var jomiz_params = '<?php echo  core_utilities::get_javascript_header_object(); ?>';

      </script>
  </head>

  <body class="top-navigation <?php echo (get_user_locale(get_current_user_id())=='ar'?'rtls':'') ?> ">

    <div id="wrapper">
      <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
			<nav class="navbar navbar-expand-lg navbar-static-top" role="navigation">

                <a href="<?php echo home_url() ?>" title="<?php printf('%1$s - %2$s', get_bloginfo('name'), get_bloginfo('description')) ?>" class="navbar-brand"><img class="img-circle" src="<?php echo $jomizSystemSettings->__get('logoSmall') ?>" alt="<?php printf('%1$s - %2$s', get_bloginfo('name'), get_bloginfo('description')) ?>" /></a>
                <button class="navbar-toggler jsys-button-direction" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-reorder"></i>
                </button>

            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav mr-auto">
                    
                   
					<?php jomizSysMenus::get_main_menu_horizontal($current_user_info) ?>
					<li class="hidden-md hidden-lg">
					  <a  class="text-warning" title="<?php echo $current_user_info->userInfo->display_name; ?>" href="#"><i class="fa fa-user"></i> <?php echo $current_user_info->userInfo->display_name; ?></a>
					</li>
					<li class="hidden-md hidden-lg">
					  <a title="<?php _e('Logout', 'jomizsystem'); ?>" href="<?php echo logout_url() ?>"><i class="fa fa-sign-out"></i> <?php _e('Logout', 'jomizsystem'); ?></a>
					</li>
                </ul>
                <ul class="nav navbar-top-links navbar-right hidden-xs hidden-sm">
					<!-- <li>
					  <a title="<?php _e('Home', 'jomizsystem'); ?>" href="<?php echo home_url('') ?>"><i class="fa fa-tachometer text-success"></i> <?php _e('Home', 'jomizsystem'); ?></a>
					</li>
					<li>
					  <a title="<?php _e('Reports', 'jomizsystem'); ?>" href="<?php echo home_url('/systemreports') ?>"><i class="fa fa-pie-chart text-primary"></i> <?php _e('Reports', 'jomizsystem'); ?></a>
					</li>
					<li>
						<a title="<?php _e('Help', 'jomizsystem'); ?>" href="<?php echo home_url('/help') ?>" ><i class="fa fa-info-circle text-info"></i> <?php _e('Help', 'jomizsystem'); ?></a>
					</li> -->
					<li>
					  <a class="text-warning" title="<?php echo $current_user_info->userInfo->display_name; ?>" href="#"><i class="fa fa-user"></i> <?php echo $current_user_info->userInfo->display_name; ?></a>
					</li>
					<li>
					  <a title="<?php _e('Logout', 'jomizsystem'); ?>" href="<?php echo logout_url() ?>"><i class="fa fa-sign-out"></i> <?php _e('Logout', 'jomizsystem'); ?></a>
					</li>
                </ul>
            </div>
        </nav>
		</div>
