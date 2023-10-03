<?php
if(isset($_POST['trf'])){

    $trf = $_POST['trf'];

    $host = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/onlinepdt/";


    echo json_encode(array('success'=>1,'host'=>$host, 'trf'=> $trf));

}