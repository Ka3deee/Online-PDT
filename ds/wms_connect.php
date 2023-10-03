<?php
// session_start();
include('server_connect.php');

$username = $_SESSION['dsw_user'];
$password = $_SESSION['dsw_pass'];

$query = 'SELECT a.user_type,a.user_name,a.user_pickid,a.ee,a.id,b.fn,b.ln as ln FROM user_tbl a LEFT JOIN user_dtl b ON a.ee = b.ee WHERE a.user_name="'. $username .'" AND a.user_pass="'. $password .'"';
// echo $query .'<br>';

$stmt = $conn->prepare($query);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
// echo '<pre>'. var_dump($user) .'</pre>';
// echo '<pre>'. var_dump($_SESSION) .'</pre>';

if (!$user) {
    unset($_SESSION['dsw_user']);
    unset($_SESSION['dsw_pass']);
    echo '<script>document.getElementById("show_errmsg").innerHTML="Login Error: User not Recognized";</script>';
}
else {
    $_SESSION['wms_user_type']      = $user['user_type'];
    $_SESSION['wms_user_name']      = $user['user_name'];
    $_SESSION['wms_status_pickid']  = $user['user_pickid'];
    $_SESSION['wms_user_ee']		= $user['ee'];
    $_SESSION['wms_user_code']      = $user['id'];
    $_SESSION['myArNew']            = $user['id'];
    $_SESSION['wms_status_user']    = $user['fn'];
    $_SESSION['lastname']           = $user['ln'];
    $_SESSION['device_id']          = $_SERVER['REMOTE_ADDR'];
}

?>