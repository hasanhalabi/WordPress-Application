<?php
class jomizSysUntilities {
	static public function get_report_url($plugin_code, $report_code) {
		//return sprintf ( '%1$s/%2$s/reports/%3$s', home_url (), $plugin_code, $report_code );
		return sprintf ( '%1$s/reports/%2$s', home_url (),  $report_code );
	}
}