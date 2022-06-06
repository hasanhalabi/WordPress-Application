<?php
/*
 * Template Name: JoMiz Reports
 */
$registeredplugin = pods ( 'jomiz_registeredplugin', array (
		'limit' => - 1,
		'orderby' => 'display_order' 
) );

global $current_user_info;
$number_of_reports = 0;

add_filter ( 'wp_title', 'page_title_reports', 10, 2 );
if (HH_USE_HORIZONTAL_MENU == true) {
    get_header('topmenu');
} else {
    get_header();
}
?>
<div>
	<div>
		<div class="row wrapper border-bottom white-bg page-heading">
			<div class="col-xs-12">
				<h2><?php _e('Reports', 'jomizsystem') ?></h2>
				<ol class="breadcrumb">

					<li class="active"><strong><?php _e('Reports', 'jomizsystem')?></strong>
					</li>

				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="wrapper wrapper-content">
					<div class="row">
						<div class="col-xs-12">
							<div class="ibox">
								<div class="ibox-title">
									<h5><?php _e('Reports', 'jomizsystem') ?></h5>
								</div>
								<div class="ibox-content">

									<div class="table-responsive">
										<table
											class="table table-bordered table-hover table-condensed">
											<thead>
												<tr>
													<th style="width: 20%;">
                              النموذج
                            </th>
													<th>
                              <?php _e('Report', 'jomizsystem')?>
                            </th>
												</tr>
											</thead>
											<tbody>
											<?php
											
											while ( $registeredplugin->fetch () ) {
												
												$plugin_configuration = ( object ) json_decode ( $registeredplugin->field ( 'configuration' ), true );
												
												if (isset ( $plugin_configuration->reports )) {
													foreach ( $plugin_configuration->reports as $report ) {
														if ($report ['listed'] && $current_user_info->isCapable ( $plugin_configuration->plugin_info ['code'], 'report', $report ['permalink'] )) {
															$number_of_reports ++;
															printf ( '<tr><td>%1$s</td><td><a href="%3$s" target="_blank">%2$s</a></td></tr>', 
																	// Module Name
																	__ ( $plugin_configuration->plugin_info ['name'], $plugin_configuration->plugin_info ['language_domain'] ), 
																	// Report Name
																	__ ( $report ['title'], $plugin_configuration->plugin_info ['language_domain'] ), 
																	// Home URL
																	jomizSysUntilities::get_report_url ( $plugin_configuration->plugin_info ['root_page'], $report ['permalink'] ) );
														}
													}
												}
											}
											
											if ($number_of_reports == 0) {
												printf ( '<tr><td colspan="2" class="text-center">%1$s</td></tr>', '<b>عذرا!</b>لا يوجد تقارير' );
											}
											?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="ibox-footer">
									<span class="pull-right">
                                      عدد التقارير<span
										class="badge badge-primary"><?php echo $number_of_reports;?></span>
									</span>

									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
get_footer ();
function page_title_reports($title, $id = null) {
	return __ ( 'Reports', 'jomizsystem' );
}
?>
