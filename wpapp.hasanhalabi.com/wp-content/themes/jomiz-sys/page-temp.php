<?php
/*
     * Template Name: fuvm
     */
/*
$abstract_class_path = 'D:\\wamp\\www\\ebal-management' . '\\wp-content\\plugins\\jomiz-plugins-manager\\files-to-include.php';
// Include The Required Files
include_once ($abstract_class_path);
include_once ('plugin-content/files-to-include.php');*/


$lookingFor = 'student';
$sponser_type = 0;
$sponser_id = -1;



// if $sponser_type  == 0 means member
// if $sponser_type  == 1 means متبرع خارجي
if (isset($_GET['looking'])) {
    $lookingFor = $_GET['looking'];
}

if (isset($_GET['stype'])) {
    $sponser_type = $_GET['stype'];
}

if (isset($_GET['sid'])) {
    $sponser_id = $_GET['sid'];
}

if (isset($_GET['mem'])) {
    $memberId = $_GET['mem'];
}

$data = (object) array('Message' => 'not supported sType');
if ($lookingFor == 'student') {
    $data = utilities::getStudentsOfSponser($sponser_type, $sponser_id);
}
if ($lookingFor == 'orphan') {
    $data = utilities::getOrphanOfSponser($sponser_type, $sponser_id);
}

if ($lookingFor == 'fmily') {
    $data = utilities::getFamilyOfSponser($sponser_type, $sponser_id);
}

if ($lookingFor == 'fee') {
    $data = utilities::getAnnualFeeOfMember($memberId);
}
header('Content-Type: application/json');
echo json_encode($data);
