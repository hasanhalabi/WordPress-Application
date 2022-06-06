
var lcManagerAjax = {
    processData: function (theDomain, theDataSource, theDataSegment, theParams, success, repsonseType) {
        if (repsonseType == undefined) {
            repsonseType = 'json';
        }

        if (success == undefined) {
            success = function (data, textStatus, jqXHR) {
                console.log(['', data, textStatus, jqXHR]);
            }
        }



        var requestData = {
            domain: theDomain
            , datasource: theDataSource
            , datasegment: theDataSegment
            , params: theParams
            , dataKey: 'edt-ajax'
            , repsonseType: repsonseType
        };
        //var requestDataJson = encodeURI(angular.toJson(requestData, false));
        jQuery.post(
                lc_manager_api.ajaxurl,
                {
                    action: 'lc_manager_api',
                    data: JSON.stringify(requestData),
                    dataType: 'json',
                },
                function (response) {

                    success(response);
                }
        );
    },
    lcManagercheckkey: function () {
        var key = jQuery("#lc_manager_activation_key").val();
        jQuery("#lc_submit_key").attr("disabled", true);

        
        if (key == undefined || key == '')
        {
            jQuery("#input_manager_key").removeAttr('hidden');
            jQuery('#input_manager_key').html('Please Enter Your Activation Key ?!');
            jQuery("#lc_submit_key").removeAttr("disabled");
            return;
        }
        lcManagerAjax.processData(
                "",
                "lc_licensing",
                "lc_check_activation_key",
                key,
                function (response) {
                    
                    if (response.theData.results = 'not-ok')
                    {
                        
                        jQuery('#input_manager_key').removeAttr('hidden');
                        jQuery('#input_manager_key').html(response.theData.reason);
                        jQuery("#lc_submit_key").removeAttr("disabled");
                    }
                    if (response.theData.results = 'ok')
                    {
                        location.reload();
                    }





                },
                );


    }
};
