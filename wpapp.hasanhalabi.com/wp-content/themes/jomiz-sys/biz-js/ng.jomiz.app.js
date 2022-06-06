var jomizNgApp = angular.module("jomizMainModule", ['ui.bootstrap', 'ui.select', 'ngSanitize', 'ngAnimate', 'toaster', 'oitozero.ngSweetAlert', 'jomiz.translate', 'pascalprecht.translate', 'angles', 'selectize', 'angular.filter']);

jomizNgApp.controller("JomizCoreController", ['$rootScope', '$http', function ($rootScope, $http) {
    $rootScope.appConfig = {};
    $rootScope.appConfig.functions = {};

    $rootScope.appConfig.functions.init = function () {
        $rootScope.appConfig.params = angular.fromJson(jomiz_params);
    }


    $rootScope.rootUtilities = {};
    $rootScope.rootUtilities.exportTableToExcel = function (tableId, fileName) {
        var table = jQuery(document.getElementById(tableId).cloneNode(true));
        table.find('input').remove();
        table.find('button').remove();
        table.find('.no-export').remove();
        var blob = new Blob([table.html()], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
        });
        saveAs(blob, fileName + ".xls");
    }
    $rootScope.rootUtilities.exportTableToPDF = function () {
        window.print();
    }
    $rootScope.rootUtilities.dateDiff = {
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
        }
        , inWeeks: function (d1, d2) {
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
        }
        , inMonths: function (d1, d2) {
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
        }
        , inYears: function (d1, d2) {
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
    $rootScope.rootUtilities.translate = function (dictionary, key) {
        var lang = jQuery('html').attr('lang');

        if (!(lang == 'en' || lang == 'ar')) {
            lang = 'en';
        }

        if (dictionary[lang] == undefined) {
            return 'ERROR! Language is not declared in the dictionary!';
        }

        if (dictionary[lang][key] == undefined) {
            return 'ERROR! Key  is not declared in the current language!';
        }

        return dictionary[lang][key];
    };


            }]);