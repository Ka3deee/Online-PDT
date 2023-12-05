function getCurrentDate() {
    const today = new Date();
  
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
  
    const formattedDate = `${year}${month}${day}`;
  
    return formattedDate;
}

function openDownload() {
    if (isUserSet == false) { 
        alert('User is required. Please set a user first.');
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

function confirmExit() {
    if (confirm('Are you sure you want to exit Transfer Out?')) {
        window.location.href = '../smr.php';
    }
}

function CheckStore(event) {
    if (event.key === "Enter") {
        var store = document.getElementById('store-num').value;
        if (store == "") {
            alert("Please enter store code");
            return 0;
        }
        document.getElementById('loader-wrapper').style.display = 'flex'; // Fix style assignment

        setTimeout(function() {
            var response;
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                response = this.responseText;
                if (response == "no result") {
                    alert("Invalid store code. Please Try Again");
                    location.reload();
                } else {
                    var storedetails = response.split("-");
                    location.reload();
                }
            };
            xhttp.open("GET", "../controllers/get_store.php?check_store=" + store);
            xhttp.send();
        }, 250);
    }
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
                        window.location.href='../index.php';
                    } else {
                        alert("Request failed with status:", xhttp.status);
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

function docNo(event) {
    const inputElement = event.target;
    const keyCode = event.keyCode;

    // Check if the key code represents a number (0-9)
    if (keyCode >= 48 && keyCode <= 57) {
        const inputValue = inputElement.value;
        const inputValueWithoutNewlines = inputValue.replace(/\n/g, '');

        if (inputValueWithoutNewlines.length >= 6) {
            event.preventDefault();
        }
        
    } else if (event.key === "Enter") {
        const storeNumInput = document.getElementById("store-num");
        var storeCodeInput = document.getElementById("store-code");
        var docNumInput = document.getElementById("doc-num");
        var trfOutList = document.getElementById("trf-out-list");
        var storeCodeValue = storeCodeInput.innerHTML;
        var docNumValue = docNumInput.value.trim();
        var storeList;

        if (docNumValue !== "") {

            if (storeCodeValue !== "") {
                
                const lines = trfOutList.value.split("\n").length;
                const maxRows = 7;
                if (lines > maxRows) {
                    trfOutList.scrollTop = trfOutList.scrollHeight;
                }
    
                trfOutList.value += storeCodeValue + "," + docNumValue + "\n";
                docNumInput.value = "";
                storeList = trfOutList.value;
                
                sessionStorage.setItem('trfOutList', storeList);
                event.preventDefault();

            } else {

                alert("Please set a store first");
                docNumInput.value = "";
                storeNumInput.focus();

            }
        }
    } else {
        event.preventDefault();
    }
}

function Clear() {
    if (confirm("Are you sure to clear field") == true) {
        sessionStorage.clear();
        location.reload();
    }
}

function Download() {
    var trfout_list = document.getElementById("trf-out-list");
    var trfout_data = trfout_list.value;
    const download = 'download';

    document.getElementById('loader-download').style = 'display:flex';

    setTimeout(function() {
        var response;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    response = xhttp.responseText;
                    if (response == "no data found") {
                        alert("No Data to Download, Please try again.");
                        location.reload();
                    } else { 
                        location.reload();
                    }
                } else {
                    alert("Request failed with status:", xhttp.status);
                    location.reload();
                }
            }
        }
        var data = "download=" + encodeURIComponent(download) + "&trfout_data=" + encodeURIComponent(trfout_data);
        xhttp.open("POST", "../controllers/get_trfoutdata.php", true);
        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhttp.send(data);
    }, 500);
}

function saveTextFile() {
    var confirmation = confirm("Are you sure to download data as text file?");

    if (confirmation) {
        document.getElementById("download-animation-wrapper").classList.remove("hidden");
        // document.getElementById("btn_prev").disabled = true;
        // document.getElementById("btn_download").disabled = true;
        // document.getElementById("btn_save").disabled = true;
        // document.getElementById("btn_exit").disabled = true;
        const currentDate = getCurrentDate();

        var xhttp = new XMLHttpRequest();
        xhttp.open('GET', '../controllers/generate_text.php', true);
        xhttp.responseType = 'blob'; 
        xhttp.onload = function (e) {
            if (this.status === 200) {
                
                var blob = new Blob([this.response], { type: 'text/plain' });
                var downloadLink = document.createElement('a');
                downloadLink.href = window.URL.createObjectURL(blob);
                downloadLink.download = 'TrfReleasingMasterData_' + currentDate + '.txt';

                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                // document.getElementById("btn_prev").disabled = false;
                // document.getElementById("btn_download").disabled = false;
                // document.getElementById("btn_save").disabled = false;
                // document.getElementById("btn_exit").disabled = false;
                document.getElementById("download-animation-wrapper").classList.add("hidden");
            }
        };
        xhttp.send();
    }
}