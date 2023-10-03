
<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
<head>
  <title>PDT Application DS : Drirect Line Inspection</title>
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
	  font-size:10pt;
	  font-weight:bold;
  }
  .btn {
    font-weight:bold;
  }
  table thead,table tbody {
    text-align:left;
    font-size:10pt;
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
	<h4 class = "fontTitle">Direct Line Inspection</h4>
        <div class="row" style = "text-align:left;">
          <!-- PO  -->
          <div class="col-xs-12">
          <label for="ex1">PO</label>
          <div>
          <input  style = "width:70%;" onkeypress="
          if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;
          if(event.key == 'Enter') 
          { 
              searchpo(document.getElementById('txtpo').value);
          }" 
          
          class="form-control pull-left input-md" id="txtpo" type="text" autofocus >
          <button style = "width:30%;font-size:1em;" type="button" class="btn btn-primary btn-block pull-right input-md" onclick = "searchpo(document.getElementById('txtpo').value)" ><span class="glyphicon glyphicon-search"></span> Search</button>
 
          </div>
          </div>

           <!-- VENDOR  -->
           <div class="col-xs-12">
          <label for="ex1">VENDOR </label>
          <input    class="form-control input-md" id="txtvendor" type="text" autofocus >
          </div>
          <input  id="txtar_ref" type="hidden"  >
          <input  id="txtpo_ref" type="hidden"  >
          <div class = 'col-xs-12'>
            <div class="table-responsive">
            <table class="table table-bordered" style = "font-size:9pt;" >
                      <thead style = "background:#35a8de;">
                      <tr>
                          <th></th>
                          <th>ORDER</th> 
                          <th>REC</th> 
                          <th>VAR</th>       
                      </tr>
                      </thead>
                      <tbody  >
                      <tr>
                          <td>LINE ITEM</td>
                          <td><input  readonly onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-md" id="txtorder_li" type="text"  >
                          </td>
                          <td><input  readonly onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-md" id="txtrec_li" type="text"  >
                          </td>
                          <td><input  readonly onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-md" id="txtvar_li" type="text"  >
                          </td>         
                      </tr>
                      <tr>
                          <td>TOTAL QTY</td>
                          <td><input  readonly  onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-md" id="txtorder_qty" type="text"  >
                          </td>
                          <td><input  readonly onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-md" id="txtrec_qty" type="text"  >
                          </td>
                          <td><input  readonly onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-md" id="txtlvar_qty" type="text"  >
                          </td>         
                      </tr>
                      
                    </tbody>
            </table>
            </div>
          </div>
          <!-- UPC / SKU  -->
          <div class="col-xs-12">
          <label for="ex1">UPC / SKU</label>
          <div>
          <input  style = "width:70%;"   onkeypress="
          if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;
          if(event.key == 'Enter') 
          { 
            searchupc(document.getElementById('txtupc').value,document.getElementById('txtpo_ref').value);
          }
          " class="form-control pull-left input-md markyellow" id="txtupc" type="text" autofocus >
          <button style = "width:30%;font-size:1em;" type="button" class="btn btn-primary btn-block pull-right input-md" onclick = "searchupc(document.getElementById('txtupc').value,document.getElementById('txtpo_ref').value)" ><span class="glyphicon glyphicon-search"></span> Search</button>
 
          </div>
          </div>

          
          <!-- DESCRIPTION  -->
          <div class="col-xs-12">
          <label for="ex1">DESCRIPTION </label>
          <input  readonly class="form-control input-md" id="txt_desc" type="text"  >
          </div>
          <!-- SKU  -->
          <div class="col-xs-6">
          <label for="ex1">SKU </label>
          <input  readonly onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-md" id="txt_sku" type="text"  >
          </div>
          <!-- REQ  -->
          <div class="col-xs-6">
          <label for="ex1">REQ </label>
          <input  readonly onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-md" id="txt_req" type="text"  >
          </div>
          <!-- TALLY  -->
          <div class="col-xs-12">
          <label for="ex1">TALLY </label>
          <input  readonly onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;" class="form-control input-md" id="txt_tally" type="text"  >
          </div>
           <!-- QTY REC  -->
           <div class="col-xs-6">
          <label for="ex1">QTY REC </label>
          <input   onkeypress="
            if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;
            if(event.key == 'Enter') 
            { 
              checkqty(this.value,document.getElementById('txtpo').value);
            }
          " class="form-control input-md markyellow" id="txt_rec" type="text"  >
          </div>
          <!-- EXP / LOT  -->
          <div class="col-xs-6" id = "exp_lot">
          <label for="ex1">EXP / LOT </label>
          <input  onchange = "setexp(this.value)" class="form-control input-md" id="txt_lot" type="date"  >
          </div>

          <!-- Buttons -->
            <div class="col-xs-6" style = "margin-top:20px;">
            <button type="button" onclick = "confirm_check(document.getElementById('txtpo_ref').value)" class="btn btn-primary  btn-block btn-md" id = "btnconfirm"><span class="glyphicon glyphicon-ok"></span> Confirm</button>
            </div>
            <div class="col-xs-6" style = "margin-top:20px;">
            <button type="button" onclick = "openmodal('modal_check');" class="btn btn-primary  btn-block btn-md" id = "btncheck"><span class="glyphicon glyphicon-ok"></span> Check</button>
            </div>

        </div>
        
        <hr>

		<button type="button" class="btn btn-primary  btn-block btn-md" onclick = "window.location.href='../'"><span class="glyphicon glyphicon-log-out"></span> RETURN</button><br>
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
      <h3 style = "text-align:center;font-weight:bold" >~ Checking Data ~</h3>
    </div>
    <div class="modal-body">
      <div class = "row">
          <div class="col-xs-12">
          <label for="ex1">PO Reference: </label>
          <input  readonly class="form-control input-md" id="txtCheckPO" type="text"  >
          </div>

          <div class="col-xs-12">
          <div class="table-responsive">
          <table class="table table-bordered" style = "font-size:8pt;" >
                    <thead>
                    <tr>
                        <th>SKU</th>
                        <th>REC</th>
                        <th>REQ</th>
                        <th>DESC</th>
                    </tr>
                    </thead>
                    <tbody id = "check_List" style ="">
                    </tbody>
          </table>
          </div>
          </div>

          <div class="col-xs-12">
          <label for="ex1">DESCRIPTION </label>
          <input  readonly class="form-control input-md" id="txtdescid" type="text"  >
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
    <script src="js/direct_line.js"></script>
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