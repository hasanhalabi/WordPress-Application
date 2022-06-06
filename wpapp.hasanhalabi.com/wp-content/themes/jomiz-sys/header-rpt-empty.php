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
            <?php
								global $page_title;
								if ($page_title != "") {
									echo "$page_title | ";
								}
								bloginfo ( 'name' );
								?>
        </title>

        <?php wp_head(); ?>
            <script>
                var jomiz_params = '<?php echo  core_utilities::get_javascript_header_object(); ?>';
            </script>
            <style>
                .jomiz-rpt-wrapper .form-group {
                    margin-bottom: 7px;
                }
                
                .jomiz-rpt-wrapper .control-label {
                    padding-top: 1px;
                    text-align: right;
                }
                
                .jomiz-rpt-wrapper .form-control-static {
                    padding-top: 1px;
                    border-bottom: dotted 1px;
                    padding-bottom: 0px;
                    min-height: auto;
                }
            </style>
    </head>

    <body class="gray-bg <?php echo (is_rtl()? 'rtls':'') ?>">
        <div class="container jomiz-rpt-container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="jomiz-rpt-wrapper" ng-app="jomizMainModule">
                        <div ng-controller="JomizCoreController" ng-init="appConfig.functions.init()"></div>
                        <div class="row hidden-print">
                            <div class="col-xs-12 text-right m-b">
                                <div class="btn-group btn-group-sm" role="group">
                                     <button type="button" class="btn btn-white btn-outline btn-sm" title="Print" ng-click='rootUtilities.exportTableToPDF()'><i class="fa fa-print"></i></button>
                                </div>
                            </div>
                        </div>
                        
                        
                        <script type="text/ng-template" id="loading.html">
                            <div class="jomiz-loading-block" ng-hide="rpt.isReady()">
                                <div class="sk-spinner sk-spinner-wave">
                                    <div class="sk-rect1"></div>
                                    <div class="sk-rect2"></div>
                                    <div class="sk-rect3"></div>
                                    <div class="sk-rect4"></div>
                                    <div class="sk-rect5"></div>
                                </div>

                                <div class="text-center text-danger jomiz-waiting-message">
                                        الرجاء الانتظارّ! قد تستغرق معالجة بيانات التقرير بعض الوقت.
                                </div>
                            </div>
                        </script>