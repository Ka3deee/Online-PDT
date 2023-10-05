<?php session_start(); ?>
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
    <div class="display-center" style="width: ">
        <div class="display-center"> 
            <div class="display-center">
                <img src="../resources/lcc.jpg" alt="LCC Logo">
            </div>
            <h4 class="tc font mb">TRF Releasing : Set User</h4>
            <h5 class="tc semi-visible">v1.0.0</h5>
            <br>
            <br>
        </div>
        <div class="display-center">
            <div id="loader-wrapper" class="mb w">
                <div id="loader"></div><strong>Checking user... Please wait...</strong>
            </div>
            <div class="mb w">
                <div class="msg warning">Please set a user</div>
            </div>  
            <div class="mb w">
                <label for="ee-no">EE No.</label>
                <input class="btn-lg" id="ee-no" type="text">
            </div>
            <div class="mb w">
                <label for="password">Password</label>
                <input class="btn-lg" id="password" type="password">
            </div>
            <div class="mb w">
                <button class="btn btn-lg" id="save-btn">Set</button>
            </div>
            <div class="mb w">
                <button onclick="CheckAdmin()" class="btn btn-lg" id="maintenance-btn">User Maintenance</button>
            </div>
            </div>
            <div class="mb w">
                <button onclick="window.location.href='../trf_out/index.php'" class="btn btn-lg">Back</button>
            </div>
        </div>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
    </div>
</body>
<script>
    function CheckAdmin(){
		let code = prompt("Enter Administrator Passcode");
		if (code != null) {
			if(code  == '13791379'){
				window.location.href="user_maintenance.php";
			}else{
				alert("Invalid Passcode, Please try again");
			}
        }
	}
</script>
<script src="assets/js/animate.js"></script>
</html>