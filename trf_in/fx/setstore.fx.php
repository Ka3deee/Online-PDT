<?php
require('../Classes/DatabaseClass.php');

$str = $_REQUEST['str'];

$obj = new DatabaseClass();
$result = $obj->getStore($str);


if($result['store_code'] == ""){
    echo json_encode(array('success' => 0));
}else{
    session_start();
    $_SESSION['strcode'] = $result['store_code'];
    $_SESSION['strdesc'] = $result['store_desc'];
    echo json_encode(array('success' => 1,'str'=>$result['store_code']));
}