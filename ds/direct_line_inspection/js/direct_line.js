      var result_json = [];
      var mylot;
      var lot_req;
      var sku_parent ;
      var sku_child ;
      var Qty_tally;
      var Sku_qty = 0;
      var req_qty = 0;
      var relFlag = 0;
      var rec_li = 0;
      var rec_qty1 = 0;
      var order_li = 0;
      var order_qty1 = 0;
      //alert(lotneeded("3001224"));
      function searchpo(po){
          lotneeded("3001224");
          var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            if(this.responseText == "No-Data"){
              alert("Document not found");  
              location.reload();
            }else{
              result_json = JSON.parse(this.responseText);
              console.log(result_json);
              //b.RA_vendor_name,a.po_rcr_ref,a.po_stat,a.PO_AR_Ref from po_sum_tbl
              document.getElementById('txtvendor').value = result_json[0].RA_vendor_name;
              document.getElementById('txtar_ref').value = result_json[0].PO_AR_Ref;
              document.getElementById('txtpo_ref').value = po;
              //enable buttons
              document.getElementById('btnconfirm').disabled = false;
              document.getElementById('btncheck').disabled = false;
              //documents check
              Documents_check(po);
              document.getElementById('txtupc').focus();
            }			
          }
          xhttp.open("GET", "opt/mysqlpost.php?po="+po);
          xhttp.send();

      }
      
      function Documents_check(po){
          order_qty(po);
          rec_qty(po);
      }
      function order_qty(po){
        var resultjson = [];
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            if(this.responseText == "No-Data"){
              console.log(this.responseText);
            }else{
              resultjson = JSON.parse(this.responseText);
              console.log(resultjson);

              if(resultjson[0].itemcount == ""){
                order_li = 0;
                alert("PO details not uploaded yet pleease ask assistance from mms processor");
              }else{
                order_li = resultjson[0].itemcount;
              }
              if(resultjson[0].itemsum == ""){
                order_qty1 = 0;
              }else{
                order_qty1 = resultjson[0].itemsum;
              }
              document.getElementById('txtorder_li').value = order_li;
              document.getElementById('txtorder_qty').value = order_qty1;
            }			
          }
          xhttp.open("GET", "opt/mysqlpost.php?order_po="+po,false);
          xhttp.send();
      }
      function rec_qty(po){
        var resultjson = [];
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            if(this.responseText == "No-Data"){
              console.log(this.responseText);
            }else{
              
              resultjson = JSON.parse(this.responseText);
              console.log(resultjson);
             
              if(resultjson[0].item_rec_qty == ""){
                rec_li = 0;
              }else{
                rec_li = resultjson[0].item_rec_qty;
              }
              if(resultjson[0].item_rec_sum == ""){
                rec_qty1 = 0;
              }else{
                rec_qty1 = resultjson[0].item_rec_sum;
              }
              
              document.getElementById('txtrec_li').value = rec_li;
              document.getElementById('txtrec_qty').value = rec_qty1;

              document.getElementById('txtvar_li').value = parseInt(document.getElementById('txtorder_li').value) - parseInt(rec_li);
              document.getElementById('txtlvar_qty').value = parseInt(document.getElementById('txtorder_qty').value) - parseInt(rec_qty1);
              
            }			
          }
          xhttp.open("GET", "opt/mysqlpost.php?rec_qty="+po,false);
          xhttp.send();
      }

      function searchupc(upc,poref){
        if(poref == ""){
            alert("Please Add PO Reference. Thank you!");
            return
        }
        relFlag = 0;
         //Sku_qty = 15;
        // req_qty = 30;
        var resultjson = [];
        const xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
			  //alert(this.responseText);
            if(this.responseText == "No-Data"){
                console.log(this.responseText);
                searchupc1(upc,poref);
            }else{           
                resultjson = JSON.parse(this.responseText); 
				
                setdata(resultjson,0);
            }			
          }
          xhttp.open("GET", "opt/mysqlpost.php?s_upc="+upc+"&ponum_ref="+ poref);
          xhttp.send();

      }
      function searchupc1(upc,poref){
        relFlag = 1;
        var resultjson = [];
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            
            if(this.responseText == "No-Data"){
                console.log(this.responseText);
                alert('Item not included in the list');
                document.getElementById('txtupc').value = '';
                document.getElementById('txtupc').focus();
            }else{           
                resultjson = JSON.parse(this.responseText); 
                setdata(resultjson,1);
            }			
          }
          xhttp.open("GET", "opt/mysqlpost.php?s_upc1="+upc+"&ponum_ref1="+ poref);
          xhttp.send();
      }
      function setdata(resultjson,flag){
            
            //0                1        2                   3           4               5               6               7             8         9                  10            11                              
            //b.item_sku,b.item_desc,b.item_req_qty,b.item_rec_qty, b.item_sku_ret,b.item_check_tally,b.item_remarks,b.item_exp_ref,a.upc,c.storeExp as exp,a.sku_parent,a.sku_child 
            if(flag == 1){
                sku_parent = resultjson[0].sku_parent;
                sku_child = resultjson[0].sku_child;
            }
            //sku
            document.getElementById('txt_sku').value = resultjson[0].item_sku;
            //txt_req
            document.getElementById('txt_req').value = resultjson[0].item_req_qty;
            if(resultjson[0].item_rec_qty == ""){
                resultjson[0].item_rec_qty = 0;
            }
            //txt_tally
            document.getElementById('txt_tally').value = resultjson[0].item_check_tally;
            Qty_tally = resultjson[0].item_check_tally;

            //txtdesc
            document.getElementById('txt_desc').value = resultjson[0].item_desc + "Retail:" + resultjson[0].item_sku+ " Qty Received :" +  resultjson[0].item_rec_qty;
            Sku_qty = resultjson[0].item_rec_qty;
            req_qty = resultjson[0].item_req_qty;

            if(resultjson[0].item_exp_ref == ""){
              //probably if not blank expiry already set
              mylot = resultjson[0].item_exp_ref;
              //hide exp_lot
              document.getElementById('exp_lot').style.display = "none";
              lot_req = true;
              //txt_rec
              document.getElementById('txt_rec').disabled = false;
              document.getElementById('txt_rec').value = '1';
              document.getElementById('txt_rec').focus();
            }
            else{
                //If lot_needed(lbl_sku.Text) = True Then
                if (lotneeded(resultjson[0].item_sku)){
                  mylot = "";
                  alert("Expiry date needed for this SKU");
                  //hide exp_lot
                  document.getElementById('txt_rec').disabled = true;
                  document.getElementById('exp_lot').style.display = "block";
                  lot_req = false;
                  tdocument.getElementById('txt_lot').focus();
                }else{ 
                   //hide exp_lot
                  document.getElementById('exp_lot').style.display = "none";
                  lot_req = false;

                  document.getElementById('txt_rec').disabled = false;
                  document.getElementById('txt_rec').value = '1';
                  document.getElementById('txt_rec').focus();
              
                }
            }
      }

      function lotneeded(val){  
        var result;
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {            
            if(this.responseText == "No-Data"){
              result = false;
            }else{           
              result = true;
            }			
          }
          xhttp.open("GET", "opt/mysqlpost.php?lot_needed="+val,false);
          xhttp.send();
          return result;
      }
      function checkqty(value1,poref){
          var srelFlag = "";
          var slot_req = "";
          if(poref == ""){
            alert("Please Add PO Reference. Thank you!");
            return
          }
          if((parseFloat(Sku_qty) + parseFloat(value1) > parseFloat(req_qty) )){
            alert("Quantity over than required");
            return
          }else{
            //add qty receive
            Sku_qty = parseFloat(Sku_qty) + parseFloat(value1);

            if(Qty_tally == ""){
              Qty_tally = parseFloat(value1);
            }else{
              Qty_tally = Qty_tally +","+ value1;
            }

            if(mylot == ""){
               mylot = document.getElementById('txt_lot').value;
            }
            if(lot_req == true){
              slot_req = "true";
              if(relFlag == 1) srelFlag = "1";
              else srelFlag = "0";
              
            }else{
              slot_req = "false";
              if(relFlag == 1)srelFlag = "1";
              else srelFlag = "0";
              
            }
            //update item ()
            updateitem(slot_req,srelFlag,mylot,Qty_tally,Sku_qty,poref,sku_parent,sku_child,document.getElementById('txt_sku').value);
            Sku_qty = 0
            Qty_tally = ""
            Documents_check(poref);
            clearfields();
            document.getElementById('txtupc').focus();
          }
      }
      function updateitem(slot_req,srelFlag,mylot,Qty_tally,Sku_qty,poref,sku_parent,sku_child,sku){
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            //alert(this.responseText);
            if(this.responseText == "0"){
                console.log(this.responseText);
                alert('Database error. Not updated ');
            }		
          }
          xhttp.open("GET", "opt/mysqlpost.php?slot_req="+slot_req+"&srelFlag="+ srelFlag+"&mylot="+ mylot+"&Qty_tally="+ Qty_tally+"&Sku_qty="+ Sku_qty+"&poref="+ poref+"&sku_parent="+ sku_parent+"&sku_child="+ sku_child+"&sku="+ sku);
          xhttp.send();
      }
      function clearfields(){
          document.getElementById('txtupc').value = "";
          document.getElementById('txt_desc').value = "";
          document.getElementById('txt_sku').value = "";
          document.getElementById('txt_tally').value = "";
          document.getElementById('txt_req').value = "";
          document.getElementById('txt_rec').value = "";
          document.getElementById('txt_lot').value = "";
      }
      function setexp(date){
        if(check_if_expire(date) == false){
          document.getElementById('txt_rec').value = "1";
          document.getElementById('txt_rec').focus().select();
        }else{
          alert("Item expiry date not acceptable. Is should be atleast 90 days before expiration");
          document.getElementById('txt_lot').focus();
        }
      }
      function check_if_expire(date){
        //date_diff($datetime1, $datetime2)
        var result;
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {           
            if(this.responseText == "exceeds"){
              result = false;
            }else{           
              result = true;
            }			
          }
          xhttp.open("GET", "opt/mysqlpost.php?checkexpire="+date,false);
          xhttp.send();
          return result;
      }
      function confirm_check(po){
        var var_li = document.getElementById('txtvar_li').value;
        var lvar_qty = document.getElementById('txtlvar_qty').value;
        
          Documents_check(po);
          if(var_li == 0){
              if(lvar_qty == 0){
                confirmed_end(po);
              }else{
                if (confirm("Not all quantity served. Do you want to proceed?")){
                    confirmed_end(po);
                } 
              }
          }else{
            if (confirm("Not all sku served. Do you want to proceed?")){
              confirmed_end(po);
            } 
          }
      }
      function confirmed_end(po){
        alert(po);
		   if (!confirm("Confirm Transaction?")) return;
        var resultjson = [];
        var tot_qty  = 0;
        var sku_cnt  = 0;
        var po_cnt  = 0;
        var c_ar_ref = document.getElementById('txtar_ref').value;
          //update po_sum_tbl
          if(uploadposum(po) == 0){
            alert("Database error. Not updated");
			      return;
          }
          //get skue count and item_rec_qty
          if(count_item_sku(c_ar_ref) != "No-Data"){
            resultjson = JSON.parse(count_item_sku(c_ar_ref));
            sku_cnt = resultjson[0].citemsku;
            tot_qty = resultjson[0].crec_qty;
          }else{
            alert("DB error");
			      return;
          }

          //count PO
          if(countPO(c_ar_ref) != "No-Data"){
            resultjson = JSON.parse(countPO(c_ar_ref));
            po_cnt = resultjson[0].pocount;
          }else{
            alert("DB error");
			      return;
          }

          //update ar_sum_tbl
          
          if(uploadar_sum(sku_cnt,tot_qty,po_cnt,c_ar_ref) == 0){
            alert("Database error. Not updated");
			      return;
          }
			
			alert("Successfully completed");
            window.location.href='../';

      }
      function uploadposum(po){
        var result = 1;
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {           
            if(this.responseText == "0"){
              result = 0;
            }		
          }
          xhttp.open("GET", "opt/mysqlpost.php?uploadposum="+po,false);
          xhttp.send();
          return result;
      }
      function uploadar_sum(sku_cnt,tot_qty,po_cnt,c_ar_ref){
        var result = 1;
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {           
            if(this.responseText == "0"){
              result = 0;
            }		
          }
          xhttp.open("GET", "opt/mysqlpost.php?uploadar_sum&usku_cnt="+sku_cnt+"&utot_qty="+tot_qty+"&upo_cnt="+po_cnt+"&uc_ar_ref="+c_ar_ref,false);
          xhttp.send();
          return result;
      }
      function count_item_sku(c_ar_ref){
        var result = 'No-Data';
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {           
            if(this.responseText != "No-Data"){
              result = this.responseText;
            }		
          }
          xhttp.open("GET", "opt/mysqlpost.php?c_ar_ref="+c_ar_ref,false);
          xhttp.send();
          return result;
      }
      function countPO(c_ar_ref){
        var result = 'No-Data';
        var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {           
            if(this.responseText != "No-Data"){
              result = this.responseText;
            }		
          }
          xhttp.open("GET", "opt/mysqlpost.php?count_ar_ref="+c_ar_ref,false);
          xhttp.send();
          return result;
      }
      function populate_check_detail(){
          var po_ref = document.getElementById('txtpo_ref').value;
          document.getElementById('txtCheckPO').value = po_ref;
          //clear List
          document.getElementById('check_List').innerHTML = "";
          //populate list
          var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {           
            if(this.responseText != "No-Data"){
              document.getElementById('check_List').innerHTML= this.responseText;
            }		
          }
          xhttp.open("GET", "opt/mysqlpost.php?getdetails="+po_ref,false);
          xhttp.send();
      }

      function Get_sku(sku,poref){
          //clear List
          //alert(sku);
          document.getElementById('txtdescid').value = "";
          var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {        
            //alert(this.responseText);   
            if(this.responseText != "No-Data"){
                document.getElementById('txtdescid').value= this.responseText;
            }else{
                alert("SKU not recognized");
            }		
          }
          xhttp.open("GET", "opt/mysqlpost.php?getdesc="+sku+"&desc_po="+poref);
          xhttp.send();
      }
      //close and opening modal

      function openmodal(id){
        populate_check_detail();
        var modal = document.getElementById(id);
		    modal.style.display = "block";
      }
      function closemodal(id){
        var modal = document.getElementById(id);
		    modal.style.display = "none";
      }

      
      

   
     