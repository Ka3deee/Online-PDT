function getCurrentDate() {
    const today = new Date();
  
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
  
    const formattedDate = `${year}${month}${day}`;
  
    return formattedDate;
}

function CheckAdmin() {
    let code = prompt("Enter Administrator Passcode");
    if (code != null) {
        if(code  == '13791379'){
            window.location.href="user_maintenance.php";
        }else{
            alert("Invalid Passcode! Please try again.");
        }
    }
}

function openDownload() {
    if (isUserSet == false) { 
        alert('User is required. Please set a user first.');
    } else {
        window.location.href='pages/trfout_download.php'; 
    }
}

function openScan() {
    if (isUserSet == false) {
        alert('User is required. Please set a user first.');
    } else {
        window.location.href='pages/trfout_scan.php'
    }
}

function openPrint() {
    if (isUserSet == false) { 
        alert('User is required. Please set a user first.');
    } else {
        window.location.href='pages/trfout_print.php'; 
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
                    alert("Invalid store code! Please try again.");
                    location.reload();
                } else {
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
    if (confirm("Are you sure to clear field?") == true) {
        sessionStorage.clear();
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            location.reload();
        }
        xhttp.open("POST", "../controllers/get_trfoutdata.php?clear_store=");
        xhttp.send();
    }
}

function Download(trfout_data) {
    const download = 'download';
    document.getElementById("progress-bar").style.width = "0%";
    document.getElementById("percent-value").innerText = "0%";
    document.getElementById('loader-download').style.display = 'flex'; // Change style assignment to display properly
    document.getElementById('loadarea').src = "../controllers/get_trfoutdata.php?download=" + encodeURIComponent(download) + "&trfout_data=" + encodeURIComponent(trfout_data);

}
function saveTextFile() {
    var confirmation = confirm("Are you sure to export data as text file?");

    if (confirmation) {
        document.getElementById("download-animation-wrapper").classList.remove("hidden");
        const currentDate = getCurrentDate();

        var xhttp = new XMLHttpRequest();
        xhttp.open('GET', '../controllers/generate_text.php', true);
        xhttp.responseType = 'blob'; 
        xhttp.onload = function (e) {
            if (this.status === 200) {
                
                var blob = new Blob([this.response], { type: 'text/plain' });
                var downloadLink = document.createElement('a');
                downloadLink.href = window.URL.createObjectURL(blob);
                downloadLink.download = 'TRFOUTDBMaster_' + currentDate + '.txt';

                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                document.getElementById("download-animation-wrapper").classList.add("hidden");
            }
        };
        xhttp.send();
    }
}

function getBarcode(code) {
    
    if (isNaN(code)) {
        prompt_alert('Scanned barcode is invalid! Please try again.');
        clear_fields();
        document.getElementById('barcode').focus();
        return 0;
    }
    if (code.charAt(0) === '0') {
        code = code.substring(1);
    }
    if (code == "") {
        document.getElementById('barcode').focus();
        return 0;
    }

    var sku_response;
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        sku_response = this.responseText;
        if (sku_response === "not found") {
            document.getElementById('loader-download').style.display = "none";
            clear_fields();
            document.getElementById('barcode').focus();	
            document.getElementById('duplicateSKU').innerHTML = "";
            prompt_alert('There is no item having the scanned barcode! Invalid UPC.');
            return 0;
        } else {
            document.getElementById('promptalert').style.display = "none";
            check_duplicate(sku_response);
        }
    }
    document.getElementById('loader-download').style.display = "block";
    xhttp.open("GET", "../controllers/scan_data.php?barcode="+code);
    xhttp.send();
}

function check_duplicate(sku) {

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.responseText == "no duplicate") {
            getSkudata(sku, 'none');
        } else {
            document.getElementById('loader-download').style.display = "none";
            document.getElementById('duplicateSKU').innerHTML = this.responseText;
        }
    }
    xhttp.open("GET", "../controllers/scan_data.php?sku="+sku);
    xhttp.send();
}

function prompt_alert(content) {
    document.getElementById('prompt_title').innerHTML = content;
    document.getElementById('promptalert').style.display = "block";
    setTimeout(
        function() {
            document.getElementById('promptalert').style.display = "none";
        }
    ,3000);
}

function clear_fields() {
    document.getElementById('barcode').value = "";
    document.getElementById('sku').value = "";
    document.getElementById('whmove').value = "";
    document.getElementById('desc').innerHTML = "";
    document.getElementById('qty').value = "";
    document.getElementById('expiry').value = "";
    document.getElementById('duplicateSKU').innerHTML = "";
}

function getSkudata(sku, whmove){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementById('loader-download').style.display = "none";
        if(this.responseText == "exceeds"){
            prompt_alert('Receive quantity is already equal to the expected quantity');
            clear_fields();
            document.getElementById('barcode').focus();
            return 0;
        }else{
            document.getElementById("duplicateSKU").innerHTML = "";
            document.getElementById("displayitemskuref").innerHTML = this.responseText;
            document.getElementById('qty').focus();
        }
    }
    document.getElementById('loader-download').style.display = "block";
    xhttp.open("GET", "../controllers/scan_data.php?skunoduplicate="+sku+"&whmove="+whmove);
    xhttp.send();
}

function acceptQty(input_qty, current, exp_qty, whmove, sku) {
    var acceptBtn = document.getElementById('accept-btn').disabled;
    var barcodeInput = document.getElementById('barcode');
    acceptBtn = true;
    if (sku == "" || whmove == "" ) {
        prompt_alert('Please scan item. Thank you!');
        acceptBtn = false;				
        return 0;
    }
    if((parseInt(input_qty) + parseInt(current)) > exp_qty){
        prompt_alert('Receive quantity will exceed the expected quantity');
        document.getElementById('qty').focus();
        acceptBtn = false;
        return 0;
    }
    var totalQty = parseInt(input_qty) + parseInt(current);
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        var respo = this.response;
        if(this.responseText == "inserted"){
            clear_fields();
            barcodeInput.focus();
            acceptBtn = false;
        }else{
            prompt_alert('Error while inserting Quantity.');
            clear_fields();
            acceptBtn = false;
            barcodeInput.focus();
        }
    }
    xhttp.open("POST", "../controllers/scan_data.php?rcvqty="+totalQty+"&whmove="+whmove+"&inumbr="+sku);
    xhttp.send();
}

function uploadTextFile(event) {
    var upload = 'upload';
    var uploadPoWrapper = document.querySelector(".upload-wrapper");
    var fileImport = document.getElementById('file-import');
    var warningMsg = document.getElementById('warning-msg');
    var loaderUpload = document.getElementById('loader-upload');

    if (fileImport.value.trim() === '' || fileImport.value === null) {
        event.preventDefault();
        warningMsg.style.display = 'flex';
        document.getElementById('file-name').textContent = "";
        return 0;
    } 
    
    var formData = new FormData();
    formData.append('file', fileImport.files[0]);

    warningMsg.style.display = 'none';
    loaderUpload.style = 'display:flex';

    setTimeout(function() {
        var response;
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            response = this.responseText;
            if (response == "upload success") {
                document.getElementById('upload-success').style.display = 'flex';
            } else {
                document.getElementById('upload-error').style.display = 'flex';
            }
            loaderUpload.style.display = 'none';
            uploadPoWrapper.style.display = 'none';
            setTimeout(function() {
                document.getElementById('upload-success').style.display = 'none';
                document.getElementById('upload-error').style.display = 'none';
            }, 5000);
        };

        xhttp.open("POST", "controllers/scan_data.php?upload="+upload);
        xhttp.send(formData);
    }, 500)
}

function selectBatch(batch_no) {
    var trfout = document.getElementById('trf-out-list');
    var response;
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        response = this.responseText;
        trfout.value = response;
    }
    xhttp.open("POST", "../controllers/get_trfoutdata.php?batch="+batch_no);
    xhttp.send();
}

function genPDF(batch_no) {
    var url = "../controllers/generate_pdf.php?batch_no=" + encodeURIComponent(batch_no);
    const currentDate = getCurrentDate();

    fetch(url) // Fetch the PDF content
    .then(response => response.blob())
    .then(blob => {
        var blobUrl = URL.createObjectURL(blob);
        var link = document.createElement('a');
        link.href = blobUrl;
        link.download = "PickingDocument_" + currentDate + ".pdf"; // PDF filename
        link.click();
        // document.body.removeChild(link);
    })
    .catch(error => {
        console.error('Error fetching the PDF:', error);
    });
}

document.body.addEventListener("keyup", function onEvent(event) {
    if (event.key == 'Unidentified' || event.key == 'Enter') {
        getBarcode(document.getElementById('barcode').value);
    }				
});