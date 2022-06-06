jQuery(function () {
	style_validation();
	//add_fixed_page_heading_effects();
	forms_enableChosenPlugin(false);
	forms_enableICheckPlugin();
	forms_enableDatePlugins();
	fileboxEffect();
});

var fileboxEffect = function () {
	jQuery('.file-box').each(function () {
		animationHover(this, 'pulse');
	});
}

var style_validation = function () {
	jQuery('[required]').closest('.form-group').addClass('form-group-required');
}

/*var add_fixed_page_heading_effects = function () {
	calculate_page_heading();
	jQuery(window).scroll(function () {
		calculate_page_heading();
	});
};

var calculate_page_heading = function () {
	if (jQuery('.page-heading').hasClass('affix')) {
		var headingWidth = jQuery(window).width() - jQuery('#jomiz-sys-main-menu').width() - 20;
		jQuery('.page-heading').width(headingWidth + "px");
	}
};*/

var create_jstreeFontAwesome = function () {
	jQuery('.jstree-font-awesome').jstree({
		'core': {
			'check_callback': true
		},
		'plugins': ['types', 'dnd'],
		'types': {
			'default': {
				'icon': 'fa fa-folder'
			},
			'html': {
				'icon': 'fa fa-file-code-o'
			},
			'svg': {
				'icon': 'fa fa-file-picture-o'
			},
			'css': {
				'icon': 'fa fa-file-code-o'
			},
			'img': {
				'icon': 'fa fa-file-image-o'
			},
			'js': {
				'icon': 'fa fa-file-text-o'
			}

		}
	});
};
var forms_enableChosenPlugin = function (enableChosenDirective) {
	jQuery(".chosen-select").each(function () {
		var chosenAttr = $(this).attr('chosen');

		if (enableChosenDirective && typeof chosenAttr !== typeof undefined && chosenAttr !== false) {
			jQuery(this).chosen({
				width: "95%"
			});
		} else if (!enableChosenDirective && typeof chosenAttr === typeof undefined) {
			jQuery(this).chosen({
				width: "95%"
			});
		}
	});
};
var forms_enableICheckPlugin = function () {
	jQuery('.i-checks').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
	});
};
var forms_enableDatePlugins = function (_minDate, _maxDate, _format) {
	var currentUYear = (new Date()).getFullYear();

	jQuery('.input-group.date').each(function () {
		var minDate = _minDate || jQuery(this).find('input').data("minDate");
		var maxDate = _maxDate || jQuery(this).find('input').data("maxDate");
		var format = _format || "dd-mm-yyyy";

		if (minDate == undefined) {
			minDate = "01-01-" + currentUYear;
		}

		if (maxDate == undefined) {
			maxDate = "31-12-" + currentUYear;
		}

		jQuery(this).datepicker('remove');
		jQuery(this).datepicker({
			startView: 0,
			todayBtn: "linked",
			keyboardNavigation: true,
			forceParse: false,
			autoclose: true,
			format: format
		});
	});


	jQuery('.input-daterange').datepicker({
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true
	});
};
var forms_enableDropzonePlugin = function () {
	Dropzone.options.myAwesomeDropzone = {

		autoProcessQueue: false,
		uploadMultiple: true,
		parallelUploads: 100,
		maxFiles: 100,

		// Dropzone settings
		init: function () {
			var myDropzone = this;

			this.element.querySelector("button[type=submit]").addEventListener("click", function (e) {
				e.preventDefault();
				e.stopPropagation();
				myDropzone.processQueue();
			});
			this.on("sendingmultiple", function () {});
			this.on("successmultiple", function (files, response) {});
			this.on("errormultiple", function (files, response) {});
		}

	}
};
