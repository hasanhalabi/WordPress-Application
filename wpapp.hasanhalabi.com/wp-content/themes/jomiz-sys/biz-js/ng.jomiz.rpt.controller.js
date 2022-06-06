jomizNgApp.controller("JomizRptController", ['$rootScope',
    '$scope',
    '$http', 'SweetAlert', 'jTranslate',
    function ($rootScope, $scope, $http, SweetAlert, jTranslate) {
        var dictionary = {
            'ar': {
                'Ok': 'موافق',
                'Cancel': 'إلغاء',
                'NoFilledFiltersTitle': 'تنبيه',
                'NoFilledFilters': 'الرجاء تعبئة الخيارات قبل لإظهار نتائج التقرير'
            },
            'en_US': {
                'Ok': 'Ok',
                'Cancel': 'Cancel',
                'NoFilledFiltersTitle': 'Warning',
                'NoFilledFilters': 'The Filter is not filled!'
            }
        };
        $scope.rptData = {};
        $scope.rptInfo = {
            ready: false,
            apiurl: "jomizapi",
            params: {},
            paramsData: {}
        };

        $scope.rpt = {};
        $scope.rpt.isReady = function () {
            return $scope.rptInfo.ready;
        }
        $scope.rpt.init = function (dataSource, dataSegment, extraData) {
            $scope.rptInfo.ready = false;
            if (extraData.params == undefined) {
                $scope.rptInfo.params = {};
            } else {
                $scope.rptInfo.params = extraData.params;
            }
            $scope.rpt.getParams(dataSource, dataSegment, extraData);
        };

        $scope.rpt.getParams = function (dataSource, dataSegment, extraData) {

            console.log(['getParams', dataSource, dataSegment, extraData]);
            $scope.rptInfo.ready = false;

            var operationData = {
                objectType: dataSource,
                dataSegment: dataSegment,
                extraData: extraData
            };

            $http({
                method: 'POST',
                url: $rootScope.appConfig.params.baseurl + $scope.rptInfo.apiurl,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: "requestData=" + angular.toJson({
                    operation: 'get-data',
                    dataToProcess: operationData
                })
            }).then(function (response) {
                // Success

                console.log("Get ParamsData Successed");

                if (response.data.result == "ok") {
                    if (response.data.theData.result == "ok") {
                        $scope.rptInfo.paramsData = response.data.theData.data;
                        console.log("Data Ok!");
                        console.log($scope.rptInfo.paramsData);
                    } else {
                        console.log("Data Source Error!");
                        console.log(response.data.theData);
                    }
                } else {
                    console.log("Data Not Ok!");
                    console.log(response.data);
                }


                $scope.rptInfo.ready = true;
                console.log(['$scope.rptInfo.ready', $scope.rptInfo.ready]);

            }, function (response) {
                // Failed
                console.log("Get Object Failed");

                console.log(response);

                $scope.rptInfo.ready = true;
            });
        };

        $scope.rpt.execute = function (dataSource, dataSegment, filterCouldBeEmpty) {

            if (filterCouldBeEmpty == undefined) {
                filterCouldBeEmpty = true;
            }
            extraData = $scope.rptInfo.params;
            if (!filterCouldBeEmpty && Object.keys(extraData).length === 0 && JSON.stringify(extraData) === JSON.stringify({})) {
                SweetAlert.swal({
                    title: jTranslate.getDictionaryValue(dictionary, 'NoFilledFiltersTitle'),
                    text: jTranslate.getDictionaryValue(dictionary, 'NoFilledFilters'),
                    type: "warning",
                    confirmButtonText: jTranslate.getDictionaryValue(dictionary, 'Ok')
                });
                return;
            }


            console.log(['execute', dataSource, dataSegment, extraData]);
            $scope.rptInfo.ready = false;

            var operationData = {
                objectType: dataSource,
                dataSegment: dataSegment,
                extraData: extraData
            };

            $http({
                method: 'POST',
                url: $rootScope.appConfig.params.baseurl + $scope.rptInfo.apiurl,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: "requestData=" + angular.toJson({
                    operation: 'get-data',
                    dataToProcess: operationData
                })
            }).then(function (response) {
                // Success

                console.log("Get Object Successed");

                if (response.data.result == "ok") {
                    if (response.data.theData.result == "ok") {
                        $scope.rptData = response.data.theData.data;
                        console.log("Data Ok!");
                        console.log($scope.rptData);
                    } else {
                        console.log("Data Source Error!");
                        console.log(response.data.theData);
                    }
                } else {
                    console.log("Data Not Ok!");
                    console.log(response.data);
                }


                $scope.rptInfo.ready = true;

            }, function (response) {
                // Failed
                console.log("Get Object Failed");

                console.log(response);
            });
        };
        $scope.rpt.clear = function () {
            $scope.rptData = {};
            $scope.rptInfo.params = {};
        }
        $scope.getData = function (domain, dataSource, dataSegment, extraData) {

            console.log(['getData', dataSource, dataSegment, extraData]);
            $scope.rptInfo.ready = false;

            var operationData = {
                domain: domain,
                objectType: dataSource,
                dataSegment: dataSegment,
                extraData: extraData
            };

            console.log(operationData);

            $http({
                method: 'POST',
                url: $rootScope.appConfig.params.baseurl + $scope.rptInfo.apiurl,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: "requestData=" + angular.toJson({
                    operation: 'get-data',
                    dataToProcess: operationData
                })
            }).then(function (response) {
                // Success

                console.log("Get Object Successed");

                if (response.data.result == "ok") {
                    if (response.data.theData.result == "ok") {
                        $scope.rptData = response.data.theData.data;
                        console.log("Data Ok!");
                        console.log($scope.rptData);
                    } else {
                        console.log("Data Source Error!");
                        console.log(response.data.theData);
                    }
                } else {
                    console.log("Data Not Ok!");
                    console.log(response.data);
                }


                $scope.rptInfo.ready = true;

            }, function (response) {
                // Failed
                console.log("Get Object Failed");

                console.log(response);
            });
        }
    }]);
