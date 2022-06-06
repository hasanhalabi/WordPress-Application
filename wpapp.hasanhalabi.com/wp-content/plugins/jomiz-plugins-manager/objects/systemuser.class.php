<?php
/**
 * Objet Type: system user
 */
class systemuser extends jomiz_api_object_type {
	public function get_object($id) {
		$formData = $this->getFormData ( $id );
		$objData = new stdClass ();
		if ($id == - 1) {
			$objData->id = - 1;
			$objData->objectType = 'systemuser';
			$objData->first_name = "";
			$objData->last_name = "";
			$objData->user_name = "";
			$objData->email = "";
			$objData->is_admin = false;
			$objData->jobtitle = "";
			$objData->user_pass = "";
			$objData->Confirm_pass = "";
			$objData->is_disabled = false;
		} else {
			$objData->id = $id;
			$objData->objectType = 'systemuser';
			
			$userObj = get_user_by ( 'id', $objData->id );
			$objData->first_name = $userObj->first_name;
			$objData->last_name = $userObj->last_name;
			$objData->user_name = $userObj->user_login;
			$objData->email = $userObj->user_email;
			
			$objData->roles = $userObj->roles;
			$objData->jobtitle = $userObj->nickname;
			$objData->exist = TRUE;
			$objData->is_disabled = false;
			
			$meta_status_value = get_user_meta ( $id, 'login_status', true );
			
			if ($meta_status_value == 'disabled') {
				$objData->is_disabled = true;
			}
			
			if (is_array ( $objData->roles )) {
				foreach ( $objData->roles as $role ) {
					if ($role == 'administrator') {
						$objData->is_admin = true;
					} else {
						$objData->is_admin = false;
					}
				}
			}
		}
		
		$data = new stdClass ();
		$data->objData = $objData;
		$data->formData = $formData;
		return $data;
	}
	public function save_object($objData) {
		global $wpdb;
		// Convert $objData into array
		$dataToSave = json_decode ( json_encode ( $objData ), true );
		// Determine if it is a new object or not
		$is_new = ($dataToSave ['id'] < 0);
		if ($is_new) {
			
			// creat new array to insert
			$InsertUserData = array (
					'first_name' => $dataToSave ['first_name'],
					'last_name' => $dataToSave ['last_name'],
					'user_email' => $dataToSave ['email'],
					'nickname' => $dataToSave ['jobtitle'],
					'user_pass' => $dataToSave ['user_pass'],
					'user_login' => $dataToSave ['user_name'] 
			);
			
			$user_id = wp_insert_user ( $InsertUserData );
			
			$dataToSave ['id'] = $user_id;
		} else {
			// creat updaet array
			
			$UpdateUserData = array (
					'ID' => $dataToSave ['id'],
					'first_name' => $dataToSave ['first_name'],
					'last_name' => $dataToSave ['last_name'],
					'user_email' => $dataToSave ['email'],
					'nickname' => $dataToSave ['jobtitle'],
					'user_pass' => $dataToSave ['user_pass'],
					'user_login' => $dataToSave ['user_name'] 
			);
			
			if ($dataToSave ['user_pass'] !== null) {
				$UpdateUserData ['user_pass'] = $dataToSave ['user_pass'];
			}
			
			$user_id = $dataToSave ['id'];
			$user_id = wp_update_user ( $UpdateUserData );
		}
		
		$meta_value = 'enabled';
		
		if ($dataToSave ['is_disabled'] == true) {
			
			$meta_value = 'disabled';
		}
		
		$user_id = $dataToSave ['id'];
		if ($dataToSave ['is_admin'] == TRUE) {
			
			wp_update_user ( array (
					'ID' => $user_id,
					'role' => 'administrator' 
			) );
		} else {
			
			
			wp_update_user ( array (
					'ID' => $user_id,
					'role' => 'contributor' 
			) );
		}
		
		update_user_meta ( $dataToSave ['id'], 'login_status', $meta_value );
		
		$data = ( object ) array (
				'objData' => new stdClass (),
				'formData' => new stdClass () 
		);
		
		return $data;
	}
	public function delete_object($objData) {
	}
	public function list_objects($operationData) {
		$data = new stdClass ();
		$offset = ($operationData->pageToFetch - 1) * $operationData->pageSize;
		$limit = $operationData->pageSize;
		
		// search
		$concat = "*" . $operationData->filter->Infos . "*";
		
		$args = array (
				'orderby' => 'display_name',
				'order' => 'ASC',
				'search' => $concat,
				'search_columns' => array (
						'display_name' 
				) 
		);
		
		$user_query = new WP_User_Query ( $args );
		
		// Data List
		$data->objectsList = array ();
		
		$system_users = get_users ( $args );
		foreach ( $system_users as $user ) {
			
			$users [] = ( object ) array (
					'id' => $user->ID,
					'username' => $user->user_nicename,
					'jobtitle' => $user->nickname,
					'email' => $user->user_email,
					'last_name' => $user->last_name,
					'first_name' => $user->first_name,
					'display_name' => $user->display_name 
			);
		}
		
		$data->objectsList = $users;
		$data->total_rsult = count ( $system_users );
		// Pagination
		$data->pagingInfo = new stdClass ();
		
		$data->pagingInfo->currentPage = $operationData->page_to_fetch;
		$data->pagingInfo->totalRecords = $data->total_rsult;
		$data->pagingInfo->totalPages = (($operationData->pageSize == 0 || ($data->total_rsult / $operationData->pageSize) < 1) ? 1 : $data->total_rsult / $operationData->pageSize);
		
		return $data;
	}
	public function delete_objects_list($operationData) {
	}
	public function getFormData($id) {
		
		$formData = new stdClass ();
		global $wpdb;
		$formData->id = $id;
		$objUsers = ( object ) array ();
		$blogusers = get_users ( array (
				'search' => '*' 
		) );
		// Array of stdClass objects.
		foreach ( $blogusers as $buser ) {
			$Xnxx [] = ( object ) array (
					'email' => $buser->user_email,
					'username' => $buser->user_login,
					'id' => $buser->ID 
			);
		}
		if ($formData->id == - 1) {
			
			$formData->objUsers = $Xnxx;
		} else {
			$formData->objUsers = array ();
		}
		return $formData;
	}
}
?>