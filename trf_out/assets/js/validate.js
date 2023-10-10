function openDownload() {
    if (isStoreSet == false) { 
        alert('Please set a store'); 
    } else if (isUserSet == false) { 
        alert('Please set a user');
    } else {
        window.location.href='pages/trfout_download.php'; 
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
    xhttp.open("GET", "../controllers/get_store.php?check_store=" + store);
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

    document.getElementById('insert-user').addEventListener("submit", function(event) {
        event.preventDefault();

        document.getElementById('loader-modal').style = 'display:flex';

        setTimeout(function() {
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
        }, 500)
    });
}

function UpdateUser() {
    var updateUser = 'update_user';
    var id = document.getElementById("id").value;
    var firstname = document.getElementById("firstname").value;
    var middlename = document.getElementById("middlename").value;
    var lastname = document.getElementById("lastname").value;
    var employee_no = document.getElementById("employee_no").value; 
    var password = document.getElementById("password").value;

    if (firstname == '' || lastname == '' || employee_no == '' || password == '') {
        alert('Please fill all required fields');
        return 0;
    }

    document.getElementById('insert-user').addEventListener("submit", function(event) {
        event.preventDefault();

        document.getElementById('loader-modal').style = 'display:flex';

        setTimeout(function() {
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
    
            var data = "update_user=" + encodeURIComponent(updateUser) + "&id=" + encodeURIComponent(id) + "&firstname=" + encodeURIComponent(firstname) + "&middlename=" + encodeURIComponent(middlename) + "&lastname=" + encodeURIComponent(lastname) + "&employee_no=" + encodeURIComponent(employee_no) + "&password=" + encodeURIComponent(password);
            xhttp.open("POST", "user_maintenance.php", true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhttp.send(data);
        }, 500)
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

        setTimeout(function() {
            var response;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState === 4) {
                    if (xhttp.status === 200) {
                        response = xhttp.responseText;
                        if (response == 'User not found') {
                            alert('Failed :\nUser not found! Please try again.');
                        } 
                        location.reload();
                    } else {
                        console.error("Request failed with status:", xhttp.status);
                        location.reload();
                    }
                }
            }
    
            var data = "set_user=" + encodeURIComponent(setUser) + "&employee_no=" + encodeURIComponent(employee_no) + "&password=" + encodeURIComponent(password);
            xhttp.open("POST", "../controllers/get_users.php", true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhttp.send(data);
        }, 500)
    });
}

function createUserModal() {
    var modalTitles = document.querySelectorAll('.modal-title');
    modalTitles.forEach(function(modalTitle) {
      modalTitle.textContent = 'Add';
    });
    document.getElementById('id').value = '';
    document.getElementById('employee_no').value = '';
    document.getElementById('firstname').value = '';
    document.getElementById('middlename').value = '';
    document.getElementById('lastname').value = '';
    document.getElementById('password').value = '';
    toggleModal();
}
   
function updateUserModal(id, employee_no, firstname, middlename, lastname) {
    var modalTitles = document.querySelectorAll('.modal-title');
    modalTitles.forEach(function(modalTitle) {
      modalTitle.textContent = 'Update';
    });
    document.getElementById('id').value = id;
    document.getElementById('employee_no').value = employee_no;
    document.getElementById('firstname').value = firstname;
    document.getElementById('middlename').value = middlename;
    document.getElementById('lastname').value = lastname;
    toggleModal();
}

var alertConfirm = document.querySelectorAll('.alert');

for (var i = 0; i < alertConfirm.length; i++) {
  alertConfirm[i].addEventListener('click', function(event) {
    event.preventDefault();

    var choice = confirm(this.getAttribute('data-confirm'));

    if (choice) {
      window.location.href = this.getAttribute('href');
    }
  });
}

function transfersOnly(event) {
    const textareaElement = event.target;
    const keyCode = event.keyCode;

    if ((keyCode >= 48 && keyCode <= 57) || keyCode === 13) {
        const inputValue = textareaElement.value;
        const newlineCount = (inputValue.match(/\n/g) || []).length;
        const currentLine = inputValue.split('\n').pop();
        if (currentLine.length === 8 && keyCode !== 13) {
            event.preventDefault();
        }
    } else {
        event.preventDefault();
    }
}

