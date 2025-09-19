<?php 
    include_once('../path.php');
    include(ROOT_PATH . '/controllers/get_users.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDT Application : Transfer Releasing</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.ico"/>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/animate.css">
</head>

<body>
    <div class="display-center pos-rel">
        <header class="display-center"> 
            <div class="display-center">
                <img src="../assets/images/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font mb">TRF Releasing : User Maintenance</h4>
            <h5 class="tc semi-visible">v2.0.0</h5>
            <br>
            <br>
        </header>
        <main class="display-center">
            <div id="loader-wrapper" class="mb w">
                <div class="loader"></div><strong>Deleting user... Please wait...</strong>
            </div>
            <div class="mb w">
                <?php include(ROOT_PATH . '/controllers/helpers/user_msg.php'); ?>
            </div>
            <h3 class="tc mb">User List</h3>
            <div class="tbl-wrap mb w">
                <table class="w p-bot">
                    <thead class="thead">
                        <th class="thead-des bold">SN</th>
                        <th class="thead-des bold">EE No.</th>
                        <th class="thead-des bold">Full Name</th>
                        <th class="thead-des" colspan="2"><div class="flex a-center j-between bold">Action<button type="button" onclick="createUserModal()" class="btn-2 btn-md primary flex a-center"><ion-icon name="person-add"></ion-icon>&nbsp;Add</button></div></th>
                    </thead>
                    <tbody class="tbody">
                    <?php foreach ($allUsers as $key => $user): ?>
                        <tr>
                            <td class="tbody-des"><?php echo $key + 1; ?></td>
                            <td class="tbody-des"><?php echo $user['employee_no']; ?></td>
                            <td class="tbody-des"><?php echo $user['firstname'] . " " . $user['middlename'] . " " . $user['lastname'] ; ?></td>
                            <td class="tbody-des"><button onclick="updateUserModal('<?php echo $user['id']; ?>','<?php echo $user['employee_no']; ?>','<?php echo $user['firstname']; ?>','<?php echo $user['middlename']; ?>','<?php echo $user['lastname']; ?>')" class="btn btn-md primary flex a-center j-center"><ion-icon name="create"></ion-icon>&nbsp;Update</button></td>
                            <td class="tbody-des"><a href="user_maintenance.php?delete_id=<?php echo $user['id']; ?>" class="alert" data-confirm="Are you sure to delete this user account?"><button class="btn btn-md delete flex a-center j-center"><ion-icon name="trash"></ion-icon>&nbsp;Delete</button></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mb w">
                <button onclick="window.location.href='set_user.php'" class="btn btn-lg primary"><ion-icon name="arrow-undo"></ion-icon>&nbsp; Back</button>
            </div>
        </main>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
        <div id="modal" class="pos-abs">
            <form id="insert-user">
                <div class="add-user">
                    <button type="button" onclick="toggleModal()" class="close delete"><ion-icon name="close-outline"></ion-icon></button>
                    <h4 class="mb bold flex a-center j-center"><ion-icon name="person-add"></ion-icon>&nbsp;<span class="bold modal-title" id="modal-title"></span>&nbsp;User</h4>
                    <label for="firstname">Firstname *</label>
                    <input id="id" name="id" type="hidden">
                    <input id="firstname" name="firstname" class="textarea mb" type="text">
                    <label for="middlename">Middlename</label>
                    <input id="middlename" name="middlename" class="textarea mb" type="text">
                    <label for="lastname">Lastname *</label>
                    <input id="lastname" name="lastname" class="textarea mb" type="text">
                    <label for="employee_no">EE No. *</label>
                    <input id="employee_no" name="employee_no" class="textarea mb" type="text" maxlength="5" size="5" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;">
                    <label for="password">Password *</label>
                    <input id="password" name="password" class="textarea mb" type="password">
                    <div id="loader-modal" class="mb w">
                        <div class="loader"></div><strong>Processing... Please wait...</strong>
                    </div>
                    <button type="submit" onclick="document.getElementById('modal-title').textContent == 'Add' ? CreateUser() : UpdateUser();" class="btn btn-md primary mb flex a-center j-center"><ion-icon name="checkmark"></ion-icon>&nbsp;<span class="modal-title"></span></button>
                    <button type="button" onclick="toggleModal()" class="btn btn-md delete mb flex a-center j-center"><ion-icon name="close"></ion-icon>&nbsp;Cancel</button>
                </div>
            </form>
            <div id="response"></div>
        </div>
    </div>
    <br>
    <br>
</body>

<script>
 
</script>
<script src="../assets/js/validate.js"></script>
<script src="../assets/js/animate.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>