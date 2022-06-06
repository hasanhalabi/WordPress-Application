<?php
class jomizHeapData {
	private static function getHeapTableName() {
		global $wpdb;
		return sprintf ( '%1$s%2$s_heap_data', $wpdb->prefix, 'jsys' );
	}
	public static function get_last_user_made_edit($objectType, $id)
	{
		global $wpdb;
		
		$objectsLogTableName = sprintf ( '%1$s%2$s_objects_log', $wpdb->prefix, 'jsys' );
		
		return $wpdb->get_var($wpdb->prepare("
				SELECT
					username
				FROM
					$objectsLogTableName
				WHERE
					object_type = %s
				AND object_id = %d
				ORDER BY
					id DESC 
				", $objectType, $id));
	}
	
	public static function log_object_change($module, $objectType, $id, $is_new, $user_id, $objData) {
		$userInfo = get_userdata ( $user_id );
		
		$dataToSave = array ();
		
		$dataToSave ['module'] = $module;
		$dataToSave ['object_type'] = $objectType;
		$dataToSave ['object_id'] = $id;
		$dataToSave ['object_data'] = json_encode ( $objData );
		$dataToSave ['is_new'] = $is_new;
		$dataToSave ['user_id'] = $user_id;
		$dataToSave ['username'] = $userInfo->display_name;
		
		$dataFormats = array (
				'%s',
				'%d',
				'%s',
				'%d',
				'%d',
				'%s' 
		);
		
		global $wpdb;
		
		$objectsLogTableName = sprintf ( '%1$s%2$s_objects_log', $wpdb->prefix, 'jsys' );
		
		$wpdb->insert ( $objectsLogTableName, $dataToSave, $dataFormats );
	}
	public static function getObject($module, $object_type, $object_id) {
		global $wpdb;
		
		$dataTableName = self::getHeapTableName ();
		
		$dbObject = $wpdb->get_row ( $wpdb->prepare ( "SELECT * FROM $dataTableName WHERE module = %s AND object_type = %s AND id = %d ", $module, $object_type , $object_id) );
		
		return json_decode ( $dbObject->object_data );
	}
	public static function getSettingsObject($module, $object_type) {
		global $wpdb;
		
		$dataTableName = self::getHeapTableName ();
		
		$dbObject = $wpdb->get_row ( $wpdb->prepare ( "SELECT * FROM $dataTableName WHERE module = %s AND object_type = %s", $module, $object_type ) );
		
		return json_decode ( $dbObject->object_data );
	}
	public static function listObjects($module, $object_type, $operationData) {
		global $wpdb;
		
		$dataTableName = self::getHeapTableName ();
		
		// START DONT TOUCH
		$total_count = $wpdb->get_var ( $wpdb->prepare ( "SELECT COUNT(id) FROM $dataTableName WHERE module = %s AND object_type = %s", $module, $object_type ) );
		
		$data = new stdClass ();
		
		$offset = ($operationData->pageToFetch - 1) * $operationData->pageSize;
		$limit = $operationData->pageSize;
		
		// Where
		$where = array ();
		$whereValues = array ();
		
		$where [] = "object_type = %s";
		$whereValues [] = $object_type;
		
		if (! empty ( $operationData->filter->name )) {
			$where [] = "name like %s";
			$whereValues [] = sprintf ( '%%%1$s%%', $operationData->filter->name );
		}
		
		$where = implode ( ' and ', $where );
		
		if (! empty ( $where )) {
			$where = ' where ' . $where;
		}
		$queryParams = array_merge ( $whereValues, array (
				$offset,
				$limit 
		) );
		
		// END DONT TOUCH
		
		$records = $wpdb->get_results ( $wpdb->prepare ( "
				SELECT *
				FROM $dataTableName
				$where
				limit %d, %d
				", $queryParams ) );
		
		// Data List
		$data->objectsList = array ();
		
		foreach ( $records as $record ) {
			$data->objectsList [] = json_decode ( $record->object_data );
		}
		
		// Pagination + DONT TOUCH
		$data->pagingInfo = new stdClass ();
		
		$data->pagingInfo->currentPage = $operationData->page_to_fetch;
		$data->pagingInfo->totalRecords = $total_count;
		$data->pagingInfo->totalPages = (($operationData->pageSize == 0 || ($total_count / $operationData->pageSize) < 1) ? 1 : $total_count / $operationData->pageSize);
		
		// Filter Lookups
		$data->formData = new stdClass ();
		
		return $data;
	}
	public static function saveSettingsObject($module, $object_type, $objData, $prefix = "", $leading_zeros = 5) {
		// Determine if it is a new object or not
		$is_new = ($objData->id < 0);
		
		global $wpdb;
		
		$dataTableName = self::getHeapTableName ();
		
		$wpdb->query ( $wpdb->prepare ( "DELETE FROM $dataTableName WHERE module = %s AND object_type = %s", $module, $object_type ) );
		
		$insert_data = array ();
		
		$insert_data ['object_type'] = $object_type;
		$insert_data ['module'] = $module;
		// Dont Touch
		$insert_data ['author_id'] = get_current_user_id ();
		$insert_data ['created'] = date ( "Y-m-d H:i:s" );
		$insert_data ['modified'] = $insert_data ['created'];
		$insert_data ['locked'] = 0;
		
		$wpdb->insert ( $dataTableName, $insert_data );
		
		$objData->id = $wpdb->insert_id;
		$objData->recordOwner = get_current_user_id ();
		$objData->recordCreatedOn = date ( "Y-m-d H:i:s" );
		$objData->recordModifiedOn = $objData->recordCreatedOn;
		
		$userInfo = get_userdata ( $objData->recordOwner );
		
		if ($userInfo !== FALSE) {
			$objData->recordOwner = ( object ) array (
					"id" => $objData->recordOwner,
					"display_name" => $userInfo->display_name 
			);
		}
		
		if (empty ( $objData->object_number )) {
			$objData->object_number = self::generateObjectFileNumberOfHeap ( $module, $object_type, $objData->id, $prefix, $leading_zeros );
		}
		
		$json_data = json_encode ( $objData );
		$update_data = array ();
		$update_data ['object_data'] = $json_data;
		$update_data ['object_number'] = $objData->object_number;
		$update_data ['modified'] = date ( "Y-m-d H:i:s" );
		$objData->recordModifiedOn = date ( "Y-m-d H:i:s" );
		$wpdb->update ( $dataTableName, $update_data, array (
				'id' => $objData->id 
		) );
		
		// Add Object To Log
		// Dont Touch
		self::log_object_change ( $module, $object_type, $objData->id, $is_new, get_current_user_id (), $objData );
		return $objData->id;
	}
	public static function saveObject($module, $object_type, $objData, $prefix = "", $leading_zeros = 5) {
		// Determine if it is a new object or not
		$is_new = ($objData->id < 0);
		
		global $wpdb;
		
		$dataTableName = self::getHeapTableName ();
		
		if ($is_new) {
			
			$insert_data = array ();
			
			$insert_data ['object_type'] = $object_type;
			$insert_data ['module'] = $module;
			// Dont Touch
			$insert_data ['author_id'] = get_current_user_id ();
			$insert_data ['created'] = date ( "Y-m-d H:i:s" );
			$insert_data ['modified'] = $insert_data ['created'];
			$insert_data ['locked'] = 0;
			
			$wpdb->insert ( $dataTableName, $insert_data );
			
			$objData->id = $wpdb->insert_id;
			$objData->recordOwner = get_current_user_id ();
			$objData->recordCreatedOn = date ( "Y-m-d H:i:s" );
			$objData->recordModifiedOn = $objData->recordCreatedOn;
			
			$userInfo = get_userdata ( $objData->recordOwner );
			
			if ($userInfo !== FALSE) {
				$objData->recordOwner = ( object ) array (
						"id" => $objData->recordOwner,
						"display_name" => $userInfo->display_name 
				);
			}
		}
		
		if (empty ( $objData->object_number )) {
			$objData->object_number = self::generateObjectFileNumberOfHeap ( $module, $object_type, $objData->id, $prefix, $leading_zeros );
		}
		
		$json_data = json_encode ( $objData );
		$update_data = array ();
		$update_data ['object_data'] = $json_data;
		$update_data ['object_number'] = $objData->object_number;
		$update_data ['modified'] = date ( "Y-m-d H:i:s" );
		$objData->recordModifiedOn = date ( "Y-m-d H:i:s" );
		$wpdb->update ( $dataTableName, $update_data, array (
				'id' => $objData->id 
		) );
		
		// Add Object To Log
		// Dont Touch
		self::log_object_change ( $module, $object_type, $objData->id, $is_new, get_current_user_id (), $objData );
		return $objData->id;
	}
	public static function deleteObjects($module, $object_type, $object_ids) {
		if (! is_array ( $object_ids )) {
			$object_ids = array (
					$object_ids 
			);
		}
		
		if (sizeof ( $object_ids ) == 0) {
			return false;
		}
		
		global $wpdb;
		
		$dataTableName = self::getHeapTableName ();
		$idsCount = count ( $object_ids );
		$idsPlaceholders = array_fill ( 0, $idsCount, '%d' );
		$idsPlaceholder = implode ( ', ', $idsPlaceholders );
		
		$object_ids [] = $object_type;
		
		$query = "DELETE FROM $object_ids WHERE id in ($idsPlaceholder) AND locked = 0 AND object_type = %s";
		
		$result = $wpdb->query ( $wpdb->prepare ( $query, $object_ids ) );
		
		if ($result === FALSE) {
			return FALSE;
		}
		
		return TRUE;
	}
	public static function generateObjectFileNumberOfHeap($module, $object_type, $object_id, $prefix = "", $leading_zeros = 5) {
		$dataTableName = self::getHeapTableName ();
		global $wpdb;
		
		$fileNumberFormat = $prefix . '%1$0' . $leading_zeros . 'd';
		
		$count = $wpdb->get_var ( $wpdb->prepare ( "SELECT count(id) from $dataTableName where module = %s AND object_type = %s", $module, $object_type ) );
		$fileNumber = sprintf ( $fileNumberFormat, $count );
		
		$isExists = $wpdb->get_var ( $wpdb->prepare ( "SELECT count(id) from $dataTableName WHERE module = %s AND  object_type = %s AND  object_number = %s", $module, $object_type, $fileNumber ) );
		
		while ( $isExists > 0 ) {
			$count ++;
			$fileNumber = sprintf ( $fileNumberFormat, $count );
			
			$isExists = $wpdb->get_var ( $wpdb->prepare ( "SELECT count(id) from $dataTableName WHERE module = %s AND object_type = %s AND  object_number = %s", $module, $object_type, $fileNumber ) );
		}
		
		$wpdb->update ( $dataTableName, array (
				'object_number' => $fileNumber 
		), array (
				"id" => $object_id,
				'object_type' => $object_type 
		) );
		
		return $fileNumber;
	}
}