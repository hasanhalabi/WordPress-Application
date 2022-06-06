<?php
	/*
	 * Template Name: JoMiz Users Group
	 */
	jomiz_user_info::register_global_object();
	global $current_user_info;

	if (!$current_user_info -> isAdmin()) {
		wp_redirect(home_url());
	}

	add_filter('wp_title', 'page_title_users_groups', 10, 2);
	get_header();
?>
  <div ng-app="jomizMainModule">
    <div ng-controller="JomizCoreController" ng-init="appConfig.functions.init()">
    </div>
    <div ng-controller="JomizPageController" ng-init='functions.init("list",<?php echo get_operationData() ?>)'>
      <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-md-4">
          <h2><?php _e('Users Groups', 'jomizsystem') ?></h2>
          <ol class="breadcrumb">

            <li class="active"><strong><?php _e('Users Groups', 'jomizsystem')?></strong>
            </li>

          </ol>
        </div>
        <div class="col-md-8">
          <div class="title-action">
            <button type="button" ng-disabled="listing.disableToolbar()" class="btn btn-primary btn-outline btn-sm" title="<?php _e('Add', 'jomizsystem') ?>" ng-click="listing.goToEditPage('<?php echo home_url('/usersgroupsedit') ?>')"><i class="fa fa-plus-circle"></i></button>
            <button type="button" ng-disabled="listing.disableDelete()" class="btn btn-danger btn-outline btn-sm" title="<?php _e('Delete Selected', 'jomizsystem') ?>" ng-click="functions.deleteSelectedObjects()"><i class="fa fa-trash"></i></button>
            <a class="btn btn-white btn-outline btn-sm" title="<?php _e('Help', 'jomizsystem') ?>" href="#" target="_blank"><i class="fa fa-info-circle"></i></a>
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
                  <div class="ibox-content" style="display: none;">
                    <div class="row">
                      <div class="col-sm-12">
                        <form>
                          <div class="row">
                            <div class="col-xs-12 col-md-6 col-lg-3">
                              <div class="form-group">
                                <label for="ctrUserGroupName" class="font-noraml">
                                  <?php _e('Group Name', 'jomizsystem') ?>
                                </label>
                                <input type="text" class="form-control input-sm" id="ctrUserGroupName" placeholder="<?php _e('Group Name', 'jomizsystem') ?>" ng-model="pageInfo.operationData.filter.name">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-12 <?php _e('text-right', 'jomizsystem') ?>">

                              <button type="button" class="btn btn-primary btn-sm" ng-click="functions.getObjectsList()">
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
                    <h5><?php _e('User Groups', 'jomizsystem') ?></h5>
                  </div>
                  <div class="ibox-content">

                    <div class="table-responsive">
                      <table class="table table-bordered table-hover table-condensed">
                        <thead>
                          <tr>
                            <th class="jomiz-table-select-col">&nbsp;
                            </th>
                            <th>
                              <?php _e('Users Group', 'jomizsystem') ?>
                            </th>

                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr ng-repeat="userGroup in pageData.objectsList">
                            <td class="jomiz-table-select-col">
                              <input type="checkbox" icheck class="i-checks jomiz-selector" ng-model="pageData.selectedObjects[userGroup.id]">
                            </td>
                            <td>{{userGroup.name}}</td>

                            <td class="action-cell">
                              <div class="btn-group">
                                <!--<button class="btn-white btn btn-xs text-info" title="<?php _e('View', 'jomizsystem'); ?>"><i class="fa fa-eye"></i></button>-->
                                <button type="button" class="btn-white btn btn-xs text-info" title="<?php _e('Edit', 'jomizsystem'); ?>" ng-click="listing.goToEditPage('<?php echo home_url('/usersgroupsedit/') ?>',userGroup.id)"><i class="fa fa-pencil-square-o"></i></button>
                                <button class="btn-white btn btn-xs text-danger" title="<?php _e('Delete', 'jomizsystem'); ?>" ng-click="functions.deleteObjectsList(userGroup.id)"><i class="fa fa-trash-o"></i></button>
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
                                      <?php _e('Number of Groups', 'jomizsystem') ?> <span class="badge badge-primary">{{pageData.pagingInfo.totalRecords}}</span>
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
    </div>
  </div>
  <?php
	get_footer();

	function page_title_users_groups($title, $id = null) {

		return __('Users Groups', 'jomizsystem');
	}
									
	function get_operationData() {
		global $jomizSystemSettings;
		
		$operationData = new stdClass(); 
		
		$operationData->{'objectType'} = 'usergroups'; 
		
		$operationData->{'pageSize'} = $jomizSystemSettings->__get('page_size'); 
		$operationData->{'orderby'} = "";
		$operationData->{'filter'} = new stdClass();
		
		return json_encode($operationData); 
	}
?>
