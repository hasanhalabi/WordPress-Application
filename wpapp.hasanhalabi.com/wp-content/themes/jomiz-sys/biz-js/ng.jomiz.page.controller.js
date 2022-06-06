jomizNgApp.controller("JomizPageController", ['$rootScope',
    '$scope',
    '$http',
    '$document', 'toaster', 'SweetAlert', '$translate',
    function ($rootScope, $scope, $http, $document, toaster, SweetAlert, $translate) {
        $scope.dictionaryKeys = [
            "DELETE.SingleTitle",
            "DELETE.SingleMessage",
            "DELETE.MultipleMessage", "DELETE.SingleConfirmButtonText"
                    , "DELETE.MultipleConfirmButtonText"
                    , "DELETE.CancelButtonText"
                    , "DELETE.SingleSuccess"
                    , "DELETE.MultipleSuccess"
                    , "DELETE.NoSelectedRecords"
                    , "SAVE.Success"
                    , "SAVE.CustomOpSuccess"
                    , "GENERAL.Info"
                    , "GENERAL.Warning"
                    , "GENERAL.okButtonText"
                    , "GENERAL.Error"
                    , "GENERAL.TechnicalError"
                    , "GENERAL.returnURL"
        ];
        $scope.dictionary = {};
        $translate($scope.dictionaryKeys).then(function (translations) {
            $scope.dictionary = translations;
        });
        $scope.pageInfo = {
            "type": "form",
            "ready": false,
            "operation": "",
            "apiurl": "jomizapi",
            operationData: {}
        };
        $scope.mainform = {};
        $scope.pageData = {};
        $scope.functions = {};
        $scope.functions.init = function (type, operationData) {
            $scope.pageInfo.type = type;
            $scope.pageInfo.operationData = angular.fromJson(operationData);
            if ($scope.pageInfo.type == "form") {
                $scope.functions.getObject();
            } else if ($scope.pageInfo.type == "list") {
                $scope.functions.getObjectsList();
            }
        };

        $scope.functions.show_print_dialog = function (theUrl, id) {
            theUrl = theUrl + id.toString();
            console.log(theUrl);
            jQuery("#print_iframe_url").attr('src', theUrl);
            jQuery("#PrintingModal").modal('show');
        };
        /*$scope.functions.execAPI = function (domain, datasource, datasegment, params, success_fn, failed_fn) {
         $scope.pageInfo.ready = false;
         
         var requestData = {
         operation: 'get-object',
         dataToProcess: {
         domain: domain,
         objectType:
         }
         };
         };*/

        $scope.functions.getObject = function () {
            $scope.pageInfo.ready = false;
            if ($scope.pageData.objData != undefined && $scope.pageData.objData.id > 0) {
                $scope.pageInfo.operationData.id = $scope.pageData.objData.id;
            }
            console.log(['RequestData'], {
                operation: 'get-object',
                dataToProcess: $scope.pageInfo.operationData
            });
            $http({
                method: 'POST',
                url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: "requestData=" + angular.toJson({
                    operation: 'get-object',
                    dataToProcess: $scope.pageInfo.operationData
                })
            }).then(function (response) {
                // Success
                console.log("Get Object Successed");
                if (response.data.result == "ok") {
                    $scope.pageData = response.data.theData;
                    console.log("Data Ok!");
                    console.log($scope.pageData);
                } else {
                    console.log("Data Not Ok!");
                    console.log(response.data);
                }
                $scope.pageInfo.ready = true;
            }, function (response) {
                // Failed
                console.log("Get Object Failed");
                console.log(response);
            });
        }
        $scope.functions.saveObject = function (afterSaveUrl) {
            $scope.$emit('event:saveObject');
            if ($scope.mainform.$invalid) {
                console.log(['Errors In Form', $scope.mainform]);
                return;
            }
            var requestData = angular.toJson({
                operation: 'save-object',
                dataToProcess: $scope.pageData.objData
            });
            console.log(['Request Data', $scope.pageData.objData, requestData]);

            $scope.pageInfo.ready = false;
            var id_before_save = -1;

            if ($scope.pageData !== undefined) {
                if ($scope.pageData.objData !== undefined) {
                    if ($scope.pageData.objData.objectType == "traffic" || $scope.pageData.objData.objectType == 'receipt-customer-orders-from-dryclean-shop') {
                        afterSaveUrl = undefined;
                    }
                    if ($scope.pageData.objData.objectType == "customer-invoice") {
                        id_before_save = '';
                    } else {
                        id_before_save = $scope.pageData.objData.id;
                    }


                }
            }

            $http({
                method: 'POST',
                url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: "requestData=" + angular.toJson({
                    operation: 'save-object',
                    dataToProcess: $scope.pageData.objData
                })
            }).then(function (response) {
                // Success
                $scope.pageData = response.data.theData;

                console.log("Save Object Successed");
                if (response.data.result == "ok") {
                    if ($scope.pageData.objData.id == -2)
                    {
                        $scope.page.notify('error', $scope.dictionary['GENERAL.Error'], $scope.dictionary['GENERAL.TechnicalError']);
                    } else {
                        $scope.page.notify('success', $scope.dictionary['GENERAL.Info'], $scope.dictionary['SAVE.Success']);
                        $scope.pageData = response.data.theData;
                        console.log("Save Ok!");
                        console.log($scope.pageData);
                    }
                } else {
                    $scope.page.notify('error', $scope.dictionary['GENERAL.Error'], $scope.dictionary['GENERAL.TechnicalError']);
                    console.log("Data Not Ok!");
                    console.log(response.data);
                }

                if (afterSaveUrl != undefined && response.data.result == "ok") {
                    if ($scope.pageData.objData.objectType == "customer-order")
                    {
                        return window.location = window.location.href.replace("-edit", '');
                    }
                    window.location = afterSaveUrl;




                } else if (id_before_save == -1 && $scope.pageData.objData.id > 0) {
                    if ($scope.pageData.objData.objectType == "customer-order")
                    {
                        return window.location = window.location.href.replace("-edit", '');
                    }
                    window.location = window.location.href.concat("?id=", $scope.pageData.objData.id);
                } else {
                    if ($scope.pageData.objData.objectType == "customer-order")
                    {
                        return window.location = window.location.href.replace("-edit", '');
                    }
                    $scope.pageInfo.ready = true;
                }
            }, function (response) {
                // Failed
                console.log("Get Object Failed");
                console.log(response);
            });
        }
        $scope.functions.deleteObject = function (afterDeleteURL) {
            if (afterDeleteURL == undefined) {
                $scope.page.notify('error', $scope.dictionary['GENERAL.Error'], $scope.dictionary['GENERAL.returnURL']);
                return;
            }
            SweetAlert.swal({
                title: $scope.dictionary['DELETE.SingleTitle'],
                text: $scope.dictionary['DELETE.SingleMessage'],
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: $scope.dictionary['DELETE.SingleConfirmButtonText'],
                cancelButtonText: $scope.dictionary['DELETE.CancelButtonText'],
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $scope.pageInfo.ready = false;
                    $http({
                        method: 'POST',
                        url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        data: "requestData=" + angular.toJson({
                            operation: 'delete-object',
                            dataToProcess: $scope.pageData.objData
                        })
                    }).then(function (response) {
                        // Success
                        console.log("Delete Object Successed");
                        if (response.data.result == "ok") {
                            $scope.page.notify('info', $scope.dictionary['GENERAL.Info'], $scope.dictionary['DELETE.SingleSuccess']);
                            $scope.pageData = response.data.theData;
                            console.log("Delete Ok!");
                            console.log($scope.pageData);
                        } else {
                            $scope.page.notify('error', $scope.dictionary['GENERAL.Error'], $scope.dictionary['GENERAL.TechnicalError']);
                            console.log("Data Not Ok!");
                            console.log(response.data);
                        }
                        if (afterDeleteURL != undefined && response.data.result == "ok") {
                            window.location = afterDeleteURL;
                        } else {
                            $scope.pageInfo.ready = true;
                        }
                    }, function (response) {
                        // Failed
                        console.log("Delete Object Failed");
                        console.log(response);
                    });
                }
            });
        }
        $scope.functions.getObjectsList = function (pageToFetch) {
            $scope.pageInfo.ready = false;
            if (pageToFetch == undefined) {
                pageToFetch = 1;
            }
            $scope.pageInfo.operationData.pageToFetch = pageToFetch;


            $scope.listing.paginationRangeInfo = {};
            console.log(['Operation Data', $scope.pageInfo.operationData]);
            $http({
                method: 'POST',
                url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: "requestData=" + angular.toJson({
                    operation: 'list-objects',
                    dataToProcess: $scope.pageInfo.operationData
                })
            }).then(function (response) {
                // Success
                console.log("Get Object Successed");
                if (response.data.result == "ok") {
                    $scope.pageData = response.data.theData;
                    $scope.listing.createPaginationRangeInfo();
                    console.log("Data Ok!");
                    console.log($scope.pageData);

                } else {
                    console.log("Data Not Ok!");
                    console.log(response.data);
                }
                $scope.pageInfo.ready = true;
            }, function (response) {
                // Failed
                console.log("Get Object Failed");
                console.log(response);
            });
        }
        $scope.functions.deleteSelectedObjects = function () {
            if ($scope.pageData.selectedObjects.length == 0) {
                SweetAlert.swal({
                    title: $scope.dictionary['GENERAL.Warning'],
                    text: $scope.dictionary['DELETE.NoSelectedRecords'],
                    type: "warning",
                    confirmButtonText: $scope.dictionary['GENERAL.okButtonText']
                });
                return;
            }
            if ($scope.listing.disableDelete()) {
                return;
            }
            var listOfObjectsIds = [];
            for (var i = 0; i < $scope.pageData.selectedObjects.length; i++) {
                if ($scope.pageData.selectedObjects[i]) {
                    listOfObjectsIds.push(i);
                }
            }
            console.log(listOfObjectsIds);
            $scope.functions.deleteObjectsList(listOfObjectsIds);
        }
        $scope.functions.deleteObjectsList = function (listOfObjectsIds) {
            var deleteOperationData = {
                objectType: $scope.pageInfo.operationData.objectType,
                domain: $scope.pageInfo.operationData.domain,
                pageSize: $scope.pageInfo.operationData.pageSize,
                objectsToDelete: []
            };
            var sAleartParams = {
                title: $scope.dictionary['DELETE.SingleTitle'],
                text: $scope.dictionary['DELETE.MultipleMessage'],
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: $scope.dictionary['DELETE.MultipleConfirmButtonText'],
                cancelButtonText: $scope.dictionary['DELETE.CancelButtonText'],
                closeOnConfirm: true,
                closeOnCancel: true
            };
            if (Array.isArray(listOfObjectsIds)) {
                deleteOperationData.objectsToDelete = listOfObjectsIds;
            } else {
                deleteOperationData.objectsToDelete.push(listOfObjectsIds);
                sAleartParams.text = $scope.dictionary['DELETE.SingleMessage'];
                sAleartParams.confirmButtonText = $scope.dictionary['DELETE.SingleConfirmButtonText'];
            }
            // Confirm Delete Message
            SweetAlert.swal(sAleartParams, function (isConfirm) {
                if (isConfirm) {
                    $scope.pageInfo.ready = false;
                    $http({
                        method: 'POST',
                        url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        data: "requestData=" + angular.toJson({
                            operation: 'delete-objects-list',
                            dataToProcess: deleteOperationData
                        })
                    }).then(function (response) {
                        // Success
                        $scope.page.notify('info', $scope.dictionary['GENERAL.Info'], $scope.dictionary['DELETE.MultipleSuccess']);
                        $scope.page.resetPage();
                    }, function (response) {
                        // Failed
                        $scope.page.notify('error', $scope.dictionary['GENERAL.Error'], $scope.dictionary['GENERAL.TechnicalError']);
                        console.log("Delete Object Failed");
                        console.log(response);
                    });
                }
            });
        }
        $scope.functions.saveObjectsList = function (listOfObjectsIds) {
            var saveOperationData = {
                objectType: $scope.pageInfo.operationData.objectType,
                domain: $scope.pageInfo.operationData.domain,
                pageSize: $scope.pageInfo.operationData.pageSize,
                objectsToSave: []
            };
            var sAleartParams = {
                title: "هل ترغب بتأكيد السند!",
                text: "في حال تأكيد السند لن تتمكن من الوصول اليها!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "نعم قم بتأكيد السند.",
                cancelButtonText: "لا تقم بالتأكيد؟!",
                closeOnConfirm: true,
                closeOnCancel: true
            };
            if (Array.isArray(listOfObjectsIds)) {
                saveOperationData.objectsToSave = listOfObjectsIds;
            } else {
                saveOperationData.objectsToSave.push(listOfObjectsIds);
                saveOperationData.objectsToSave.push("confirmVoucher");
                sAleartParams.text = "في حال تأكيد السند لن تتمكن من الوصول اليها!";
                sAleartParams.confirmButtonText = "نعم قم بتأكيد السند.";
            }
            // Confirm Delete Message
            SweetAlert.swal(sAleartParams, function (isConfirm) {
                if (isConfirm) {
                    $scope.pageInfo.ready = false;
                    $http({
                        method: 'POST',
                        url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        data: "requestData=" + angular.toJson({
                            operation: 'save-object',
                            dataToProcess: saveOperationData
                        })
                    }).then(function (response) {
                        // Success
                        $scope.page.notify('success', $scope.dictionary['GENERAL.Info'], $scope.dictionary['SAVE.Success']);
                        //$scope.page.resetPage();
                        window.location = window.location.href;

                    }, function (response) {
                        // Failed
                        $scope.page.notify('error', $scope.dictionary['GENERAL.Error'], $scope.dictionary['GENERAL.TechnicalError']);
                        console.log("Delete Object Failed");
                        console.log(response);
                    });
                }
            });
        }
        $scope.functions.cancelConfirmReceiptVoucher = function (listOfObjectsIds) {
            var saveOperationData = {
                objectType: $scope.pageInfo.operationData.objectType,
                domain: $scope.pageInfo.operationData.domain,
                pageSize: $scope.pageInfo.operationData.pageSize,
                objectsToSave: []
            };
            var sAleartParams = {
                title: "هل ترغب بإلغاء تأكيد السند!",
                text: "في حال إلغاء تأكيد السند سوف تقوم بإرجاع الحالة الى قيد التنفيذ هل انت متأكد!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "نعم قم بإلغاء التأكيد.",
                cancelButtonText: "لا تقم بإلغاء التأكيد",
                closeOnConfirm: true,
                closeOnCancel: true
            };
            if (Array.isArray(listOfObjectsIds)) {
                saveOperationData.objectsToSave = listOfObjectsIds;
            } else {
                saveOperationData.objectsToSave.push(listOfObjectsIds);
                saveOperationData.objectsToSave.push("cancelVoucher");
                sAleartParams.text = "في حال إلغاء تأكيد السند سوف تقوم بإرجاع الحالة الى قيد التنفيذ هل انت متأكد!";
                sAleartParams.confirmButtonText = "نعم قم بإلغاء التأكيد.";
            }
            // Confirm Delete Message
            SweetAlert.swal(sAleartParams, function (isConfirm) {
                if (isConfirm) {
                    $scope.pageInfo.ready = false;
                    $http({
                        method: 'POST',
                        url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        data: "requestData=" + angular.toJson({
                            operation: 'save-object',
                            dataToProcess: saveOperationData
                        })
                    }).then(function (response) {
                        // Success
                        $scope.page.notify('success', $scope.dictionary['GENERAL.Info'], $scope.dictionary['SAVE.Success']);
                        //$scope.page.resetPage();
                        window.location = window.location.href;

                    }, function (response) {
                        // Failed
                        $scope.page.notify('error', $scope.dictionary['GENERAL.Error'], $scope.dictionary['GENERAL.TechnicalError']);
                        console.log("Delete Object Failed");
                        console.log(response);
                    });
                }
            });
        }
        $scope.functions.execCustomOperation = function (domain, pageType, operationType, operationData) {
            $scope.$emit('event:execCustomOperation');
            if ($scope.mainform.$invalid) {
                return;
            }
            console.log(['execCustomOperation', pageType, operationType, operationData]);
            $scope.pageInfo.ready = false;
            $http({
                method: 'POST',
                url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: "requestData=" + angular.toJson({
                    domain: domain,
                    operation: operationType,
                    dataToProcess: operationData
                })
            }).then(function (response) {
                // Success
                console.log("Custom Operation Successed");
                if (response.data.result == "ok") {
                    $scope.page.notify('success', $scope.dictionary['GENERAL.Info'], $scope.dictionary['SAVE.CustomOpSuccess']);
                    if (pageType == 'form') {
                        $scope.functions.getObject();
                    } else if (pageType == 'listing') {
                        $scope.functions.getObjectsList();
                    }
                } else {
                    $scope.page.notify('error', $scope.dictionary['GENERAL.Error'], $scope.dictionary['GENERAL.TechnicalError']);
                    console.log("Custom Operation Not Ok!");
                    console.log(response.data);
                }

            }, function (response) {
                // Failed
                console.log("Get Object Failed");
                console.log(response);
            });
        }


        // General GUI
        $scope.page = {};
        $scope.page.isReady = function () {
            if ($scope.pageInfo == undefined || $scope.pageInfo == {}) {
                return false;
            }
            return $scope.pageInfo.ready;
        }
        $scope.page.resetPage = function (returnURL) {
            if ($scope.pageInfo.type == "form") {
                if (returnURL == undefined) {
                    $scope.functions.getObject();
                } else {

                    window.location = returnURL;
                    $scope.pageInfo.ready = false;
                }
            } else {
                $scope.pageInfo.operationData.filter = {};
                $scope.functions.getObjectsList();
            }
        }
        $scope.page.notify = function (type, title, body) {
            if (type == undefined) {
                type = 'error';
            }
            if (title == undefined) {
                title = 'No Title!!';
            }
            if (body == undefined) {
                body = 'No Message Body';
            }
            toaster.pop({
                type: type,
                title: title,
                body: body,
                showCloseButton: true,
                preventDuplicates: true,
                timeout: -1
            });
        }
        // Form GUI controlling
        $scope.form = {};
        $scope.form.disableToolbar = function () {
            return !($scope.pageInfo.ready && $scope.mainform.$valid);
        }
        $scope.form.disableDelete = function () {
            return !($scope.pageInfo.ready && $scope.pageData.objData.id > 0);
        }
        // Listing GUI controlling
        $scope.listing = {};
        $scope.listing.disableToolbar = function () {
            return !($scope.pageInfo.ready);
        }
        $scope.listing.goToEditPage = function (editURL, id) {
            if (id != undefined) {
                editURL = editURL + "?id=" + id;
            }

            window.location = editURL;
            $scope.pageInfo.ready = false;
        }
        $scope.listing.showPagination = function () {
            if ($scope.pageData.pagingInfo == undefined ||
                    $scope.pageData.pagingInfo.totalPages == undefined ||
                    $scope.pageData.pagingInfo.totalPages == 0 ||
                    $scope.pageData.pagingInfo.totalPages == 1) {
                return false;
            }
            return true;
        }
        $scope.listing.rangePagination = function () {
            if ($scope.pageData.pagingInfo == undefined ||
                    $scope.pageData.pagingInfo.totalPages == undefined) {
                return [];
            }
            var range = [];
            for (var i = 0; i < $scope.pageData.pagingInfo.totalPages; i++) {
                range.push(i + 1);
            }
            return range;
        }
        $scope.listing.paginationRangeInfo = {};

        $scope.listing.createPaginationRangeInfo = function () {
            if ($scope.pageData.pagingInfo == undefined ||
                    $scope.pageData.pagingInfo.totalPages == undefined) {
                return [];
            }
            var range = [],
                    totalPages = Math.floor($scope.pageData.pagingInfo.totalPages),
                    currentPage = $scope.pageData.pagingInfo.currentPage,
                    useArrowBefore = false,
                    useArrowAfter = false,
                    arrowBeforePage = 0,
                    arrowAfterPage = 0;

            if (totalPages <= 10) {
                // No Arrows Less than 10 pages
                for (var i = 0; i < totalPages; i++) {
                    range.push(i + 1);
                }
            } else if (currentPage <= 10) {
                for (var i = 0; i < 10; i++) {
                    range.push(i + 1);
                }
                useArrowAfter = true;
                arrowAfterPage = 11;
                // One of the first 10 pages
            } else if (totalPages - 10 < currentPage) {
                for (var i = totalPages - 10; i < totalPages; i++) {
                    range.push(i + 1);
                }
                useArrowBefore = true;
                arrowBeforePage = totalPages - 10;
                // One of the last 10 pages
            } else {
                // In The Middle
                useArrowBefore = true;
                useArrowAfter = true;
                arrowBeforePage = currentPage - 1;
                arrowAfterPage = currentPage + 10;

                for (var i = currentPage - 1; i < currentPage + 9; i++) {
                    range.push(i + 1);
                }
            }

            $scope.listing.paginationRangeInfo = {
                currentPage: currentPage,
                totalPages: totalPages,
                useArrowBefore: useArrowBefore,
                useArrowAfter: useArrowAfter,
                arrowBeforePage: arrowBeforePage,
                arrowAfterPage: arrowAfterPage,
                range: range
            };

            console.log(['Paging', $scope.listing.paginationRangeInfo]);
        }
        $scope.listing.disableDelete = function () {
            if ($scope.listing.disableToolbar() ||
                    $scope.pageData == undefined ||
                    $scope.pageData.selectedObjects == undefined ||
                    $scope.pageData.selectedObjects.length == 0) {
                return true;
            }
            for (var i = 0; i < $scope.pageData.selectedObjects.length; i++) {
                if ($scope.pageData.selectedObjects[i]) {
                    return false;
                }
            }
            return true;
        }
        $scope.listing.isDataEmpty = function () {
            if ($scope.pageData == undefined ||
                    $scope.pageData.objectsList == undefined ||
                    $scope.pageData.objectsList.length == 0) {
                return true;
            }
            return false;
        }
        // Utilities
        $scope.utilities = {};

        $scope.utilities.pushObjectToArray = function (arr, obj) {
            if (arr == undefined) {
                arr = array();
            }
            arr.push(obj);
        }
        $scope.utilities.removeObjectFromArray = function (arr, obj, useConfirmation) {
            if (useConfirmation == undefined) {
                useConfirmation = false;
            }
            if (useConfirmation) {
                if (confirm("Are you sure?") == false) {
                    return;
                }
            }
            if (arr == undefined) {
                arr = array();
            }
            var index = arr.indexOf(obj);
            arr.splice(index, 1);
        }
        $scope.utilities.dateDiff = {
            inDays: function (d1, d2) {
                if (typeof d2 === undefined) {
                    d2 = new Date();
                }
                d1 = new Date(d1);
                var t2 = d2.getTime();
                var t1 = d1.getTime();
                var val = parseInt((t2 - t1) / (24 * 3600 * 1000));
                if (isNaN(val)) {
                    return '-';
                }
                return val;
            },
            inWeeks: function (d1, d2) {
                if (typeof d2 === undefined) {
                    d2 = new Date();
                }
                d1 = new Date(d1);
                var t2 = d2.getTime();
                var t1 = d1.getTime();
                var val = parseInt((t2 - t1) / (24 * 3600 * 1000 * 7));
                if (isNaN(val)) {
                    return '-';
                }
                return val;
            },
            inMonths: function (d1, d2) {
                if (d2 == undefined) {
                    d2 = new Date();
                }
                d1 = new Date(d1);
                var d1Y = d1.getFullYear();
                var d2Y = d2.getFullYear();
                var d1M = d1.getMonth();
                var d2M = d2.getMonth();
                var val = (d2M + 12 * d2Y) - (d1M + 12 * d1Y);
                if (isNaN(val)) {
                    return '-';
                }
                return val;
            },
            inYears: function (d1, d2) {
                if (d2 === undefined) {
                    d2 = new Date();
                }
                d1 = new Date(d1);
                var val = d2.getFullYear() - d1.getFullYear();
                if (isNaN(val)) {
                    return '-';
                }
                return val;
            }
        }
        $scope.utilities.isEmpty = function (obj) {
            // null and undefined are "empty"
            if (obj == null)
                return true;
            // Assume if it has a length property with a non-zero value
            // that that property is correct.
            if (obj.length > 0)
                return false;
            if (obj.length === 0)
                return true;
            // Otherwise, does it have any properties of its own?
            // Note that this doesn't handle
            // toString and valueOf enumeration bugs in IE < 9
            for (var key in obj) {
                if (hasOwnProperty.call(obj, key))
                    return false;
            }
            return true;
        }
        $scope.utilities.exportTableToExcel = function (tableId, fileName) {
            var table = jQuery(document.getElementById(tableId).cloneNode(true));
            table.find('input').remove();
            //table.find('button').remove();
            table.find('.no-export').remove();
            var blob = new Blob([table.html()], {
                type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
            });
            saveAs(blob, fileName + ".xls");
        }
    }]);
