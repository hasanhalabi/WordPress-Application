Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
jomizNgApp.directive('jomizRefreshControlsWatching', function ($timeout) {

    var linker = function (scope, element, attr) {


        var modelToWatch = attr['jomizRefreshControlsWatching'];
        var controlToRefresh = attr['jomizRefreshControls'];


        if (controlToRefresh == undefined) {
            controlToRefresh = 'chosen';
        }

        scope.$watch(modelToWatch, function (newVal, oldVal) {
            $timeout(function () {
                if (newVal != undefined) {
                    if (controlToRefresh == 'checkboxes') {
                        forms_enableICheckPlugin();
                    } else {
                        jQuery(element).chosen({});
                    }
                }
            }, 0, false);
        }, true);
    };

    return {
        restrict: 'A',
        link: linker
    };
}).directive('stringToNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModel) {
            ngModel.$parsers.push(function (value) {
                return '' + value;
            });
            ngModel.$formatters.push(function (value) {
                return parseFloat(value, 10);
            });
        }
    };
}).directive('restrictedDateForm', function () {
    return {
        restrict: 'A',
        scope: {
            minDate: '@',
            maxDate: '@',
            disabled: '=ngDisabled'
        },
        link: function (scope, element, attributes) {
            var reInitDatePicker = function () {
                var _maxDate = moment(scope.maxDate, 'DD-MM-YYYY', true);
                var _minDate = moment(scope.minDate, 'DD-MM-YYYY', true);

                // get its parent and remove old setup
                var _parent = element.parent();
                jQuery(_parent).datepicker('remove');

                // init datepicker only if min/max date is valid & it's not disabled
                if (_minDate.isValid() && _maxDate.isValid() && _maxDate.diff(_minDate) >= 0 && !scope.disabled) {
                    jQuery(_parent).datepicker('remove');
                    jQuery(_parent).datepicker({
                        startView: 0,
                        todayBtn: "linked",
                        keyboardNavigation: true,
                        forceParse: false,
                        autoclose: true,
                        format: "dd-mm-yyyy",
                        startDate: scope.minDate,
                        endDate: scope.maxDate
                    }).on('changeDate', function () {
                        // trigger element change date as date selected
                        angular.element(element).triggerHandler('input');
                    });
                }
            };

            var destroyDatePicker = function () {
                var _parent = element.parent();
                jQuery(_parent).datepicker('remove');
            }

            attributes.$observe('maxDate', function (value) {
                reInitDatePicker();
            });

            attributes.$observe('minDate', function (value) {
                reInitDatePicker();
            });

            // watch disabled attribute and destroy date picker on disabling
            scope.$parent.$watch(attributes.ngDisabled, function (newVal) {
                if (newVal) {
                    destroyDatePicker();
                }
            });
        }
    }
}).directive('dateForm', function () {
    return {
        restrict: 'A',
        scope: {
            disabled: '=ngDisabled'
        },
        link: function (scope, element, attributes) {
            // get its parent and remove old setup
            var _parent = element.parent();
            jQuery(_parent).datepicker('remove');
            jQuery(_parent).datepicker({
                startView: 0,
                todayBtn: "linked",
                keyboardNavigation: true,
                forceParse: false,
                autoclose: true,
                format: "dd-mm-yyyy",
                startDate: undefined,
                endDate: undefined
            }).on('changeDate', function () {
                // trigger element change date as date selected
                angular.element(element).triggerHandler('input');
            });
            // 
            // var destroyDatePicker = function() {
            // 	var _parent = element.parent();
            // 	jQuery(_parent).datepicker('remove');
            // }
            // // watch disabled attribute and destroy date picker on disabling
            // scope.$parent.$watch(attributes.ngDisabled, function(newVal){
            // 	destroyDatePicker();
            // });
        }
    }
}).filter('total', function () {
    return function (input, property) {
        var i = input instanceof Array ? input.length : 0;
        if (typeof property === 'undefined' || i === 0) {
            return i;
        } else if (typeof property === 'function') {
            var total = 0;
            while (i--)
                total += property(input[i]);
            return total;
        } else if (isNaN(input[0][property])) {
            throw 'filter total can count only numeric values';
        } else {
            var total = 0;
            while (i--)
                total += input[i][property];
            return total;
        }
    };
}).filter('accnum', function () {
    return function (input) {

        if (input == 0 || input == undefined) {
            return '-';
        }

        if (typeof (input) == "string") {
            input = parseFloat(input);

            if (isNaN(input)) {
                return '-';
            }
        }

        var output = input.formatMoney(2, '.', ',');

        if (input < 0) {
            output = '(' + output + ")";
        }
        return output;
    };
}).filter('accceil', function () {
    return function (input) {

        if (input == 0 || input == undefined) {
            return '-';
        }

        if (typeof (input) == "string") {
            input = parseFloat(input);

            if (isNaN(input)) {
                return '-';
            }
        }

        var output = Math.floor(input);

       /* var output = input.formatMoney(0, '.', ',');

        if (input < 0) {
            output = '(' + output + ")";
        }*/
        return output;
    };
}).filter('accpoints', function () {
    return function (input) {

        if (input == 0 || input == undefined) {
            return '-';
        }

        if (typeof (input) == "string") {
            input = parseFloat(input);

            if (isNaN(input)) {
                return '-';
            }
        }

        var fixed = Math.floor(input);
        output = Math.abs(Math.round((input - fixed)*1000));
        output = Math.floor(output);


       /* var output = input.formatMoney(3, '.', ',');

        if (input < 0) {
            output = '(' + output + ")";
        }*/
        return output;
    };
}).filter('formatdatestr', function () {
    return function (input) {

        if (input == '' || input == undefined) {
            return '';
        }

        if (typeof (input) == "string") {
            try {
                input = new Date(input);

                if (input === undefined) {
                    return '';
                }
            } catch (e) {
                return '';
            }
        }
        return input;
    };
}).directive("fileread", [function () {
    return {
        scope: {
            fileread: "="
        },
        link: function (scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                var reader = new FileReader();
                reader.onload = function (loadEvent) {
                    scope.$apply(function () {
                        scope.fileread = loadEvent.target.result;
                    });
                }
                reader.readAsDataURL(changeEvent.target.files[0]);
            });
        }
    }
}]);
