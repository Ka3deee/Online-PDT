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
            <h4 class="tc font mb">TRF Releasing : User Maintenance</h4>
            <h5 class="tc semi-visible">v1.0.0</h5>
            <br>
            <br>
        </div>
        <div class="display-center">
            <h4 class="tc mb">User List</h4>
            <div class="tbl-wrap mb w">
                <table class="w p-bot">
                    <thead class="thead">
                        <th class="thead-des">SN</th>
                        <th class="thead-des">EE No.</th>
                        <th class="thead-des">Full Name</th>
                        <th class="thead-des" colspan="2"><div class="flex-between">Action<button class="btn-md">Add</button></div></th>
                    </thead>
                    <tbody class="tbody">
                        <tr>
                            <td class="tbody-des">1</td>
                            <td class="tbody-des">31529</td>
                            <td class="tbody-des">Rainier C. Barbacena</td>
                            <td class="tbody-des"><button class="btn btn-md">Update</button></td>
                            <td class="tbody-des"><button class="btn btn-md">Delete</button></td>
                        </tr>
                        <tr>
                            <td class="tbody-des">1</td>
                            <td class="tbody-des">31529</td>
                            <td class="tbody-des">Rainier C. Barbacena</td>
                            <td class="tbody-des"><button class="btn btn-md">Update</button></td>
                            <td class="tbody-des"><button class="btn btn-md">Delete</button></td>
                        </tr>
                        <tr>
                            <td class="tbody-des">1</td>
                            <td class="tbody-des">31529</td>
                            <td class="tbody-des">Rainier C. Barbacena</td>
                            <td class="tbody-des"><button class="btn btn-md">Update</button></td>
                            <td class="tbody-des"><button class="btn btn-md">Delete</button></td>
                        </tr>
                        <tr>
                            <td class="tbody-des">1</td>
                            <td class="tbody-des">31529</td>
                            <td class="tbody-des">Rainier C. Barbacena</td>
                            <td class="tbody-des"><button class="btn btn-md">Update</button></td>
                            <td class="tbody-des"><button class="btn btn-md">Delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb w">
                <button onclick="window.history.back();" class="btn btn-lg">Back</button>
            </div>
        </div>

        <div id="preloader">
            <div class="caviar-load"></div>
        </div>
    </div>
</body>

<script src="assets/js/animate.js"></script>
</html>