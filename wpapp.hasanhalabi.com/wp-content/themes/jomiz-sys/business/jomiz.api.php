<?php

/**
 * JoMiz API Class
 */
class jomiz_api
{
    public static function send_data_to_client($data, $isOk = true, $forceDataAsArray = false)
    {
        header('Content-Type: application/json');
        
        if ($data-> forceDataAsArray == true){
            $forceDataAsArray = true;
        }

        if (is_array($data) && !$forceDataAsArray) {
            //$data = ( object )$data;
        }

        $dataToSend = new stdClass ();
        $dataToSend->{'result'} = $isOk ? 'ok' : 'not ok!';
        $dataToSend->{'theData'} = $data;
        echo json_encode($dataToSend);
        exit ();
    }

    public static function process_request($operation, $operationData)
    {


        $objecttype = str_replace('-', '_', $operationData->{'objectType'});

       

        if (isset($operationData->domain)) {
            $objecttype = $operationData->domain . '\\' . $objecttype;
        }

        if (!class_exists($objecttype)) {
            $data = array();
            $data ['error'] = sprintf('Object Type (%1$s) is not defined!', $objecttype);
            jomiz_api::send_data_to_client($data, false);

            return;
        }

        $data = new stdClass ();

        $objectinstance = new $objecttype ();

        switch ($operation) {
            case 'get-object' :
                if (!isset($operationData->id)) {
                    $operationData->id = -1;
                }
                $data = $objectinstance->get_object($operationData->id);
                $data->objData->objectType = $operationData->objectType;
                $data->objData->domain = $operationData->domain;
                try {
                    $username = jomizHeapData::get_last_user_made_edit($operationData->objectType, $operationData->id);
                    //$data->objData->recordModifiedBy = $username;
                } catch (Exception $e) {
                    // Do Nothing Just Don't Stop the process
                }
                break;

            case 'list-objects' :
                $data = $objectinstance->list_objects($operationData);
                $data->selectedObjects = array();
                if (isset ($data->pagingInfo) && ($data->pagingInfo->currentPage == null || !isset ($data->pagingInfo->currentPage))) {
                    $data->pagingInfo->currentPage = $operationData->pageToFetch;
                }
                break;

            case 'save-object' :

                $data = $objectinstance->save_object($operationData);

                $data->objData->objectType = $operationData->objectType;
                $data->objData->domain = $operationData->domain;

                break;

            case 'delete-object' :
                $data = $objectinstance->delete_object($operationData);
                break;

            case 'delete-objects-list' :
                $data = $objectinstance->delete_objects_list($operationData);
                break;

            case 'get-data' :
                $data = $objectinstance->get_data($operationData->{'dataSegment'}, $operationData->{'extraData'});

                break;

            default :
                try {
                    $data = $objectinstance->execute_operation($operation, $operationData);
                } catch (Exception $exception) {
                    $data ['error'] = 'Not Supported Operation!';
                    jomiz_api::send_data_to_client($data, false);

                    return;
                }
        }
        $data-> forceDataAsArray = $operationData -> forceDataAsArray;
        jomiz_api::send_data_to_client($data);
    }
}