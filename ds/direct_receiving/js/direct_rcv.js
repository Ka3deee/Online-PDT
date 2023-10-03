function insert_ar(plate,ar_ref){
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    if(this.responseText.match(/Error.*/)){
      alert("Response: "+this.responseText);     
    }else{
      alert(this.responseText);
      document.getElementById('btnstart').disabled = true;
      document.getElementById('btnconfirm').disabled = false;
      document.getElementById('txtplates').focus();
    }			
  }
  xhttp.open("GET", "opt/mysqlpost.php?insert_ar&plate="+plate+"&ar_ref="+ar_ref);
  xhttp.send();
}
function check_complete(){
    if(document.getElementById('txtplates').value == ""){
      alert("Please Add Plate Number.. Thank you!");
    }else{
      insert_ar(document.getElementById('txtplates').value,document.getElementById('txtar_ref').value);
      window.location.href='drt_rcv_b.php?ar_ref='+document.getElementById('txtar_ref').value;
    }
}
function fn_start(){    

  //check if has existing ar_ref
  if(document.getElementById('txtar_ref').value != ""){
    if (!confirm("Generate New Ar Reference?")) return
  }
const xhttp = new XMLHttpRequest();
xhttp.onload = function() {
  if(this.responseText.match(/Error.*/)){
    alert("Response: "+this.responseText);     
  }else{
    document.getElementById('txtar_ref').value = this.responseText;
    document.getElementById('btnstart').disabled = true;
    document.getElementById('btnconfirm').disabled = false;
    document.getElementById('txtplates').focus();
  }			
}
xhttp.open("GET", "opt/mysqlpost.php?start_direct_rcv");
xhttp.send();
}
// For Testing Clear Sessions
function clearsession(){
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    window.location.href = '../';
  }
  xhttp.open("GET", "opt/mysqlpost.php?clearsession");
  xhttp.send();
} 
function confirm_ar2(){
//check if has existing ar_ref
var ar_ref = document.getElementById('txtar_ref').value;
if(ar_ref == ""){
    alert("No AR Reference, Please Generate to Continue");
    return;
}else{
    if (!confirm("Confirm AR Reference?")) return
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      alert(this.responseText);
      if(this.responseText.match(/Error.*/)){
        alert("Response: "+this.responseText);     
      }else{
        alert("Confirm Finish Succesful!");           
      }			
    }
    xhttp.open("GET", "opt/mysqlpost.php?confirmfinish&ar_ref="+ar_ref);
    xhttp.send();
    }
}
function confirm_ar(){
//check if has existing ar_ref
var ar_ref = document.getElementById('txtar_ref').value;
if(ar_ref == ""){
    alert("No AR Reference, Please Generate to Continue");
    return;
}else{
    //if (!confirm("Confirm AR Reference?")) return
	if (confirm("Receiving not finished. No PO reference yet Do you want to exit?")){
		 window.location.href = '../';
		 return;
	}else{
		return;
	}
    //const xhttp = new XMLHttpRequest();
    //xhttp.onload = function() {
    //  alert(this.responseText);
    // if(this.responseText.match(/Error.*/)){
    //    alert("Response: "+this.responseText);     
     // }else{
     //   alert("Confirm Finish Succesful!");           
     // }			
    //}
    //xhttp.open("GET", "opt/mysqlpost.php?confirmfinish&ar_ref="+ar_ref);
    //xhttp.send();
}
}