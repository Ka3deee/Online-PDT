//inserting to po_ref Select Tag
      function addporef(poref){
        var urlParams = new URLSearchParams(location.search);
        var ar_ref = String(urlParams).split("=");
        if(ar_ref == ""){
          alert('Please provide AR ref first');
          window.location.href = 'drt_rcv_a.php';
          return;
        }
        if(document.getElementById('txtporef').value == ""){
          alert('Please Enter PO Reference! Thank you.');
          document.getElementById('txtporef').focus();
          return;
        }
          //insert to poref dropdown
        _insertToCombo(document.getElementById('txtporef').value);
      }
      
      function _insertToCombo(po_ref){
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            document.getElementById('txtporef').value = "";
            document.getElementById('po_ref_list').innerHTML = this.responseText;
        }
        
        document.getElementById('po_ref_list').innerHTML = "";
        xhttp.open("GET", "opt/mysqlpost.php?storetosession="+po_ref);
        xhttp.send();
      }
      // For Testing Clear Sessions
      function clearsession(){
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
          document.getElementById('po_ref_list').focus();
        }
        xhttp.open("GET", "opt/mysqlpost.php?clearsession");
        xhttp.send();
      } 
      function addpo(po_ref,type,saleinv,amount){
          if(po_ref == "" || type == "" || saleinv == "" || amount == ""){
              alert("System Says: Please Enter SI,amount and Invoice type!");
              return;
          }
		  
		  //review inputs
		   if (!confirm("Are you sure you ? Please Review Your Input")){return;} 
		   
		   
          //insert to inv_sum_tbl
          const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            if(this.responseText != "not inserted" ){
                //execute next insert
                alert('inv reference: '+saleinv+' added.');
                document.getElementById('txtsalesinvoice').value = "";
                document.getElementById('txtamount').value = "";
                //insert into table
                Inv_list_check(po_ref);
                //send to mysql
                sendToMysql(po_ref);

            }else{
                alert('System Says: Error occured while inserting Data');
            }			
          }
          xhttp.open("GET", "opt/mysqlpost.php?insert_inv&po_ref="+po_ref+"&type="+type+"&saleinv="+saleinv+"&amount="+amount);
          xhttp.send();
        
      }
      function Inv_list_check(po_ref){
          const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            if(this.responseText == "not found" ){
                alert('System Says: Empty List');
            }else{
              document.getElementById('lv_Si').innerHTML = this.responseText;
            }			
          }
          document.getElementById('lv_Si').innerHTML = "";
          xhttp.open("GET", "opt/mysqlpost.php?inv_list_check&po_ref="+po_ref);
          xhttp.send();
      }
      function sendToMysql(po_ref){
        var urlParams = new URLSearchParams(location.search);
        var ar_ref = String(urlParams).split("=");

        const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            if(this.responseText.match(/Error.*/)){
                alert(this.responseText);
            }
          }
          xhttp.open("GET", "opt/mysqlpost.php?sendtomsysql&po_ref="+po_ref+"&ar_ref="+ar_ref[1]);
          xhttp.send();
      }