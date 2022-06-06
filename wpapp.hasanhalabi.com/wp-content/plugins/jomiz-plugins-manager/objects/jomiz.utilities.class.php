<?php
class data_utilities {
	public static function prepare_date_to_save($date) {
		return date_format ( new DateTime ( $date ), 'Y-m-d' );
	}
	public static function prepare_date_to_json($date) {
		return date_format ( new DateTime ( $date ), 'd-m-Y' );
	}
}