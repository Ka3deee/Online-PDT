<?php
    session_start();
    // echo '<pre>'. var_dump($data) .'</pre>';

    if(isset($_REQUEST['change_server'])){
        change_server();
    }

    if(isset($_REQUEST['change_user'])){
        change_user();
    }

    if(isset($_REQUEST['mssr_download'])){
        mssr_download();
    }

    if(isset($_REQUEST['mssr_confirm'])){
        mssr_confirm();
    }

    if(isset($_REQUEST['trf_download'])){
        trf_download();
    }

    if(isset($_REQUEST['trf_confirm'])){
        trf_confirm();
    }

    /*-----------------------------------------------------------------------------------------------------------
    |                                                                                                           |
    |                                     FUNCTIONS GO HERE                                                     |
    |                                                                                                           |
    ------------------------------------------------------------------------------------------------------------*/

    function getDeviceHostName(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $str = preg_split('/\(/', $user_agent)[1];
        $str = preg_split('/\)/', $str)[0];
        $str = preg_split('/;/', $str)[2];
        return trim($str);
    }

    function change_server($location = ''){
        unset($_SESSION['dss_ip']);
        unset($_SESSION['srname']);
        unset($_SESSION['dss_user']);
        unset($_SESSION['dss_pass']);
        unset_wms_user();
        redirect(!empty($location) ? $location : 'server_login.php');
    }

    function change_user($location = ''){
        unset_wms_user();
        redirect(!empty($location) ? $location : 'wms_login.php');
    }

    function unset_wms_user(){
        unset($_SESSION['dsw_user']);
        unset($_SESSION['dsw_pass']);
        unset($_SESSION['wms_user_type']);
        unset($_SESSION['wms_user_log']);
        unset($_SESSION['wms_status_pickid']);
        unset($_SESSION['wms_user_ee']);
        unset($_SESSION['wms_user_code']);
        unset($_SESSION['myArNew']);
        unset($_SESSION['wms_status_user']);
        unset($_SESSION['lastname']);
        unset($_SESSION['device_id']);
    }
    
    function redirect($location){
        header("Location: ". $location);
    }

    function isLoggedin($location = ''){
        $dss_ip   = $_SESSION['dss_ip'];
        $dss_user = $_SESSION['dss_user'];
        $dss_pass = $_SESSION['dss_pass'];
        $dsw_user = $_SESSION['dsw_user'];
        $dsw_pass = $_SESSION['dsw_pass'];

        if (empty($dss_ip) || empty($dss_user) || empty($dsw_user) || empty($dsw_pass)) {
            header("Location: ". (!empty($location) ? $location : '../'));
        }
    }

    /*-----------------------------------------------------------------------------------------------------------
    |                                                                                                           |
    |                                        MSSR RECEIVING                                                     |
    |                                                                                                           |
    ------------------------------------------------------------------------------------------------------------*/

    function mssr_download(){
        require 'server_connect.php';
        $list = json_decode($_POST['mssr_list'], true);
        $id   = date('YmdHis') . preg_replace('/[a-zA-Z]/', '', getDeviceHostName());

        foreach ($list as $key => $value) {
            // CHECK IF mssr_no ALREADY ADDED
            $query = 'SELECT COUNT(1), stat FROM trflist WHERE trfnum = '. $value['mssr_no'];
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $stat = $stmt->fetch(PDO::FETCH_NUM);

            // SKIP IF ALREADY ADDED
            if ($stat[0] != 0) {
                $list[$key]['td2'] = $stat[1] == 5 ? 'Downloaded' : ($stat[1] == -1 ? 'MSSR Not Found' : 'Downloading. . .');
                continue;
            }

            // CHECK IF $id ALREADY ADDED
            $query = 'SELECT COUNT(1) FROM pdt_info WHERE id = '. $id;
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_NUM)[0];
            
            // INSERT INTO pdt_info
            if ($count == 0) {
                $query = 'INSERT INTO pdt_info(id,status,userid,pdt_id) VALUES("'. $id .'",0,'.$_SESSION['wms_user_code'].',"'. getDeviceHostName().'")';
                $stmt = $conn->prepare($query);
                $stmt->execute();
            }

            // INSERT INTO trflist
            $query = 'INSERT INTO trflist(id,trfnum) VALUES("'. $id .'", '.$value['mssr_no'].')';
            $stmt = $conn->prepare($query);
            $stmt->execute();
            
            $list[$key]['td2'] = 'Downloading. . .';
        }

        echo json_encode($list);
    }

    function mssr_confirm(){
        require 'server_connect.php';
        
        $mssr_ref = $_POST['mssr_ref'];
        $confirm  = $_POST['confirm'];
        
        $query = 'SELECT COUNT(ar_ref) - (SELECT COUNT(rec_qty) FROM msr_det_tbl WHERE msr_ts='. $mssr_ref .' AND rec_qty > 0) AS cnt, SUM(qty) - SUM(rec_qty) AS qty FROM msr_det_tbl WHERE msr_ts='. $mssr_ref;
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_NUM)[0];
        
        if ($data[0] > 0 && $confirm == 0) {
            echo json_encode(array('status' => 'error', 'message' => 'Not all sku served. Do you want to proceed?'));
        }
        else if ($data[1] > 0 && $confirm == 0) {
            echo json_encode(array('status' => 'error', 'message' => 'Not all quantity served. Do you want to proceed?'));
        }
        else {
            // UPDATE STATUS
            $query = 'UPDATE msr_sum_tbl SET msr_stat = 2, rec_end = NOW(), msr_user="'. $_SESSION['wms_status_user'] .'" WHERE msr_ts = "'. $mssr_ref .'"';
            $stmt = $conn->prepare($query);
            $stmt->execute();
            echo json_encode(array('status' => 'success', 'message' => 'Successfully completed'));
        }
    }

    /*-----------------------------------------------------------------------------------------------------------
    |                                                                                                           |
    |                                         TRF LINE INSPECTION                                               |
    |                                                                                                           |
    ------------------------------------------------------------------------------------------------------------*/

    function trf_download(){
        require 'server_connect.php';
        $list = json_decode($_POST['trf_list'], true);
        $id   = date('YmdHis') . preg_replace('/[a-zA-Z]/', '', getDeviceHostName());

        foreach ($list as $key => $value) {
            // CHECK IF trf_no ALREADY ADDED
            $query = 'SELECT COUNT(1), stat FROM trf_tbl WHERE trf_ref = '. $value['trf_no'];
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $stat = $stmt->fetch(PDO::FETCH_NUM);

            // SKIP IF ALREADY ADDED
            if ($stat[0] != 0) {
                $list[$key]['td2'] = $stat[1] == 5 ? 'Downloaded' : ($stat[1] == -1 ? 'TRF Not Found' : 'Downloading. . .');
                continue;
            }

            // CHECK IF $id ALREADY ADDED
            $query = 'SELECT COUNT(1) FROM pdt_info WHERE id = '. $id;
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_NUM)[0];
            
            // INSERT INTO pdt_info
            if ($count == 0) {
                $query = 'INSERT INTO pdt_info(id,status,userid,pdt_id) VALUES("'. $id .'",0,'.$_SESSION['wms_user_code'].',"'. getDeviceHostName().'")';
                $stmt = $conn->prepare($query);
                $stmt->execute();
            }

            // INSERT INTO trflist
            $query = 'INSERT INTO trf_tbl(id,trf_ref) VALUES("'. $id .'", '.$value['trf_no'].')';
            $stmt = $conn->prepare($query);
            $stmt->execute();
            
            $list[$key]['td2'] = 'Downloading. . .';
        }

        echo json_encode($list);
    }

    function trf_confirm(){
        require 'server_connect.php';
        
        $trf_ref = $_POST['trf_ref'];
        $confirm = $_POST['confirm'];
        
        $query = 'SELECT COUNT(sku) - (SELECT COUNT(rcv_qty) FROM trf_det_tbl WHERE trf_ref='. $trf_ref .' AND rcv_qty > 0) AS cnt, SUM(qty) - SUM(rcv_qty) AS qty FROM trf_det_tbl WHERE trf_ref='. $trf_ref;
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_NUM)[0];
        
        if ($data[1] > 0 && $confirm == 0) {
            echo json_encode(array('status' => 'error', 'message' => 'Not all sku served. Do you want to proceed?'));
        }
        else if ($data[0] > 0 && $confirm == 0) {
            echo json_encode(array('status' => 'error', 'message' => 'Not all quantity served. Do you want to proceed?'));
        }
        else {
            // UPDATE STATUS
            $query = 'UPDATE trf_sum_tbl SET trf_stat = 2, rcv_date = NOW(), rcv_user="'. $_SESSION['wms_status_user'] .'" WHERE trf_ref = "'. $trf_ref .'"';
            $stmt = $conn->prepare($query);
            $stmt->execute();
            echo json_encode(array('status' => 'success', 'message' => 'Successfully completed'));
        }
    }

?>