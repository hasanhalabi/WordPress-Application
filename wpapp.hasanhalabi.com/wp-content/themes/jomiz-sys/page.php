<?php
jomiz_plugin_system_settings::register_global_object();
jomiz_user_info::register_global_object();
global $jomizSystemSettings;

$registeredplugin = pods('jomiz_registeredplugin', array(
    'limit' => -1,
    'orderby' => 'display_order DESC'
        ));
$total_display_widgets = 0;
$total_widgets = 0;
$tasks = array();
$widgets_urls = array();
global $current_user_info;
global $wpdb;

while ($registeredplugin->fetch()) {
    $plugin_configuration = (object) json_decode($registeredplugin->field('configuration'), true);

    if (isset($plugin_configuration->widgets)) {
        foreach ($plugin_configuration->widgets as $widget) {
			$total_widgets++;
            if ($current_user_info->isCapable($plugin_configuration->plugin_info ['code'], 'widget', $widget ['permalink'])) {
                $total_display_widgets++;

                if ($widget['istask'] == true) {
					$task_query = str_replace (
												array('^^NE^^','^^GTQ^^','^^GT^^','^^LTQ^^','^^LT^^','^^USER_ID^^'),
												array('<>','>=','>','<=','<',get_current_user_id()),
												$widget['taskquery']);
                    $tasks = array_merge($tasks, $wpdb->get_results($task_query));
                } else {
                    $widgets_urls[] = sprintf('%1$s%2$s/widgets/%3$s', home_url('/'), $plugin_configuration->plugin_info ['root_page'], $widget ['permalink']);
                }
            }
        }
    }
}

if(HH_USE_HORIZONTAL_MENU == true) {
	get_header('topmenu');
} else {
	get_header();
}

?>
<style type="text/css">
td.project-status.split {
    border-right: 1px solid #e7eaec;
}
</style>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-xs-12 ">
            <div class="row dashboard">
                <div class="col-xs-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5><?php _e('Pending Tasks', 'jomizsystem'); ?></h5>
                        </div>
                        <div class="ibox-content">

                            <div class="row m-b-sm m-t-sm">
                                <div class="col-md-12">
                                    <input id="SearchKey" type="text" placeholder="<?php _e('Search for...', 'jomizsystem'); ?>" class="col-lg form-control">                                    
                                </div>
                                
                            </div>

                            <?php if (sizeof($tasks) > 0): 
							$tasks_html_even = "";
							$tasks_html_odd = "";
							
							$index = 0;
							
							usort($tasks, function($a, $b) {return strcmp($a->created_on, $b->created_on);});
							
							foreach ($tasks as $task){
									$task_html = sprintf('<tr>
										 <td class="project-status <?php echo $project_status_splitter; ?>">
                                                        <span id="SearchScope"
                                                              class="label label-%1$s">%2$s</span>
                                                    </td>
                                                    <td class="project-title">
                                                        <a href="%3$s">%4$s</a>
                                                        <br/>
                                                        <small>
                                                            %5$s %6$s</small>
                                                    </td>
                                                    <td class="project-completion">
                                                        <small>%7$s</small>
                                                        <br/>
                                                        %8$s
                                                    </td>

                                                    <td class="text-left">
                                                        <a href="%3$s"
                                                           class="btn btn-white btn-md"><i class="fa fa-folder"></i>
                                                            %9$s </a>
                                                    </td>
									</tr>',
									$task->action_color,
									$task->action_title,
									home_url($task->task_link),
									$task->task_title,
									__('on', 'jomizsystem'),
									date_format(new DateTime($task->created_on), 'Y-m-d'),
									$task->task_type,
									$task->task_no,
									__('More...', 'jomizsystem'));
									
									if ($index %2 == 0) {
										$tasks_html_even .= $task_html;
									} else {
										$tasks_html_odd .= $task_html;
									}
									$index++;
							}
							
							?>
								<div class="row">
									<div class="col-xs-12 col-md-6">
										<div class="project-list">
											<table id="FilterTable" class="table table-hover">										
												<tbody>
													<?php echo $tasks_html_even; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-xs-12 col-md-6">
										<div class="project-list">
											<table id="FilterTable" class="table table-hover">										
												<tbody>
													<?php echo $tasks_html_odd; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
                            <?php else: ?>
                                <div class="alert alert-info text-center"><?php _e('No Pending Tasks!', 'jomizsystem'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-4 col-lg-6">
            <div class="row">
                <div class="col-lg-12" ng-app="jomizMainModule">
                    <div ng-controller="JomizCoreController" ng-init="appConfig.functions.init()"></div>

                    <?php foreach ($widgets_urls as $widgets_url) : ?>
                        <div class="row">
                            <div class="col-xs-12" ng-include=""
                                 src="'<?php echo $widgets_url ?>'">
                            </div>
                        </div>
                        <br/>
                        <br/>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>



</div>
<script>
    $(document).ready(function () {

        function tasks_filter() {
            filter = new RegExp($("#SearchKey").val(), 'i');
            $("#FilterTable tbody tr").filter(function () {
                $(this).each(function () {
                    found = false;
                    $(this).children().each(function () {
                        content = $(this).html();
                        if (content.match(filter)) {
                            found = true
                        }
                    });
                    if (!found) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
        }
        $('#EmptySearchKey').click(function () {
            $('#SearchKey').val('');
            tasks_filter();
        });

        $("#SearchKey").keyup(function () {
            tasks_filter();
        });


    });
</script>

<?php get_footer(); ?>