<?php
	/*
	 * Template Name: JoMiz System Users
	 */
add_action ( 'wp_enqueue_scripts', 'user_page_scripts' );
	jomiz_user_info::register_global_object();
	global $current_user_info;

	if (!$current_user_info -> isAdmin()) {
		wp_redirect(home_url());
	}

	add_filter('wp_title', 'page_title_users_groups', 10, 2);
	get_header();
?>
    <div ng-app="jomizMainModule">
        <toaster-container toaster-options="{'position-class': 'toast-bottom-center', 'close-button':{ 'toast-warning': true, 'toast-error': false },'limit':1}"></toaster-container>

        <div ng-controller="JomizCoreController" ng-init="appConfig.functions.init()">
        </div>
        <div ng-controller="JomizPageController" ng-init='functions.init("list",<?php echo get_operationData() ?>)'>
            <div ng-controller="JomizUserPageController">
                <div class="row wrapper border-bottom white-bg page-heading">
                    <div class="col-md-4">
                        <h2><?php _e('System Users', 'jomizsystem') ?></h2>
                        <ol class="breadcrumb">

                            <li class="active"><strong><?php _e('System Users', 'jomizsystem')?></strong>
                            </li>

                        </ol>
                    </div>
                    <div class="col-md-8">
                        <div class="title-action">
                            <button ng-disabled="listing.disableToolbar()" class="btn btn-primary btn-outline btn-sm" title="<?php _e('Add', 'jomizsystem') ?>" ng-click="usersystemAppPage.showUserModal()"><i class="fa fa-plus-circle"></i></button>
                            <!--<button type="button" ng-disabled="listing.disableDelete()" class="btn btn-danger btn-outline btn-sm" title="<?php _e('Delete Selected', 'jomizsystem') ?>" ng-click="functions.deleteSelectedObjects()"><i class="fa fa-trash"></i></button>-->

                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-success btn-outline btn-sm" title="<?php _e('Export To Excel', 'jomizsystem') ?>" ng-click='utilities.exportTableToExcel("listingDataTable","datatable")'><i class="fa fa-file-excel-o"></i></button>
                                <a class="btn btn-success btn-outline btn-sm" title="<?php _e('Help', 'jomizsystem') ?>" href="<?php echo home_url('/help') ?>" target="_blank"><i class="fa fa-info-circle"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">

                        <div class="wrapper wrapper-content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="ibox border-bottom">
                                        <div class="ibox-title">
                                            <h5><?php _e('Filter', 'jomizsystem') ?></h5>
                                            <div class="ibox-tools">
                                                <a class="collapse-link">
                                                    <i class="fa fa-chevron-down"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="ibox-content" style="display: none;" ng-submit="functions.getObjectsList()">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <form>
                                                        <div class="row">
                                                            <div class="col-xs-12 col-md-6 col-lg-3">
                                                                <div class="form-group">
                                                                    <label for="ctrUserInformation" class="font-noraml">
                                                                        <?php _e('User information', 'jomizsystem') ?>
                                                                    </label>
                                                                    <input autocomplete="off" type="text" class="form-control input-sm" ng-model="pageInfo.operationData.filter.Infos" id="ctrUserInformation" placeholder="<?php _e('UserInfo', 'jomizsystem') ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12 <?php _e('text-right', 'jomizsystem') ?>">

                                                                <button class="btn btn-primary btn-sm">
                                                                    <?php _e('Search', 'jomizsystem') ?>
                                                                </button>
                                                                <button type="button" class="btn btn-primary btn-outline  btn-sm" ng-click="page.resetPage()">
                                                                    <?php _e('Reset', 'jomizsystem') ?>
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="ibox">
                                        <div class="ibox-title">
                                            <h5><?php _e('System Users', 'jomizsystem') ?></h5>
                                        </div>
                                        <div class="ibox-content">

                                            <div id="listingDataTable" class="table-responsive">
                                                <table class="table table-bordered table-hover table-condensed">
                                                    <thead>
                                                        <tr>
                                                            <th class="jomiz-table-select-col">&nbsp;
                                                            </th>
                                                            <th>
                                                                <?php _e('Name', 'jomizsystem') ?>
                                                            </th>
                                                            <th>
                                                                <?php _e('User Name', 'jomizsystem') ?>
                                                            </th>

                                                            <th>
                                                                <?php _e('Email', 'jomizsystem') ?>
                                                            </th>
                                                            <th>
                                                                <?php _e('Job Title', 'jomizsystem') ?>
                                                            </th>

                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="theObject in pageData.objectsList">
                                                            <td class="jomiz-table-select-col">
                                                                <input type="checkbox" icheck class="i-checks jomiz-selector" ng-model="pageData.selectedObjects[theObject.id]">
                                                            </td>
                                                            <td>{{theObject.display_name}}</td>
                                                            <td>{{theObject.username}}</td>

                                                            <td>{{theObject.email}}</td>
                                                            <td>{{theObject.jobtitle}}</td>


                                                            <td class="action-cell">
                                                                <div class="btn-group">
                                                                    <!--<button class="btn-white btn btn-xs text-info" title="<?php _e('View', 'jomizsystem'); ?>"><i class="fa fa-eye"></i></button>-->
                                                                    <button ng-click="usersystemAppPage.showUserModal(theObject.id)" type="button" class="btn-white btn btn-xs text-info" title="<?php _e('Edit', 'jomizsystem'); ?>"><i class="fa fa-pencil-square-o"></i></button>
                                                                    <!--<button class="btn-white btn btn-xs text-danger" title="<?php _e('Delete', 'jomizsystem'); ?>" ng-click="functions.deleteObjectsList(theObject.id)"><i class="fa fa-trash-o"></i></button>-->
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr ng-show="listing.isDataEmpty()">
                                                            <td colspan="3" class="text-center">
                                                                <?php _e('<b>Warning!</b> No Data Available!','jomizsystem'); ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="ibox-footer">
                                            <span class="pull-right">
                                      <?php _e('Number of Users', 'jomizsystem') ?> <span class="badge badge-primary">{{pageData.pagingInfo.totalRecords}}</span>
                                            </span>
                                            <ul class="pagination" ng-show="listing.showPagination()">

                                                <li class="paginate_button " ng-repeat="n in listing.rangePagination()" ng-class="{active: n == pageData.pagingInfo.currentPage}">
                                                    <a ng-click="functions.getObjectsList(n)" aria-controls="editable" data-dt-idx="{{n}}">{{n}}</a>
                                                </li>

                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="jomiz-loading-block" ng-show="!page.isReady()">
                            <div class="sk-spinner sk-spinner-wave">
                                <div class="sk-rect1"></div>
                                <div class="sk-rect2"></div>
                                <div class="sk-rect3"></div>
                                <div class="sk-rect4"></div>
                                <div class="sk-rect5"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal inmodal fade" data-backdrop="static" id="EditUser" tabindex="-1" role="dialog" aria-hidden="true">

                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <form id="mainform" name="mainform" novalidate ng-submit="usersystemAppPage.saveUserModal()">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h3 class="modal-title">
                                    <?php _e('Edit/Rigester User', 'jomizsystem') ?>
                                    </h3>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <h4>
                                                     <?php _e('Basic Information', 'jomizsystem') ?>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="ctrFirstName" class="font-noraml">
                                                            <?php _e('First Name', 'jomizsystem') ?>
                                                        </label>
                                                        <input required autocomplete="off" type="text" class="form-control input-sm" ng-model="userData.objData.first_name" id="ctrFirstName" placeholder="<?php _e('First Name', 'jomizsystem') ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="ctrLastName" class="font-noraml">
                                                            <?php _e('Last Name', 'jomizsystem') ?>
                                                        </label>
                                                        <input required autocomplete="off" type="text" class="form-control input-sm" ng-model="userData.objData.last_name" id="ctrLastName" placeholder="<?php _e('Last Name', 'jomizsystem') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="ctrJobTitle" class="font-noraml">
                                                            <?php _e('Job Title', 'jomizsystem') ?>
                                                        </label>
                                                        <input required autocomplete="off" type="text" class="form-control input-sm" ng-model="userData.objData.jobtitle" name="ctrJobTitle" id="ctrJobTitle" placeholder="<?php _e('Job Title', 'jomizsystem') ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="ctrEmail" class="font-noraml">
                                                            <?php _e('Email', 'jomizsystem') ?>
                                                        </label>
                                                        <input required autocomplete="off" type="email" class="form-control input-sm" ng-model="userData.objData.email" name="ctrEmail" id="ctrEmail" placeholder="<?php _e('Email', 'jomizsystem') ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <h4>
                                                     <?php _e('Security Information', 'jomizsystem') ?>
                                                    </h4>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <div class="form-group" ng-class="{'form-group-required': userData.objData.id == '-1'}">
                                                        <label for="ctrPassword" class="font-noraml">
                                                            <?php _e('Password', 'jomizsystem') ?>
                                                        </label>
                                                        <input ng-required="userData.objData.id == '-1'" autocomplete="off" type="password" class="form-control input-sm" ng-model="userData.objData.user_pass" name="ctrPassword" id="ctrPassword" placeholder="<?php _e('Password', 'jomizsystem') ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <div class="form-group" ng-class="{'form-group-required': userData.objData.id == '-1'}">
                                                        <label for="ctrPasswordConfirm" class="font-noraml">
                                                            <?php _e('Password Confirm', 'jomizsystem') ?>
                                                        </label>
                                                        <input autocomplete="off" ng-required="userData.objData.id == '-1'" type="password" class="form-control input-sm" ng-model="userData.objData.Confirm_pass" name="ctrPasswordConfirm" id="ctrPasswordConfirm" placeholder="<?php _e('Password Confirm', 'jomizsystem') ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <h4>
                                                     <?php _e('Login Information', 'jomizsystem') ?>
                                                    </h4>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-xs-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="ctrUserName" class="font-noraml">
                                                            <?php _e('User Name', 'jomizsystem') ?>
                                                        </label>
                                                        <input required ng-required="true" autocomplete="off" ng-disabled="userData.objData.exist" type="text" class="form-control input-sm" ng-model="userData.objData.user_name" name="ctrUserName" id="ctrUserName" placeholder="<?php _e('User Name', 'jomizsystem') ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6 col-lg-3">
                                                    <div class="form-group">
                                                        <label for="ctrIsAdmin" class="font-noraml">
                                                            <?php _e('Is Admin', 'jomizsystem') ?>
                                                        </label>
                                                        <div>
                                                            <input icheck ng-model="userData.objData.is_admin" type="checkbox" id="ctrIsAdmin" name="ctrIsAdmin" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6 col-lg-3">
                                                    <div class="form-group">
                                                        <label for="ctrDisable" class="font-noraml">
                                                            <?php _e('Disable This User', 'jomizsystem') ?>
                                                        </label>
                                                        <div>
                                                            <input icheck ng-model="userData.objData.is_disabled" type="checkbox" id="ctrDisable" name="ctrDisable" class="form-control input-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-xs-12 col-xs-12">
                                        <div style="z-index:500000000" class="col-xs-12" ng-show="mainform.$invalid && mainform.$submitted">
                                            <div class="alert alert-danger">
                                                <h4><i class="fa fa-exclamation-triangle"></i> <?php _e('Error found in the form!', 'jomizsystem')?></h4>
                                                <ul>
                                                    <li ng-show="mainform.ctrEmail.$error.required" jomiz-scroll-to="#ctrEmail">
                                                        <?php _e('Email is required','jomizsystem')?>
                                                    </li>
                                                    <li ng-show="mainform.ctrEmail.$error.email" jomiz-scroll-to="#ctrEmail">
                                                        <?php _e('Email is required','jomizsystem')?>
                                                    </li>
                                                    <li ng-show="mainform.$error.invalid_email" jomiz-scroll-to="#ctrEmail">
                                                        <?php _e('This e-mail already exists','jomizsystem')?>
                                                    </li>
                                                    <li ng-show="mainform.ctrJobTitle.$error.required" jomiz-scroll-to="#ctrJobTitle">
                                                        <?php _e('JobTitle is required','jomizsystem')?>
                                                    </li>
                                                    <li ng-show="mainform.ctrPassword.$error.required" jomiz-scroll-to="#ctrPassword">
                                                        <?php _e('Password is required','jomizsystem')?>
                                                    </li>
                                                    <li ng-show="mainform.ctrPasswordConfirm.$error.required" jomiz-scroll-to="#ctrPasswordConfirm">
                                                        <?php _e('Connfirm your password is required','jomizsystem')?>
                                                    </li>
                                                    <li ng-show="mainform.$error.match_password" jomiz-scroll-to="#ctrPasswordConfirm">
                                                        <?php _e('Your password did not match','jomizsystem')?>
                                                    </li>
                                                    <li ng-show="mainform.ctrUserName.$error.required" jomiz-scroll-to="#ctrUserName">
                                                        <?php _e('please insert user name ','jomizsystem')?>
                                                    </li>
                                                    <li ng-show="mainform.$error.invalid_user_name" jomiz-scroll-to="#ctrUserName">
                                                        <?php _e('This user name already exists','jomizsystem')?>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="modal-footer">
                                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       -->
                                    <button type="submit" class="btn btn-ms btn-primary btn-outline" title="<?php _e('Save &amp; New', 'jomizsystem')?>">
                                        <?php _e('Save', 'jomizsystem') ?>
                                    </button>
                                    <button type="button" class="btn btn-ms btn-danger btn-outline" ng-click="usersystemAppPage.hideUserModal()">
                                        <?php _e('Cancel', 'jomizsystem') ?>
                                    </button>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
	get_footer();

	function page_title_users_groups($title, $id = null) {

		return __('System Users', 'jomizsystem');
	}
function user_page_scripts() {
	wp_enqueue_script ( 'users', get_template_directory_uri () . '/biz-js/ng.UserSystem.controller.js', array (
			'jomiz.page.controller' 
	), '1.0.0', FALSE );
}
									
	function get_operationData() {
		global $jomizSystemSettings;
		
		$operationData = new stdClass(); 
		
		$operationData->{'objectType'} = 'systemuser'; 
		
		$operationData->{'pageSize'} = $jomizSystemSettings->__get('page_size'); 
		$operationData->{'orderby'} = "";
		$operationData->{'filter'} = new stdClass();
		
		return json_encode($operationData); 
	}
?>
