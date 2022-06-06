<?php
	// Register JoMiz System Settings

	jomiz_plugin_system_settings::register_global_object();
	jomiz_user_info::register_global_object();
	
	global $jomizSystemSettings;
	global $current_user_info;
?>
  <!DOCTYPE html>
  <html dir="ltr" lang="<?php bloginfo('language') ?>" class="no-js">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>
      <?php wp_title() ?>
    </title>
    <?php wp_head(); ?>
      <script>
        var jomiz_params = '<?php echo  core_utilities::get_javascript_header_object(); ?>';

      </script>
  </head>

  <body class="<?php echo (is_rtl()?'rtls':'') ?> ">

    <div id="wrapper">
      <nav id="jomiz-sys-main-menu" class="navbar-default navbar-static-side hidden-print" role="navigation">
        <div class="sidebar-collapse">
          <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
              <div class="dropdown profile-element">
                <div class="text-center">
                  <a href="<?php echo home_url() ?>" title="<?php printf('%1$s - %2$s', get_bloginfo('name'), get_bloginfo('description')) ?>">
                                    <img class="img-circle" src="<?php echo $jomizSystemSettings->__get('logoSmall') ?>" alt="<?php printf('%1$s - %2$s', get_bloginfo('name'), get_bloginfo('description')) ?>" />
                                        </a>
                </div>
                <a data-toggle="dropdown" class="dropdown-toggle" href="#"> <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $current_user_info->userInfo->display_name; ?></strong> </span> <span class="text-muted text-xs block"><?php echo $current_user_info->userInfo->nickname; ?> <b class="caret"></b></span> </span>
                </a>
                <?php echo jomizSysMenus::get_user_menu($current_user_info) ?>
              </div>
              <div class="logo-element">
                <img src="<?php echo $jomizSystemSettings->__get('logoSmall') ?>" alt="<?php printf('%1$s - %2$s', get_bloginfo('name'), get_bloginfo('description')) ?>" />
              </div>
            </li>


            <?php jomizSysMenus::get_main_menu($current_user_info) ?>
          </ul>
        </div>
      </nav>
      <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
          <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
              <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
              <form role="search" class="navbar-form-custom" method="post" action="#">
                <div class="form-group">
                  <input type="text" placeholder="البحث داخل النظام..." class="form-control" name="top-search" id="top-search">
                </div>
              </form>
            </div>
            <ul class="nav navbar-top-links navbar-right">
              <?php jomizSysMenus::get_add_menu($current_user_info) ?>
                <li>
                  <a  title="<?php _e('Home', 'jomizsystem'); ?>" href="<?php echo home_url('') ?>"><i class="fa fa-tachometer text-success"></i> </a>
                </li>
                <li>
                  <a  title="<?php _e('Reports', 'jomizsystem'); ?>" href="<?php echo home_url('/systemreports') ?>"><i class="fa fa-pie-chart text-primary"></i> </a>
                </li>
                <li>
                   <a title="<?php _e('Help', 'jomizsystem'); ?>" href="<?php echo home_url('/help') ?>" ><i class="fa fa-info-circle text-info"></i> </a>
                </li>
                <li>
                  <a title="<?php _e('Logout', 'jomizsystem'); ?>" href="<?php echo logout_url() ?>"><i class="fa fa-sign-out"></i></a>
                </li>
            </ul>
          </nav>
        </div>
