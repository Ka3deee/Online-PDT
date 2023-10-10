<?php
session_start(); 
include_once('../path.php');
include(ROOT_PATH . '/databases/functions.php');

$users = 'users';
$allUsers = selectAll($users, $conditions = ['active' => 1]);

if (isset($_POST['create_user'])) {
    unset($_POST['create_user'], $_POST['id']);

    $_POST['password'] = md5($_POST['password']);
    $_POST['active'] = 1;
    $user = create($users, $_POST);
    $_SESSION['message'] = 'New user has been created!';
    $_SESSION['type'] = 'create';
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
        unset($_SESSION['employee_no']);
        echo "User not found";
    }
}

if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    unset($_POST['update_user'], $_POST['id']);

    $_POST['password'] = md5($_POST['password']);
    $user = update($users, $id, $_POST);
    $_SESSION['message'] = 'User account updated successfully!';
    $_SESSION['type'] = 'create';
}

if (isset($_GET['delete_id'])) {
    $user = delete($users, $_GET['delete_id']);
    $_SESSION['message'] = 'You have successfully deleted a user';
    $_SESSION['type'] = 'delete';
    header('location: user_maintenance.php');
    exit();
}

?>