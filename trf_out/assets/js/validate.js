function StoreIsSet() {
    if (isStoreSet == false) { 
        alert('Please set store first'); 
    } else { 
        window.location.href='download_trfout.php'; 
    }
}

function CheckAdmin() {
    let code = prompt("Enter Administrator Passcode");
    if (code != null) {
        if(code  == '13791379'){
            window.location.href="user_maintenance.php";
        }else{
            alert("Invalid Passcode, Please try again");
        }
    }
}

function CheckStore(){
    var store = document.getElementById('store-code').value;
    if(store == ""){
        alert("Please enter store code");
        return 0;
    }
    document.getElementById('loader-wrapper').style = 'display:flex';
    var response;
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        response =  this.responseText;
        if (response == "no result") {	
            alert("Invalid store code. Please Try Again");
            location.reload();			
        } else {
            var storedetails = response.split("-");
            document.getElementById('save-btn').disabled = false;
            location.reload();
        }			
    }
    document.getElementById('save-btn').disabled = true;
    xhttp.open("GET", "controllers/get_store.php?check_store=" + store);
    xhttp.send();
}

function CreateUser() {
    var createUser = 'create_user';
    var firstname = document.getElementById("firstname").value;
    var middlename = document.getElementById("middlename").value;
    var lastname = document.getElementById("lastname").value;
    var employee_no = document.getElementById("employee_no").value; 
    var password = document.getElementById("password").value;

    if (firstname == '' || lastname == '' || employee_no == '' || password == '') {
        alert('Please fill all required fields');
        return 0;
    }

    document.getElementById('create-user').addEventListener("submit", function(event) {
        event.preventDefault();

        var response;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    response = xhttp.responseText;
                    location.reload();	
                } else {
                    console.error("Request failed with status:", xhttp.status);
                    location.reload();	
                }
            }
        };

        var data = "create_user=" + encodeURIComponent(createUser) + "&firstname=" + encodeURIComponent(firstname) + "&middlename=" + encodeURIComponent(middlename) + "&lastname=" + encodeURIComponent(lastname) + "&employee_no=" + encodeURIComponent(employee_no) + "&password=" + encodeURIComponent(password);
        xhttp.open("POST", "user_maintenance.php", true);
        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhttp.send(data);
    });
}

function SetUser() {
    const setUser = 'set_user';
    var employee_no = document.getElementById('employee_no').value;
    var password = document.getElementById('password').value;

    if (employee_no == '') {
        alert('Please enter employee no');
        return 0;
    }
    if (password == '') {
        alert('Please enter password');
        return 0;
    }

    document.getElementById('set-user').addEventListener("submit", function(event) {
        event.preventDefault();

        document.getElementById('loader-wrapper').style = 'display:flex';

        var response;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    response = xhttp.responseText;
                    location.reload();
                } else {
                    console.error("Request failed with status:", xhttp.status);
                    location.reload();
                }
            }
        }

        var data = "set_user=" + encodeURIComponent(setUser) + "&employee_no=" + encodeURIComponent(employee_no) + "&password=" + encodeURIComponent(password);
        xhttp.open("POST", "set_user.php", true);
        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhttp.send(data);
    });
}
