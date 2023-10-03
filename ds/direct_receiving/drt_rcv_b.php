
<!DOCTYPE html>
<?php
session_start();
include ("opt/redirect.php");
?>
<html lang="en">
<head>
  <title>PDT Application DS : Drirect Receiving</title>
  <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../../images/favicon.ico"/>
    <link rel="bookmark" href="../../images/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link rel="stylesheet" href="../../bootstrap-3.4.1-dist/css/bootstrap.min.css">

  <!---   Content Styles -->
  <link href="../../mycss.css" rel="stylesheet">
  <style>
  .fontTitle , label{
	  font-size:12pt;
	  font-weight:bold;
  }
  label{
	  font-size:10pt;
	  font-weight:bold;
  }
  .btn {
    font-weight:bold;
  }
  table thead,table tbody {
    text-align:left;
    font-size:12pt;
	  font-weight:bold;
  }
  </style>
</head>  
<body >
	<div class="container-fluid text-center">
		<img src="../../resources/lcc.jpg" style="width: 90px; height: 70px;">
        <div class="row" style = "text-align:left;">
          <!-- PO REFERENCE -->
          <div class="col-xs-12">
          <label for="ex1">PO REFERENCE</label>
          <div>
          <input maxlength="18" style = "width:70%;" 
            onkeypress="
            if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;
            if(event.key == 'Enter') 
            { 
              addporef(document.getElementById('txtporef').value);
            }
            "
            class="form-control pull-left" id="txtporef" type="text" autofocus >
          <button style = "width:30%;font-size:1em;" type="button" class="btn btn-primary btn-block pull-right" onclick = "addporef(document.getElementById('txtporef').value)" >Enter</button>
          <!-- 
          <button style = "width:30%;font-size:1em;" type="button" class="btn btn-primary btn-block pull-right" onclick = "clearsession()" >clear</button>
            -->
        </div>
          </div>
          <!-- RO REF -->
          <div class="col-xs-6">
          <label for="ex1">PO Ref</label>
          <select class="form-control " id="po_ref_list" onchange = "Inv_list_check(this.value)">
            <?php
            if(isset($_SESSION['po_ref_array'] )){             
              $po_array = explode(",",substr($_SESSION['po_ref_array'], 0, -1) );
              for($i=0;$i<count($po_array);$i++){
                ?><option><?php echo $po_array[$i]; ?></option><?php
              }
            }
            ?>					
					</select>  
          </div>
          <!-- TYPE -->
          <div class="col-xs-6">
          <label for="ex1">Type</label>
          <select class="form-control " id="type">
            <option value = "SI (Sales Invoice)">SI (Sales Invoice)</option>	
            <option value = "CM (Credit Meno)">CM (Credit Meno)</option>
            <option value = "DR (Delivery Receipt)">DR (Delivery Receipt)</option>	
            <option value = "OS (Order Slip)">OS (Order Slip)</option>		
            <option value = "PL (Packing list)">PL (Packing list)</option>	
            <option value = "SD (SIDR)">SD (SIDR)</option>	
            <option value = "SR (CCN_SSR)">SR (CCN_SSR)</option>	
					</select>  
          </div>
           <!-- Sales Invoice -->
           <div class="col-xs-6">
          <label for="ex1">Sales Invoice</label>
          <input  class="form-control " id="txtsalesinvoice" type="text"  >
          </div>
          <!-- Amount -->
          <div class="col-xs-6">
          <label for="ex1">Amount</label>
          <input   onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control " id="txtamount" type="text"  >
          </div>
    
          <div class="col-xs-12">
          <button  style = "margin-top:5px;"type="button" onclick="addpo(document.getElementById('po_ref_list').value,document.getElementById('type').value,document.getElementById('txtsalesinvoice').value,document.getElementById('txtamount').value)" class="btn btn-primary btn-block btn-md" >Add PO</button>
         </div>

        </div>
        <hr>
        <div class="table-responsive">
         <table class="table table-bordered" style = "font-size:9pt;" >
                  <thead style = "background:#35a8de;">
                  <tr>
                      <th>SI</th>
                      <th>Amount</th>       
                  </tr>
                  </thead>
                  <tbody id = "lv_Si">
                  
                </tbody>
        </table>
        </div>

		
		<button type="button" class="btn btn-primary  btn-block btn-md" onclick = "window.location.href='./'"><span class="glyphicon glyphicon-log-out"></span> Back </button>
    </div>
<div id="preloader">
        <div class="caviar-load"></div>
</div>

</body>
   
	<!-- Page Functions -->
	<script src="js/direct_rcv_b.js"></script>
    <!-- Jquery-2.2.4 js -->
    <script src="../../js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="../../js/bootstrap/popper.min.js"></script>
    <!-- Bootstrap-4 js -->
    <script src="../../js/bootstrap/bootstrap.min.js"></script>
    <!-- All Plugins js -->
    <script src="../../js/others/plugins.js"></script>
    <!-- Active JS -->
    <script src="../../js/active.js"></script>
</html>