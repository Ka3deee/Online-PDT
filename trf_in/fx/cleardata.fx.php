<?php

require('../classes/DatabaseClass.php');


$obj = new DatabaseClass();
if($obj->clearData()){
    echo json_encode(array('success' => 1));
}else{
    echo json_encode(array('success' => 0));
}