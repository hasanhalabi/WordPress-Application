<?php

/**
 * Created by PhpStorm.
 * User: hasan
 * Date: 2017-02-14
 * Time: 17:53
 */

namespace jomizSys;

class utilities { 

    static public function get_objects_users_info($objData) {
        $created_by_name = '-';
        $modified_by_name = '-';

        if (isset($objData->created_by) && $objData->created_by > 0) {
            $user = get_userdata($objData->created_by);
            $created_by_name = $user->display_name;
        }

        if (isset($objData->modified_by) && $objData->modified_by > 0) {
            $user = get_userdata($objData->modified_by);
            $modified_by_name = $user->display_name;
        }

        return array(
            created_by_name => $created_by_name,
            modified_by_name => $modified_by_name
        );
    }

    static public function strip_object_to_save($objData, $insert_or_update, array $tableColumns) {
        $objArray = (array) $objData;
        $objFinalData = array();

        foreach ($tableColumns as $column) {
            $objFinalData[$column] = $objArray[$column];
        }

        if ($insert_or_update == 1) {
            $objFinalData['created_by'] = $objArray['created_by'];
            $objFinalData['created_on'] = $objArray['created_on'];
        }

        $objFinalData['modified_by'] = $objArray['modified_by'];
        $objFinalData['modified_on'] = $objArray['modified_on'];
		
		if ($insert_or_update == 2) {
			unset($objFinalData['created_by']);
			unset($objFinalData['created_on']);
			unset($objFinalData['modified_by']);
			unset($objFinalData['modified_on']);
			
		}


        return $objFinalData;
    }

	
	public static function create_seq_number($args) {
		
		if (is_array($args)){
			$args= (object)$args;
		}
		$table_name = $args->table_name;
		$column_name = $args->column_name;
		$prefix = $args->prefix;
		$id = $args->id;
		
        global $wpdb;
		log_to_file("hi");

        $dbObject = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $id");
		log_to_file($dbObject);

        if (empty($column_name)) {
			log_to_file("hi 1");
            return $column_name;
        }

        $query = "SELECT COUNT(id) FROM $table_name";
		log_to_file($query);

        $row_count = $wpdb->get_var($query);
		$digits = 4;
		
		if(isset($args->digits)){
			$digits = $args->digits;
		}
		$current_year = '';
		if(isset($args->use_current_year) && $args->use_current_year === true){
			$current_year = date("Y");
		}
		$rowNumberFormat = '%1$s'.$current_year.'%2$0'.$digits.'d';
		log_to_file($rowNumberFormat);
        $rowNumber = sprintf($rowNumberFormat, $prefix, $row_count);
		

        $row_of_same_number = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM $table_name WHERE $column_name = %s", $rowNumber));

        while ($row_of_same_number > 0) {
            $row_count ++;
            $rowNumber = sprintf($rowNumberFormat, $prefix, $row_count);
            $row_of_same_number = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM $table_name WHERE $column_name = %s", $rowNumber));
        }

        $rowNumber = sprintf($rowNumberFormat, $prefix, $row_count);

        $update_data = array(
            $column_name => $rowNumber
        );

        $wpdb->update($table_name, $update_data, array(
            'id' => $id
        ));

        return $rowNumber;
    }
}
