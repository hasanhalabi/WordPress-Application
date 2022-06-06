<?php

/**
 * API Object Type Abstract Class
 */
abstract class jomiz_api_object_type
{
    abstract public function get_object($id);

    abstract public function save_object($objData);

    abstract public function delete_object($objData);

    abstract public function list_objects($operationData);

    abstract public function delete_objects_list($operationData);

    public function execute_operation($operation, $operationData)
    {
    }

    protected function delete_objects($tableName, $ids)
    {
        global $wpdb;

        $idsCount = count($ids);
        $idsPlaceholders = array_fill(0, $idsCount, '%d');
        $idsPlaceholder = implode(', ', $idsPlaceholders);

        $query = "DELETE FROM $tableName WHERE id in ($idsPlaceholder)";

        $result = $wpdb->query($wpdb->prepare($query, $ids));

        if ($result === FALSE) {
            return FALSE;
        }

        return TRUE;
    }
    protected function delete_voucher_objects($tableName,$tableNameTrs, $ids)
    {
        global $wpdb;

        $idsCount = count($ids);
        $idsPlaceholders = array_fill(0, $idsCount, '%d');
        $idsPlaceholder = implode(', ', $idsPlaceholders);
        
        $newIdsQuery  = "SELECT id FROM $tableName WHERE id in ($idsPlaceholder) and (status = 0 OR status = 3)";
        $newIds = $wpdb->get_results($wpdb->prepare($newIdsQuery, $ids));
        
        $newIds = array_map(function ($item){
            return $item->id;
        }, $newIds);
        
        $this->delete_objects_trs($tableNameTrs, $newIds);   
        
        $query = "DELETE FROM $tableName WHERE id in ($idsPlaceholder) and (status = 0 OR status = 3) ";

        $result = $wpdb->query($wpdb->prepare($query, $ids));

        if ($result === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

    protected function delete_objects_trs($tableNameTrs, $ids)
    {
        global $wpdb;

        $idsCount = count($ids);
        $idsPlaceholders = array_fill(0, $idsCount, '%d');
        $idsPlaceholder = implode(', ', $idsPlaceholders);
        

        $query = "DELETE FROM $tableNameTrs WHERE voucher_id in ($idsPlaceholder)";

        $result = $wpdb->query($wpdb->prepare($query, $ids));

        if ($result === FALSE) {
            return FALSE;
        }

        return TRUE;
    }
    
    protected function delete_objects_no_trs($tableName, $ids)
    {
        global $wpdb;

        $idsCount = count($ids);
        $idsPlaceholders = array_fill(0, $idsCount, '%d');
        $idsPlaceholder = implode(', ', $idsPlaceholders);
         
        $query = "DELETE FROM $tableName WHERE id in ($idsPlaceholder) and (status = 0 OR status = 3) ";

        $result = $wpdb->query($wpdb->prepare($query, $ids));

        if ($result === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

    protected function delete_vendor_stuff($tableName, $ids)
    {
        
        global $wpdb;

        $idsCount = count($ids);
        $idsPlaceholders = array_fill(0, $idsCount, '%d');
        $idsPlaceholder = implode(', ', $idsPlaceholders);

        $query = "DELETE FROM $tableName WHERE vendor_id in ($idsPlaceholder)";

        $result = $wpdb->query($wpdb->prepare($query, $ids));

        if ($result === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

    static public function log_object_change($objectType, $id, $is_new, $user_id, $objData, $module = null)
    {
        $userInfo = get_userdata($user_id);

        $dataToSave = array();

        $dataToSave['object_type'] = $objectType;
        $dataToSave['object_id'] = $id;
        $dataToSave['object_data'] = json_encode($objData);
        $dataToSave['is_new'] = $is_new;
        $dataToSave['user_id'] = $user_id;
        $dataToSave['username'] = $userInfo->display_name;
        $dataToSave['module'] = $module;

        global $wpdb;

        $objectsLogTableName = sprintf('%1$s%2$s_objects_log', $wpdb->prefix, 'jsys');

        $wpdb->insert($objectsLogTableName, $dataToSave);
    }

}

?>