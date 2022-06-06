<?php
/*
 * Template Name: public_report
 */
//get_header('rpt');
?>
<!DOCTYPE html>

<html dir="ltr" lang="<?php bloginfo('language') ?>" class="no-js">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>
            <?php _e("Receipt Voucher", "dryclean") ?>
        </title>

        <?php wp_head(); ?>
        <script>
            var jomiz_params = '<?php echo core_utilities::get_javascript_header_object(); ?>';
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
    <body class="gray-bg <?php echo (is_rtl() ? 'rtls' : '') ?>">
        <div class="container jomiz-rpt-container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="jomiz-rpt-wrapper" ng-app="jomizMainModule">
                        <div ng-controller="JomizCoreController" ng-init="appConfig.functions.init()"></div>
                        <div class="row hidden-print">
                            <div class="col-xs-12 text-left m-b">
                                <div class="btn-group btn-group-sm" role="group">

                                </div>
                            </div>
                        </div>

                        <script type="text/ng-template" id="toolbar.html">
                            <div class="row">
                            <div class="col-xs-12 text-left m-b">
                            <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-white btn-outline btn-sm text-info" title="Export To Excel" ng-click='rootUtilities.exportTableToExcel("rptDataTable","datatable")'><i class="fa fa-file-excel-o"></i></button>
                            <button type="button" class="btn btn-white btn-outline btn-sm text-danger" title="Export To Excel" ng-click='rootUtilities.exportTableToPDF()'><i class="fa fa-file-pdf-o"></i></button>
                            </div>
                            </div>
                            </div>
                        </script>
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
                        <div ng-controller="JomizRptController" ng-init='getData("dryclean", "reports", "public_bill",<?php echo getExtraData() ?>)' style="font-size: 12px">

                            <div class="col-md-12">
                                <h2>سند إستلام قطع</h2>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <p><strong><?php _e("Customer Name", "dryclean") ?></strong>:{{rptData.data_result.customer_name}}</p>
                            </div>
                            <br>

                            <div class="col-md-6">
                                <p><strong><?php _e("Customer Phone", "dryclean") ?></strong>:{{rptData.data_result.customer_phone}}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><?php _e("date", "dryclean") ?></strong>:{{rptData.data_result.order_date}}</p>
                            </div>

                            <div class="col-md-6">
                                <p> <strong><?php _e("Additional Service Cost", "dryclean") ?></strong>:{{rptData.data_result.invoice_info.additional_service_cost|accnum}}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><?php _e("Discount", "dryclean") ?></strong>:{{rptData.data_result.invoice_info.discount|accnum}}</p>
                            </div>
                            <div class="col-md-12">
                                <p><strong><?php _e("Voucher Total", "dryclean") ?></strong>:{{rptData.data_result.invoice_info.total|accnum}}</p>
                            </div>

                            <div class="row" ng-controller="reportcontroller">
                                <div class="col-md-12">


                                    <div class="table-responsive" id="ctrTransactions"  >
                                        <table class="table table-bordered  table-condensed"   >







                                            <thead>
                                            <th colspan="2" class="text-center">#</th>    
                                            <th colspan="2" class="text-center"><?php _e("price", "dryclean") ?></th>    
                                            <th colspan="2" class="text-center"><?php _e("Quantity", "dryclean") ?></th>    
                                            <th colspan="1" class="text-center">#</th>    
                                            </thead>
                                            <th class="text-center">#</th>
                                            <th class="text-center"><?php _e("Piece", "dryclean") ?></th>
                                            <th class="text-center"><?php _e("Cleaning", "dryclean") ?></th>
                                            <th class="text-center"><?php _e("Iron", "dryclean") ?></th>
                                            <th class="text-center"><?php _e("Cleaning", "dryclean") ?></th>
                                            <th class="text-center"><?php _e("Iron", "dryclean") ?></th>
                                            <th class="text-center"><?php _e("Total", "dryclean") ?></th>
                                            <tbody  ng-repeat="data in rptData.data_result.config">
                                            <td>
                                                {{$index + 1}}
                                            </td>
                                            <td>

                                                {{rptData.data_result.price_name[data.list_price_id].piece}}
                                            </td>
                                            <td>
                                                {{data.cleaning_price|accnum}}
                                            </td>
                                            <td>
                                                {{data.iron_price|accnum}}
                                            </td>
                                            <td>
                                                {{data.cleaning_quantity}}
                                            </td>
                                            <td>
                                                {{data.iron_quantity}}
                                            </td>
                                            <td>
                                                {{(data.cleaning_price * data.cleaning_quantity) + (data.iron_price * data.iron_quantity) | accnum}}
                                            </td>


                                            </tbody>
                                            <thead>
                                            <td>#</td>
                                            <td><?php _e("Total", "dryclean") ?></td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td>{{totalInv()|accnum}}</td>
                                            </thead>
                                        </table>

                                    </div>
                                </div>






                            </div>
                        </div>
                        <script  type="text/javascript">

                            jomizNgApp.controller("reportcontroller", ['$rootScope',
                                    '$scope',
                                    '$http',
                                    '$document', 'toaster', 'SweetAlert', '$translate',
                                    function ($rootScope, $scope, $http, $document, toaster, SweetAlert, $translate) {

                                    $scope.totalInv = function () {
                                        total=0;
                                    angular.forEach($scope.rptData.data_result.config, function (value) {
                                       total=total+(value.cleaning_price * value.cleaning_quantity) + (value.iron_price * value.iron_quantity)      
                                    });
                                    return total;
                                    }
                                    }
                            ]);
                        </script>
                        <?php

                        function getExtraData() {
                            $id = -1;

                            if (isset($_GET ['id'])) {
                                $id = $_GET ['id'];
                            }

                            $params = (object) array(
                                        'id' => $id
                            );
                            return json_encode($params);
                        }

                        get_footer('rpt');
                        ?>ذ