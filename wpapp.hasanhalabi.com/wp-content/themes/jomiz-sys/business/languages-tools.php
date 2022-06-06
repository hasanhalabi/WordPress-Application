<?php
	class languagesTools {
		static public function get_current_language_text($languagesOptions) {
			$currentLang = get_bloginfo('language');
			/*
			 if (!isset($languagesOptions[$currentLang])) {
			 return "## Language Not Suported ##";
			 }*/

			return $languagesOptions[$currentLang];
		}

	}

	function __lang($key, $domain) {
		echo _lang($key, $domain);
	}

	function _lang($key, $domain) {
		if (!isset($domain[$key])) {
			throw new Exception("The key ($key) not exists in the languages domain.", 1);
		}

		return languagesTools::get_current_language_text($domain[$key]);
	}

	function __theme($key) {
		echo _theme($key);
	}

	function _theme($key) {
		$themeDomain = array();

		$themeDomain['chosen'] = array(
			'en-US' => ' chosen-select ',
			'ar' => ' chosen-select chosen-rtl '
		);

		$themeDomain['body'] = array(
			'en-US' => '  ',
			'ar' => ' rtals '
		);

		return _lang($key, $themeDomain);
	}
