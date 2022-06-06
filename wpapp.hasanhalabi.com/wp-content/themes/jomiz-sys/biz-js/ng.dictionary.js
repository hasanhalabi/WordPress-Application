function config($translateProvider) {

    $translateProvider
        .translations('en', {
            "DELETE": {
                "SingleTitle": "Are you sure?",
                "SingleMessage": "Your will not be able to recover this record!",
                "MultipleMessage": "Your will not be able to recover these records!",
                "SingleConfirmButtonText": "Yes, delete it!",
                "MultipleConfirmButtonText": "Yes, delete them!",
                "CancelButtonText": "No, cancel please!",
                "SingleSuccess": "Record deleted successfully!",
                "MultipleSuccess": "Records deleted successfully!",
                "NoSelectedRecords": "Please select items to delete!"
            },
            "SAVE": {
                "Success": "Data Saved Successfully!",
                "CustomOpSuccess": "Operation Performed Successfully!"
            },
            "GENERAL": {
                "Info": "Information",
                "Warning": "Warning",
                "okButtonText": "Ok!",
                "Error": "Error!",
                "TechnicalError": "A technical error occured. Please notify the admin!",
                "returnURL": "returnURL is missing!"
            }
        })
        .translations('ar', {
            "DELETE": {
                "SingleTitle": "هل أنت متأكد?",
                "SingleMessage": "لن تتمكن من استعادة هذا السجل مرة أخرى!",
                "MultipleMessage": "لن تتمكن من استعادة هذه السجلات مرة أخرى!",
                "SingleConfirmButtonText": "نعم، قم بحذفة!",
                "MultipleConfirmButtonText": "نعم، قم بحذفهم!",
                "CancelButtonText": "لا، أبقهم رجاء!",
                "SingleSuccess": "تم حذف السجل بنجاح!",
                "MultipleSuccess": "تم حذف السجلات بنجاح!",
                "NoSelectedRecords": "الرجاء إختيار السجلات المراد حذفها!"
            },
            "SAVE": {
                "Success": "تم حفظ البيانات بنجاح!",
                "CustomOpSuccess": "تم العملية المطلوبة بنجاح!"
            },
            "GENERAL": {
                "Info": "إشعار",
                "Warning": "تنبيه",
                "okButtonText": "تم!",
                "Error": "خطأ!",
                "TechnicalError": "حدث خطأ تقني، الرجاء تنبيه مدير النظام!",
                "returnURL": "returnURL is missing!"
            }
        })
        .preferredLanguage('en')
        .determinePreferredLanguage(function () {
            var preferredLangKey = jQuery('html').attr('lang');
            // some custom logic's going on in here
            return preferredLangKey;
        });

}

angular
    .module('jomizMainModule')
    .config(config);
