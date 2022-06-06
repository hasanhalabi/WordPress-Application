<?php
/*
 * Template Name: JoMiz API
 */
$data = array ();
$forceDataAsArray = FALSE;

if (! sizeof ( $_POST )) {
	$data ['error'] = 'This is not a valid request!';
	jomiz_api::send_data_to_client ( $data, FALSE );
}

if (! isset ( $_POST ['requestData'] )) {
	$data ['error'] = 'No Request Data!';
	jomiz_api::send_data_to_client ( $data, FALSE );
} // $objToSave = (array)json_decode(str_replace('\\', '', $_POST["objToSave"]));
  // var_dump($_POST["requestData"]); die;
$requestData = str_replace ( '\\', '', $_POST ["requestData"] );
$requestData = json_decode ( $requestData );
// var_dump($requestData); die;

if (! isset ( $requestData->{'operation'} )) {
	$data ['error'] = 'No Operation!';
	jomiz_api::send_data_to_client ( $data, FALSE );
}

if (! isset ( $requestData->{'dataToProcess'} )) {
	$data ['error'] = 'No Data To Process!';
	jomiz_api::send_data_to_client ( $data, FALSE );
}

$dataToProcess = $requestData->{'dataToProcess'};

jomiz_api::process_request ( $requestData->{'operation'}, $dataToProcess );
