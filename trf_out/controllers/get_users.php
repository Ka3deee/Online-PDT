<?php
session_start(); 
include('database/functions.php');

$users = 'users';
$allUsers = selectAll($users, $conditions = []);

if (isset($_POST['create_user'])) {
    unset($_POST['create_user']);

    $_POST['password'] = md5($_POST['password']);
    $_POST['active'] = 1;
    $request_id = create($users, $_POST);
    $_SESSION['msg'] = 'success';
}

if (isset($_POST['set_user'])) {
    unset($_POST['set_user']);

    $conditions = [
        'employee_no' => $_POST['employee_no'],
        'password' => md5($_POST['password']),
    ];
    
    $user = selectOne($users, $conditions);
    if ($user) {
        $id = $user['id'];
        $firstname = $user['firstname'];
        $middlename = $user['middlename'];
        $lastname = $user['lastname'];
        $employee_no = $user['employee_no'];
        $active = $user['active'];
        $_SESSION['employee_no'] = $user['employee_no'];
        
    } else {
        echo "no result";
        unset($_SESSION['employee_no']);
    }
}

?>