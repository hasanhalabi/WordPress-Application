<?php
/**
 * String Utilities
 */
class string_utilities {

	public static function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public static function endsWith($haystack, $needle) {
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}
	
	public static function delete_all_between($beginning, $end, $string) {
	  $beginningPos = strpos($string, $beginning);
	  $endPos = strpos($string, $end);
	  
	  if ($beginningPos === false || $endPos === false) {
		return $string;
	  }

	  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

	  return string_utilities::delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
	}

}
