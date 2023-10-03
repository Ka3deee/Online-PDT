 _queryPo();
      var starttimer = setInterval(_queryPo, 2000);
      function _queryPo() {
        //get status
        var xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
          document.getElementById('polistdata').innerHTML = this.responseText;
          getpocount();
          resumetimer();
        }
        stoptimer();
        xhttp.open("GET", "opt/mysqlpost.php?getpdtinfo");
        xhttp.send();
       
      }
      function stoptimer(){
        clearInterval(starttimer);
      }
      function resumetimer(){
        starttimer = setInterval(_queryPo, 2000);
      }
	  
      function getpocount(){
          var xhttp = new XMLHttpRequest();
          xhttp.onload = function() {
            document.getElementById('listcount').innerHTML = this.responseText;
          }
          xhttp.open("GET", "opt/mysqlpost.php?getpodata");
          xhttp.send();
      }
      function viewfn(view){
          var xhttp = new XMLHttpRequest();
          xhttp.open("GET", "opt/mysqlpost.php?viewdate="+view);
          xhttp.send();
      }
      