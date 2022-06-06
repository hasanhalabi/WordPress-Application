jomizNgApp.controller("JomizUserPageController", ['$rootScope', '$scope', '$http', '$document', 'toaster', 'SweetAlert', '$translate', function ($rootScope, $scope, $http, $document, toaster, SweetAlert, $translate) {

    $scope.usersystemAppPage = {};
    $scope.userData = {};

    $scope.usersystemAppPage.hideUserModal = function () {
        $scope.mainform.$invalid = false;
        jQuery('#EditUser').modal('hide');
    }
    $scope.usersystemAppPage.showUserModal = function (user_id) {
        if (user_id == undefined) {
            user_id = -1;
        }
        $scope.pageInfo.ready = false;
        var operationData = {
            objectType: "systemuser"
            , id: user_id

        };


        console.log(['RequestData'], {
            operation: 'get-object'
            , dataToProcess: operationData
        });
        $scope.mainform.$invalid = false;
        $scope.mainform.$submitted = false;
        $http({
            method: 'POST'
            , url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl
            , headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
            , data: "requestData=" + angular.toJson({
                operation: 'get-object'
                , dataToProcess: operationData
            })
        }).then(function (response) {
            // Success
            console.log("Get User Successed");
            if (response.data.result == "ok") {
                $scope.userData = response.data.theData;
                console.log("Data Ok!");
                console.log($scope.userData);
                jQuery('#EditUser').modal('show');
            } else {
                console.log("Data Not Ok!");
                console.log(response.data);
            }
            $scope.pageInfo.ready = true;
        }, function (response) {
            // Failed
            console.log("Get User Failed");
            console.log(response);
        });
    }
    $scope.usersystemAppPage.saveUserModal = function (afterSaveUrl) {
        $scope.$emit('event:saveObject');

        delete $scope.mainform.$error.match_password;
        delete $scope.mainform.$error.invalid_user_name;
        delete $scope.mainform.$error.invalid_email;



        if ($scope.userData.objData.user_pass != '' && $scope.userData.objData.user_pass != $scope.userData.objData.Confirm_pass) {
            $scope.mainform.$error.match_password = true;
            $scope.mainform.$invalid = true;
            console.log('mmmmxxx');
        }
        var countUsers = $scope.userData.formData.objUsers.length;
        for (var i = 0; i < countUsers; i++) {
            var theUser = $scope.userData.formData.objUsers[i];



            if ($scope.userData.objData.user_name == theUser.username) {

                console.log(['FUCK name value', $scope.userData.objData.user_name, theUser.username]);
                $scope.mainform.$error.invalid_user_name = true;
                $scope.mainform.$invalid = true;
                console.log('mmmmzzz');
            } else if ($scope.userData.objData.email == theUser.email) {
                $scope.mainform.$error.invalid_email = true;
                $scope.mainform.$invalid = true;
                console.log('mmmmyyy');
            }
        }
        if ($scope.mainform.$invalid) {
            console.log(['Errors In Form', $scope.mainform]);
            return;
        }

        var requestData = angular.toJson({
            operation: 'save-object'
            , dataToProcess: $scope.userData.objData
        });
        console.log(['Request Data', $scope.userData.objData, requestData]);

        jQuery('#EditUser').modal('hide');
        $scope.pageInfo.ready = false;
        $http({
            method: 'POST'
            , url: $rootScope.appConfig.params.baseurl + $scope.pageInfo.apiurl
            , headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
            , data: "requestData=" + angular.toJson({
                operation: 'save-object'
                , dataToProcess: $scope.userData.objData
            })
        }).then(function (response) {
            // Success
            console.log("Save Object Successed");
            if (response.data.result == "ok") {

                $scope.page.notify('success', $scope.dictionary['GENERAL.Info'], $scope.dictionary['SAVE.Success']);
                console.log("Save Ok!");
                console.log($scope.userData);

                $scope.functions.getObjectsList();
            } else {
                $scope.page.notify('error', $scope.dictionary['GENERAL.Error'], $scope.dictionary['GENERAL.TechnicalError']);
                console.log("Data Not Ok!");
                console.log(response.data);
            }
        }, function (response) {
            // Failed
            console.log("Get Object Failed");
            console.log(response);
        });



    }


}]);