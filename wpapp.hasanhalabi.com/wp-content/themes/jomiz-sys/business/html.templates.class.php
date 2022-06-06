<?php

class html_templates {

    static public function loading() {
        echo '<div class="jomiz-loading-block" ng-hide="page.isReady()">' . '<div class="sk-spinner sk-spinner-wave"> ' . ' <div class="sk-rect1"></div>' . ' <div class="sk-rect2"></div>' . ' <div class="sk-rect3"></div>' . ' <div class="sk-rect4"></div>' . ' <div class="sk-rect5"></div>' . ' </div>' . '</div>';
    }

    static public function owner_information() {
        vprintf('<div class="ibox">
                  <div class="ibox-title">
                    <h5>%1$s</h5>
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
                            %2$s
                          </label>
                          <p id="ctrFileOwner" name="ctrFileOwner" class="form-control-static">{{pageData.objData.recordOwner}}</p>
                        </div>
                      </div>
                      <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="form-group">
                          <label class="control-label">
                            %3$s
                          </label>
                          <p id="ctrCreatedOn" name="ctrCreatedOn" class="form-control-static">{{pageData.objData.recordCreatedOn}}</p>
                        </div>
                      </div>
                      <div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="form-group">
                          <label class="control-label">
                            %4$s
                          </label>
                          <p id="ctrModifiedBy" name="ctrModifiedBy" class="form-control-static">{{pageData.objData.recordModifiedBy}}</p>
                        </div>
                      </div>
				 		<div class="col-xs-12 col-md-6 col-lg-3">
                        <div class="form-group">
                          <label class="control-label">
                            %5$s
                          </label>
                          <p id="ctrModifiedOn" name="ctrModifiedOn" class="form-control-static">{{pageData.objData.recordModifiedOn}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
             ', array(
            __('Owner Information', 'jomizsystem'),
            __('File Owner', 'jomizsystem'),
            __('Created On', 'jomizsystem'),
            __('Modified By', 'jomizsystem'),
            __('Modified On', 'jomizsystem')
        ));
    }

    static public function owner_information_v2_0() {
        vprintf('<div class="ibox">
											<div class="ibox-title">
												<h5>%1$s</h5>
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
													<div class="col-xs-12">
														<div class="form-group">
															<label class="control-label">
																%2$s
															</label>
															<p id="ctrFileOwner" name="ctrFileOwner"
															   class="form-control-static">
																{{pageData.objData.created_by_name}}</p>
														</div>
														<div class="form-group">
															<label class="control-label">
																%3$s
															</label>
															<p id="ctrCreatedOn" name="ctrCreatedOn"
															   class="form-control-static">
																{{pageData.objData.created_on}}</p>
														</div>
														<div class="form-group">
															<label class="control-label">
																%4$s
															</label>
															<p id="ctrModifiedBy" name="ctrModifiedBy"
															   class="form-control-static">
																{{pageData.objData.modified_by_name}}</p>
														</div>
														<div class="form-group">
															<label class="control-label">
																%5$s
															</label>
															<p id="ctrModifiedOn" name="ctrModifiedOn"
															   class="form-control-static">
																{{pageData.objData.modified_on}}</p>
														</div>
													</div>
												</div>
											</div>
										</div>
             ', array(
            __('Owner Information', 'jomizsystem'),
            __('File Owner', 'jomizsystem'),
            __('Created On', 'jomizsystem'),
            __('Modified By', 'jomizsystem'),
            __('Modified On', 'jomizsystem')
        ));
    }

    static public function breadcrumb($nodes = null) {
        $html = vsprintf('<li>
              <a href="%1$s">
               %2$s
              </a>
            </li>', array(
            home_url(),
            __('Dashboard', 'jomizsystem')
        ));

        if (is_array($nodes)) {
            foreach ($nodes as $node) {
                if ($node->url == 'edit') {
                    $html .= vsprintf('
							<li class="active">
				              <strong>{{pageData.objData.id > 0 ? "%1$s" : "%2$s"}}</strong>
				            </li>
							', array(
                        __('Edit', 'jomizsystem'),
                        __('Add', 'jomizsystem')
                    ));
                } elseif ($node->url == 'active') {
                    $html .= vsprintf('
									<li class="active">
						               <strong>%1$s</strong>
						            </li>', array(
                        $node->title
                    ));
                } else {
                    $html .= vsprintf('
									<li>
						              <a href="%1$s">
						               %2$s
						              </a>
						            </li>', array(
                        home_url($node->url),
                        $node->title
                    ));
                }
            }
        }

        return printf('<ol class="breadcrumb hidden-sm hidden-xs">%1$s</ol>', $html);
    }
 
    static public function attachments($module, $object_type, $object_id, $style="thumbs") {
        if ($object_id <= 0) {
            return;
        }
        echo do_shortcode(vsprintf('[dropzonejs module="%1$s" objecttype="%2$s" objectid="%3$s" style="%4$s"]', array(
            $module,
            $object_type,
            $object_id,
			$style
        )));
    }

    static public function pagination($title = 'Send This') {
        $arrowBefore = "fa-angle-double-left";
        $arrowAfter = "fa-angle-double-right";

        if (is_rtl()) {
            $arrowBefore = "fa-angle-double-right";
            $arrowAfter = "fa-angle-double-left";
        }

        printf('<div class="ibox-footer"><span class="pull-left">
                                        %1$s <span class="badge badge-primary">{{pageData.pagingInfo.totalRecords}}</span>
                                    </span>
				<ul class="pagination" ng-show="listing.showPagination()">
					<li ng-if="listing.paginationRangeInfo.useArrowBefore">
						<a ng-click="functions.getObjectsList(listing.paginationRangeInfo.arrowBeforePage)" aria-controls="editable" data-dt-idx="{{listing.paginationRangeInfo.arrowBeforePage}}"><i class="fa %2$s"></i></a>
					</li>
					<li class="paginate_button " ng-repeat="n in listing.paginationRangeInfo.range" ng-class="{active: n == pageData.pagingInfo.currentPage}">
						<a ng-click="functions.getObjectsList(n)" aria-controls="editable" data-dt-idx="{{n}}">{{n}}</a>
					</li>
					<li ng-if="listing.paginationRangeInfo.useArrowAfter">
						<a ng-click="functions.getObjectsList(listing.paginationRangeInfo.arrowAfterPage)" aria-controls="editable" data-dt-idx="{{listing.paginationRangeInfo.arrowAfterPage}}"><i class="fa %3$s"></i></a>
					</li>
				</ul><div class="clearfix"></div>
                                </div>
				', $title, $arrowBefore, $arrowAfter);
    }

    static public function ctrl_input($args = array()) {
        $args = (object) $args;
        $label = "";
        $input = "";
        if ($args->label != "") {
            $label = sprintf('<label class="control-label">
                                    %1$s
                            </label>', $args->label);
        }
		
		$control_html = sprintf('%1$s
                            <input ng-model="%2$s"  type="%3$s" id="%4$s"
                                       name="%4$s" %5$s
                                       placeholder="%6$s"
                                       %7$s
                                       ng-disabled="%8$s"
									   %7$s
									   %9$s
									   %10$s
                                       class="form-control input-sm ">
									   %11$s
                    ', 
					$label, //1
					$args->ng_model, //2
					$args->type, //3
					$args->ctrl_id, //4
					$args->required, //5
					$args->placeholder, //6
					$args->disabled,//7
					$args->ng_disabled, //8
					(isset($args->minValue)?sprintf('min="%1$s"',$args->minValue):""), // 9
					(isset($args->maxValue)?sprintf('max="%1$s"',$args->maxValue):""), // 10
					$args->input_description // 11
					); 
		
        if ($args->indiv == "true") {
			$control_html = sprintf('<div class="form-group">
                            %1$s</div>',$control_html);
        } 
		
		printf($control_html);
    }
	
	static public function ctrl_file($args = array()) {
        $args = (object) $args;
        $label = "";
        $input = "";
        if ($args->label != "") {
            $label = sprintf('<label class="control-label">
                                    %1$s
                            </label>', $args->label);
        }
		
		$control_html = sprintf('%1$s
                            <input type="file" autocomplete="no" fileread="%2$s" ng-model="%2$s" id="%4$s"
                                       name="%4$s" %5$s
                                       placeholder="%6$s"
                                       %7$s
                                       ng-disabled="%8$s"
									   
                                       class="form-control input-sm ">
									   %9$s
                    ', 
					$label, //1
					$args->ng_model, //2
					"file", //3
					$args->ctrl_id, //4
					$args->required, //5
					$args->placeholder, //6
					$args->disabled,//7
					$args->ng_disabled, //8
					$args->input_description // 9
					); 
		
        if ($args->indiv == "true") {
			$control_html = sprintf('<div class="form-group">
                            %1$s</div>',$control_html);
        } 
		
		printf($control_html);
    }

    static public function ctrl_selectize($args = array()) {
        $args = (object) $args;
        $label = "";
        $input = "";
        if ($args->label != "") {
            $label = sprintf('<label class="control-label">
                                    %1$s
                            </label>', $args->label);
        }
        
		$control_html = sprintf('%1$s
                           <selectize
                         placeholder="%5$s"
                         name="%3$s"
                         id="%3$s" %4$s
                         options="%6$s"
                         ng-model="%2$s"
                         config="%7$s"
                         ng-disabled="%8$s"                        
                         ></selectize>
						 %9$s
                    ', 
					$label, //1
					$args->ng_model, //2
					$args->ctrl_id, //3
					(isset($args->required)&&($args->required==true || $args->required== "required")?'required="true"':''), //4
					$args->placeholder, //5
					$args->options, //6
					$args->config, //7
					$args->ng_disabled, //8
					$args->input_description // 9
					);
		
		if ($args->indiv == "true") {
			$control_html = sprintf('<div class="form-group">
                            %1$s</div>',$control_html);
        } 
		
		printf($control_html);
    }

	static public function ctrl_textarea($args = array()) {
        $args = (object) $args; 
        $label = "";
        $input = "";
        if ($args->label != "") {
            $label = sprintf('<label class="control-label">
                                    %1$s
                            </label>', $args->label);
        }
		
		$control_html = sprintf('%1$s
							<textarea ng-model="%2$s"
							name="%4$s"
							id="%4$s" %5$s
							placeholder="%6$s"
							class="form-control input-sm"
							ng-disabled="%8$s"
							style="min-height:%9$s;"
							%7$s></textarea>
                    ', 
					$label, //1
					$args->ng_model, //2
					$args->type, //3
					$args->ctrl_id, //4
					$args->required, //5
					$args->placeholder, //6
					$args->disabled,//7
					$args->ng_disabled,//8
					$args->min_height,); //9
					
        if ($args->indiv == "true") {
			$control_html = sprintf('<div class="form-group">
                            %1$s</div>',$control_html);
        } 
		
		printf($control_html);
    }
	
	static public function ctrl_datetime($args = array()) {
        $args = (object) $args; 
        $label = "";
        $input = "";
        if ($args->label != "") {
            $label = sprintf('<label class="control-label">
                                    %1$s
                            </label>', $args->label);
        }
		
		$control_html = sprintf('%1$s
							<div class="input-group input-group-sm date">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input ng-model="%2$s" 
								%5$s 
								type="text" 
								class="form-control input-sm" 
								id="%4$s" 
								name="%4$s" 
								placeholder="%6$s" 
								%9$s
								%10$s 
								%11$s
								ng-disabled="%8$s" %7$s>
							</div>
							%12$s
                    ', 
					$label, //1
					$args->ng_model, //2
					$args->type, //3
					$args->ctrl_id, //4
					$args->required, //5
					$args->placeholder, //6
					$args->disabled,//7
					$args->ng_disabled,//8
					(isset($args->min_date) ? sprintf('data-min-date="%1$s"',date_format($args->min_date,"d-m-Y")) :""), //9
					(isset($args->max_date) ? sprintf('data-max-date="%1$s"',date_format($args->max_date,"d-m-Y")) :""), //10
					((isset($args->min_date) || isset($args->max_date))? "restricted-date-form":"") , //11
					$args->input_description //12
					); 
					
        if ($args->indiv == "true") {
			$control_html = sprintf('<div class="form-group">
                            %1$s</div>',$control_html);
        } 
		
		printf($control_html);
    }
}
