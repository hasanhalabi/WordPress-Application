<?php
	/*
	 * Template Name: JoMiz Users Group (Edit)
	 */

	jomiz_user_info::register_global_object();
	global $current_user_info;

	if (!$current_user_info -> isAdmin()) {
		wp_redirect(home_url());
	}

	$group_id = -1;

	if (isset($_GET['id'])) {
		$group_id = $_GET['id'];
	}

	add_filter('wp_title', 'page_title_users_groups', 10, 2);
	get_header();
?>

  <div ng-app="jomizMainModule">
    <div ng-controller="JomizCoreController" ng-init="appConfig.functions.init()">
    </div>
    <div ng-controller="JomizPageController" ng-init='functions.init("form",<?php echo get_operationData() ?>)'>
      <div class="row wrapper border-bottom white-bg page-heading jomiz-fixed-heading">
        <div class="col-md-4">
          <h2><?php printf('%1$s %2$s', __(($group_id == -1 ? 'Add' : 'Edit'), 'jomizsystem'), __('Users Group', 'jomizsystem')); ?></h2>
          <ol class="breadcrumb">
            <li>
              <a href="<?php echo home_url('/usersgroups') ?>">
                <?php _e('Users Groups', 'jomizsystem')?>
              </a>
            </li>
            <li class="active">
              <strong><?php _e(($group_id == -1 ? 'Add' : 'Edit'), 'jomizsystem')?></strong>
            </li>

          </ol>
        </div>
        <div class="col-md-8">
          <div class="title-action">
            <div class="btn-toolbar pull-right" role="toolbar">
              <div class="btn-group btn-group-sm">
                <button type="submit" form="mainform" class="btn-primary btn btn-outline" title="<?php _e('Save', 'jomizsystem')?>" ng-disabled="form.disableToolbar()"><i class="fa fa-floppy-o"></i></button>
                <button type="button" class="btn-primary btn btn-outline dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" ng-disabled="form.disableToolbar()">
                  <span class="caret"></span>
                  <span class="sr-only"><?php _e('Save Options', 'jomizsystem')?></span>
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a ng-click="functions.saveObject('<?php echo home_url('/usersgroupsedit/') ?>')" title="<?php _e('Save &amp; New', 'jomizsystem')?>">
                      <i class="glyphicon glyphicon-plus-sign"></i>
                      <?php _e('Save &amp; New', 'jomizsystem')?>
                    </a>
                  </li>
                  <li>
                    <a ng-click="functions.saveObject('<?php echo home_url('/usersgroups/') ?>')" title="<?php _e('Save &amp; Close', 'jomizsystem')?>">
                      <i class="glyphicon glyphicon-floppy-remove"></i>
                      <?php _e('Save &amp; Close', 'jomizsystem')?>
                    </a>
                  </li>

                </ul>

              </div>
              <div class="btn-group btn-group-sm" role="group">
                <button type="button" ng-click="page.resetPage('<?php echo home_url('/usersgroups/') ?>')" form="mainform" class="btn-danger btn btn-outline " title="<?php _e('Cancel', 'jomizsystem')?>" ng-disabled="!page.isReady()"><i class="fa fa-times-circle"></i></button>
                <button type="button" ng-click="functions.deleteObject('<?php echo home_url('/usersgroups/') ?>')" form="mainform" class="btn btn-danger btn-outline" title="<?php _e('Delete', 'jomizsystem') ?>" href="#" ng-disabled="form.disableDelete()"><i class="fa fa-trash-o"></i></button>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="wrapper wrapper-content " ng-class="{'jomiz-form-not-ready' : !pageInfo.ready} ">
            <div class="row">

              <div class="col-xs-12">
                <div class="alert alert-danger jomiz-form-error-message" ng-show="mainform.$invalid && mainform.$submitted">
                  <h4><i class="fa fa-exclamation-triangle"></i> <?php _e('Error found in the form!', 'jomizsystem')?></h4>
                  <ul>
                    <li ng-show="mainform.ctrUserGroupName.$error.required" jomiz-scroll-to="#ctrUserGroupName">
                      <?php _e('Group Name is required','jomizsystem') ?>
                    </li>
                    <li ng-show="mainform.ctrUserGroupMembers.$error.required" jomiz-scroll-to="#ctrUserGroupMembers">
                      <?php _e('Members are required','jomizsystem') ?>
                    </li>

                  </ul>
                </div>


                <div class="ibox">
                  <div class="ibox-title">
                    <h5><?php _e('Basic Information', 'jomizsystem') ?></h5>
                    <div class="ibox-tools">
                      <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                      </a>
                    </div>
                  </div>
                  <div class="ibox-content">
                    <form id="mainform" name="mainform" novalidate ng-submit="functions.saveObject()">
                      <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-3">
                          <div class="form-group" ng-class="{'has-error':(mainform.$submitted && mainform.ctrUserGroupName.$invalid)}">
                            <label class="control-label">
                              <?php _e('Group Name', 'jomizsystem') ?>
                            </label>
                            <input type="text" id="ctrUserGroupName" name="ctrUserGroupName" ng-required="true" ng-model="pageData.objData.name" class="form-control input-sm">
                          </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-3">
                          <div class="form-group" ng-class="{'has-error':(mainform.$submitted && mainform.ctrUserGroupMembers.$invalid)}">
                            <label class="control-label">
                              <?php _e('Members', 'jomizsystem') ?>
                            </label>
                            <select id="ctrUserGroupMembers" name="ctrUserGroupMembers" ng-required="true" ng-model="pageData.objData.members" ng-options="user as user.username for user in pageData.formData.users track by user.userId" data-placeholder="<?php _e('Chose Members...', 'jomizsystem') ?>" class="form-control input-sm m-b " multiple>

                            </select>
                          </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-3">
                          <div class="form-group">
                            <label class="control-label">
                              <?php _e('Managers', 'jomizsystem') ?>
                            </label>
                            <select id="ctrUserGroupManagers" name="ctrUserGroupManagers" ng-model="pageData.objData.managers" ng-options="user as user.username for user in pageData.formData.users track by user.userId" data-placeholder="<?php _e('Chose Managers...', 'jomizsystem') ?>" class="form-control input-sm m-b " multiple>

                            </select>
                          </div>
                        </div>

                      </div>

                    </form>
                  </div>
                </div>
                <!--Plugins + Pages-->
                <div class="ibox" ng-repeat="plugin in pageData.formData.plugins_components">
                  <div class="ibox-title">
                    <h5>{{plugin.name}}</h5>
                    <div class="ibox-tools">
                      <a class="collapse-link">

                      </a>
                    </div>
                  </div>
                  <div class="ibox-content">

                    <div class="row">
                      <div class="col-xs-12">
                        <div class="row">
                          <div class="col-xs-12 col-sm-2 col-md-3" ng-repeat="page in plugin.capabilities track by $index" ng-class="{'jomiz-break-line':($index % 4 == 0)}">
                            <div class="panel panel-primary">
                              <div class="panel-heading">
                                {{page.title}}
                              </div>
                              <div class="panel-body">
                                <div ng-repeat="cap in page.capabilities">
                                  <label class="control-label">
                                    <input id="privileges-{{cap.code}}" name="privileges-{{cap.code}}" type="checkbox" icheck ng-model="pageData.objData.privileges[cap.code]"> <span>{{cap.display}}</span></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-md-6">
                        <div class="panel panel-success">
                          <div class="panel-heading">
                            <?php _e('Reports','jomizsystem') ?>
                          </div>
                          <div class="panel-body">
                            <div ng-repeat="report in plugin.reports">
                              <label class="control-label">
                                <input id="privileges-{{report.code}}" name="privileges-{{report.code}}" type="checkbox" icheck ng-model="pageData.objData.reports[report.code]"> <span>{{report.display}}</span></label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-md-6">
                        <div class="panel panel-success">
                          <div class="panel-heading">
                            <?php _e('Dashboard','jomizsystem') ?>
                          </div>
                          <div class="panel-body">
                            <div ng-repeat="widget in plugin.widgets">
                              <label class="control-label">
                                <input id="privileges-{{widget.code}}" name="privileges-{{widget.code}}" type="checkbox" icheck ng-model="pageData.objData.widgets[widget.code]"> <span>{{widget.display}}</span></label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="ibox">
                  <div class="ibox-title">
                    <h5><?php _e('Owner Information','jomizsystem') ?></h5>
                    <div class="ibox-tools">
                      <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                      </a>
                      <a class="close-link">
                        <i class="fa fa-times"></i>
                      </a>
                    </div>
                  </div>
                  <div class="ibox-content">
                    <div id="OwnerInformation" name="OwnerInformation" class="row">
                      <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="form-group">
                          <label class="control-label">
                            <?php _e('File Owner','jomizsystem') ?>
                          </label>
                          <p id="ctrFileOwner" name="ctrFileOwner" class="form-control-static">{{pageData.objData.recordOwner}}</p>
                        </div>
                      </div>
                      <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="form-group">
                          <label class="control-label">
                            <?php _e('Created On','jomizsystem') ?>
                          </label>
                          <p id="ctrCreatedOn" name="ctrCreatedOn" class="form-control-static">{{pageData.objData.recordCreatedOn}}</p>
                        </div>
                      </div>
                      <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="form-group">
                          <label class="control-label">
                            <?php _e('Modified On','jomizsystem') ?>
                          </label>
                          <p id="ctrModifiedOn" name="ctrModifiedOn" class="form-control-static">{{pageData.objData.recordModifiedOn}}</p>
                        </div>
                      </div>
                    </div>
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
    </div>
  </div>
  <?php
		get_footer();

		function page_title_users_groups($title, $id = null) {
			$term = 'Add';

			if (isset($_GET['id'])) {
				$term = 'Edit';

			}
			return sprintf('%1$s %2$s', __($term, 'jomizsystem'), __('Users Group', 'jomizsystem'));
		}

		function get_operationData() {
			$operationData = new stdClass();
			$operationData->{'objectType'} = 'usergroups';
			$operationData->{'id'} = -1;

			if (isset($_GET['id'])) {
				$operationData->{'id'} = $_GET['id'];

			}
			
			return json_encode($operationData);
		}
	?>
