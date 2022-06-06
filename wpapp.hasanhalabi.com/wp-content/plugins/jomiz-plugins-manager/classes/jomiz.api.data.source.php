<?php
/**
 * API Data Source Abstract Class
 */
abstract class jomiz_api_data_source {
	public function get_data($dataSegment, $extraData) {
		$dataSourceInfo = array (
				$this,
				$dataSegment 
		);
		
		if (is_callable ( $dataSourceInfo )) {
			return ( object ) array (
					'result' => 'ok',
					'data' => $this->$dataSegment ( $extraData ) 
			);
		} else {
			return ( object ) array (
					'result' => 'not_ok',
					'message' => "Not Supported Data Segment ($dataSegment)" 
			);
		}
	}
}