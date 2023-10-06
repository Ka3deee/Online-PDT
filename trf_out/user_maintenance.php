<?php include('controllers/get_users.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDT Application : Transfer Releasing</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/favicon.ico"/>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animate.css">
</head>

<body>
    <div class="display-center pos-rel">
        <div class="display-center"> 
            <div class="display-center">
                <img src="../resources/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font mb">TRF Releasing : User Maintenance</h4>
            <h5 class="tc semi-visible">v1.0.0</h5>
            <br>
            <br>
        </div>
        <div class="display-center">
            <?php if (isset($_SESSION['msg'])) { ?>
                <div class="msg success mb">User created successfully</div>
            <?php } ?>
            <h4 class="tc mb">User List</h4>
            <div class="tbl-wrap mb w">
                <table class="w p-bot">
                    <thead class="thead">
                        <th class="thead-des">SN</th>
                        <th class="thead-des">EE No.</th>
                        <th class="thead-des">Full Name</th>
                        <th class="thead-des" colspan="2"><div class="flex-between">Action<button type="button" onclick="toggleModal()" class="btn-2 btn-md primary">Add</button></div></th>
                    </thead>
                    <tbody class="tbody">
                    <?php foreach ($allUsers as $key => $user): ?>
                        <tr>
                            <td class="tbody-des"><?php echo $key + 1; ?></td>
                            <td class="tbody-des"><?php echo $user['employee_no']; ?></td>
                            <td class="tbody-des"><?php echo $user['firstname'] . " " . $user['middlename'] . " " . $user['lastname'] ; ?></td>
                            <td class="tbody-des"><button class="btn btn-md primary">Update</button></td>
                            <td class="tbody-des"><button class="btn btn-md delete">Delete</button></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mb w">
                <button onclick="window.location.href='set_user.php'" class="btn btn-lg primary">Back</button>
            </div>
        </div>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
        <div id="modal" class="pos-abs">
            <form id="create-user">
                <div></div>
                <div class="add-user">
                    <button type="button" onclick="toggleModal()" class="close"><ion-icon name="close-outline"></ion-icon></button>
                    <h4 class="tc mb bold">Add New User</h4>
                    <label for="firstname">Firstname *</label>
                    <input id="firstname" name="firstname" class="mb" type="text">
                    <label for="middlename">Middlename</label>
                    <input id="middlename" name="middlename" class="mb" type="text">
                    <label for="lastname">Lastname *</label>
                    <input id="lastname" name="lastname" class="mb" type="text">
                    <label for="employee_no">EE No. *</label>
                    <input id="employee_no" name="employee_no" class="mb" type="text">
                    <label for="password">Password *</label>
                    <input id="password" name="password" class="mb" type="password">
                    <label style="visibility: hidden;">space</label>
                    <button type="submit" onclick="CreateUser()" class="btn btn-md primary mb">Add</button>
                    <button type="button" onclick="toggleModal()" class="btn btn-md primary mb">Cancel</button>
                </div>
            </form>
            <div id="response"></div>
        </div>
    </div>
    <br>
    <br>
</body>

<script src="assets/js/validate.js"></script>
<script src="assets/js/animate.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</html>