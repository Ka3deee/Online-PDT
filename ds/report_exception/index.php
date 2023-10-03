
<!DOCTYPE html>
<?php
session_start();

?>
<html lang="en">
<head>
  <title>PDT Application DS : Report Exception</title>
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
  .btn {
    font-weight:bold;
  }
  table thead,table tbody {
    text-align:left;
    font-size:12pt;
	  font-weight:bold;
  }
  .markyellow{
    background:#f4fc9a;
  }
  </style>
</head>  
<body >
	<div class="container-fluid text-center">
		<img src="../../resources/lcc.jpg" style="width: 90px; height: 70px;">
    <h5 class = "fontTitle" style = "font-style:italic">Welcome: <?php echo $_SESSION['wms_status_user'];?></h5>
    <h5 class = "fontTitle" style = "font-style:italic">~ REPORT EXCEPTION ~</h4>
        <div class="row" style = "text-align:left;">
           <!-- Exception Type  -->
           <div class="col-xs-12">
          <label for="ex1">Exception Type</label>
          <select class="form-control input-md "
          onchange = "checkexc_type(this.value)"
          onkeypress="
            if(event.key == 'Enter') 
            { 
              checkexc_type(this.value);
            }
          "
          id="exc_type">
            <option value = "SUNREADABLE BARCODE">UNREADABLE BARCODE</option>	
            <option value = "CBCODE NOT ON FILE">BCODE NOT ON FILE</option>
            <option value = "OVER FROM DELIVERY">OVER FROM DELIVERY</option>	
            <option value = "SHORT FROM DELIVERY">SHORT FROM DELIVERY</option>		
            <option value = "DAMAGE ITEM">DAMAGE ITEM</option>	
            <option value = "LATE DELIVERY">LATE DELIVERY</option>	
            <option value = "OTHERS">OTHERS</option>	
					</select>  
          </div>
           <!-- Source  -->
           <div class="col-xs-12">
          <label for="ex1">Source</label>
          <select class="form-control input-md" id="exc_src"
          onchange = "checkexc_source(this.value)"
          onkeypress="
            if(event.key == 'Enter') 
            { 
              checkexc_source(this.value);
            }
          "
          >
            <option value = "DIRECT DELIVERY">DIRECT DELIVERY</option>	
            <option value = "WAREHOUSE TRANSFER">WAREHOUSE TRANSFER</option>
            <option value = "STORE TRANSFER">STORE TRANSFER</option>	
					</select>  
          </div>

          <!-- Source  -->
          <div class="col-xs-12">
          <label for="ex1">Document Reference</label>
          <div>
          <input  class="form-control input-md" id="txt_docref" disabled type="text"
          onkeypress="
            if(event.key == 'Enter') 
            { 
              checkexc_doc(this.value);
            }
          "  >      
          </div>
          </div>

          
          <!-- DESCRIPTION  -->
          <div class="col-xs-12">
          <label for="ex1">Description </label>
          <input  class="form-control input-md" id="txt_desc" disabled type="text"
          onkeypress="
            if(event.key == 'Enter') 
            { 
              checkexc_desc(this.value);
            }
          "  >
          </div>
         

          <!-- Buttons -->
            <div class="col-xs-6" style = "margin-top:20px;">
            <button type="button" disabled onclick = "confirm_exc()" class="btn btn-primary  btn-block btn-md" id = "btnconfirm"><span class="glyphicon glyphicon-ok"></span> Confirm</button>
            </div>
            <div class="col-xs-6" style = "margin-top:20px;">
            <button type="button" onclick = "openmodal('modal_check');" class="btn btn-primary  btn-block btn-md" id = "btnextoday"><span class="glyphicon glyphicon-list-alt"></span> EX Today</button>
            </div>

        </div>
        
        <hr>

		<button type="button" class="btn btn-primary  btn-block btn-md" onclick = "window.location.href='../'"><span class="glyphicon glyphicon-log-out"></span> RETURN</button>
    </div>
<div id="preloader">
        <div class="caviar-load"></div>
</div>
<!-- The Modal -->
<div id="modal_check" class="modal" style = "text-align:left;padding:30px;overflow:auto;">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" onclick = "closemodal('modal_check')">&times;</span>
      <h3 style = "text-align:center;font-weight:bold">-Exception Today-</h3>
    </div>
    <div class="modal-body">
      <div class = "row">
          <div class="col-xs-12">
          <div class="table-responsive">
          <table class="table table-bordered" style = "font-size:5pt;" >
                    <thead>
                    <tr>
                        <th>EX REF</th>
                        <th>TYPE</th>
                        <th>DOC REF</th>
                        <th>DETAILS</th>
                    </tr>
                    </thead>
                    <tbody id = "check_List" style ="">
                    </tbody>
          </table>
          </div>
          </div>

          <div class="col-xs-12">
          <label for="ex1">DESCRIPTION </label>
          <input readonly class="form-control input-md" id="txtdescid" type="text"  >
          </div>

          <div class="col-xs-12" style = "margin-top:30px;">
          <button type="button" onclick = "closemodal('modal_check');" class="btn btn-primary  btn-block btn-md" id = "btncheck"><span class="glyphicon glyphicon-log-out"></span> Close</button>
           
          </div>
          <hr>
          
      </div>	 
      </div>
      
      	
    </div>
  	</div>
</div>
</body>
    <script src="js/rp_exception.js"></script>
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