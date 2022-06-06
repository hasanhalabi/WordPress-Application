<!DOCTYPE html>

<html dir="ltr" lang="<?php bloginfo('language') ?>" class="no-js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="user-local" content="<?php echo get_user_locale(get_current_user_id())	?>" />
    <title>
        <?php
	global $page_title;
	if ($page_title != "") {echo "$page_title | ";
	} bloginfo('name');
 ?>
    </title>

    <?php wp_head(); ?>
</head>

<body class="gray-bg <?php echo (is_rtl()? 'rtls':'') ?>">
