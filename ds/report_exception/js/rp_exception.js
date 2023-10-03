
function checkexc_type(value){
    if(value != ""){
      document.getElementById("exc_src").focus();
    }
}
function checkexc_source(value){
  if(value != ""){
    document.getElementById("txt_docref").disabled = false;
    document.getElementById("txt_docref").focus();
  }
}
function checkexc_doc(value){
  if(value != ""){
    document.getElementById("txt_desc").disabled = false;
    document.getElementById("txt_desc").focus();
  }else{
    alert("Please put document reference first");
    return;
  }
}
function checkexc_desc(value){
  if(value != ""){
    document.getElementById("btnconfirm").disabled = false;
    document.getElementById("btnconfirm").focus();
  }else{
    alert("Please put details on the exception");
    return;
  }
}
function confirm_exc(){
  var exc_source = document.getElementById("exc_src").value;
  var txt_docref = document.getElementById("txt_docref").value;
  var exc_type = document.getElementById("exc_type").value;
  var txt_desc = document.getElementById("txt_desc").value;
  if(upload_exc(exc_source,txt_docref,exc_type,txt_desc) == 0){
    alert("Database error. Not updated");
  }else{
    alert("Exception Successfully Updated");
    window.location.href='../';
  }
}
function upload_exc(xc_source,txt_docref,exc_type,txt_desc){
  var result = 1;
  var xhttp = new XMLHttpRequest();
    xhttp.onload = function() {           
      if(this.responseText == "0"){
        result = 0;
      }		
    }
    xhttp.open("GET", "opt/mysqlpost.php?upload_exception&xc_source="+xc_source+"&txt_docref="+txt_docref+"&exc_type="+exc_type+"&txt_desc="+txt_desc,false);
    xhttp.send();
    return result;
}
function populate_check_detail(){
          //clear List
          document.getElementById('check_List').innerHTML = "";
          //populate list
          var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {           
            if(this.responseText != "No-Data"){
              document.getElementById('check_List').innerHTML= this.responseText;
            }		
          }
          xhttp.open("GET", "opt/mysqlpost.php?getexc_details",false);
          xhttp.send();
}
function getdetails(doc_ref,exr_detail){
  //clear List
  document.getElementById('txtdescid').value = "Doc Ref: "+doc_ref+ " Details:" + exr_detail;

}
function openmodal(id){
  populate_check_detail();
  var modal = document.getElementById(id);
  modal.style.display = "block";
}
function closemodal(id){
  var modal = document.getElementById(id);
  modal.style.display = "none";
}