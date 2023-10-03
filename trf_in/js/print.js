var load = function(){
    var refid = $('.refid').val();
    $.ajax({
        type    : "POST",
        url     : "Functions/fx_print.php",
        data    : {'refid' : refid},
        success : function(response)
        {
            var arr = jQuery.parseJSON(response);

            //var progressbar_value = 0;
            
            var q = 1;
            for (var i = 0; i < arr.length; i++) {
                
                //console.log('index: ' + i + ', id: ' + arr[i].id + ', trfbch: ' + arr[i].trfbch);
                


                var id = arr[i].id;
                var trfbch = arr[i].trfbch;

                let newElement = '<div id="del'+id+'" class="form-check"><label class="form-check-label"><input type="checkbox" name="trfs" class="form-check-input trfs" value="'+id+'">'+trfbch+'</label></div>';

                $('.print_form').prepend(newElement);

            }
        }
    });
};



var load2 = function(){
    var refid = $('.refid').val();
    $.ajax({
        type    : "POST",
        url     : "Functions/fx_print.php",
        data    : {'refid' : refid},
        success : function(response)
        {
            var arr = jQuery.parseJSON(response);

            //var progressbar_value = 0;
            
            var q = 1;
            for (var i = 0; i < arr.length; i++) {
                
                //console.log('index: ' + i + ', id: ' + arr[i].id + ', trfbch: ' + arr[i].trfbch);
                


                var id = arr[i].id;
                var trfbch = arr[i].trfbch;

                let newElement = '<div id="del'+id+'" class="form-check"><label class="form-check-label"><input type="checkbox" name="dl_trfs" class="form-check-input trfs" value="'+id+'">'+trfbch+'</label></div>';

                $('.print_form').prepend(newElement);

            }
        }
    });
};



//TO ADD STORE =================================================
$(document).ready(function(){
    $('#update_form').submit(function(e){
        e.preventDefault();
        let refid = $('#refid').val();
        let printer = $('#p_ip').val();

        let trfsArray = [];
        //get the checked items
        $("input:checkbox[name=trfs]:checked").each(function(){
            trfsArray.push($(this).val());
        });

        var counter = 1;
        var progressbar_value = 0;
        for (let index = 0; index < trfsArray.length; index++) {
            //console.log(trfsArray[index]);

            $.ajax({
                type    : "POST",
                url     : "Functions/fx_print.php",
                data    : {'command':'print','ref_id' : refid, 'trf' : trfsArray[index],'pr_ip':printer},
                success : function(response)
                {

                    progressbar_value = Math.round(counter / trfsArray.length * 100);
                    $('#download_progressbar').css("width",progressbar_value+"%");
                    $('#download_progressbar').attr('aria-valuenow',progressbar_value);

                    var jsonData = JSON.parse(response);
                    if (jsonData.success == "1")
                    {
                        //alert('Successfully Scheduled to print.');
                        //location.reload();
                        //console.log('printed');
                        $('#del'+jsonData.trf).remove();
                    }
                    else
                    {
                        //alert('Failed to schedule for Print!');
                    }
                    
                }
            });            
            counter +=1;
        }
        
    });
});
//=============================================================



//TO ADD STORE =================================================
$(document).ready(function(){
    $('#download_form').submit(function(e){
        e.preventDefault();
        let x_ref = $('#x_ref').val();
        let x_strcode = $('#x_strcode').val();
        let trfsArray = [];
        var urlArray =[];
        //get the checked items
        $("input:checkbox[name=dl_trfs]:checked").each(function(){
            trfsArray.push($(this).val());
        });

        var counter = 1;
        for (let index = 0; index < trfsArray.length; index++) {
            //console.log(trfsArray[index]);
            
            $.ajax({
                type    : "POST",
                url     : "htmls/redirect.php",
                data    : {'command':'print','x_ref' : x_ref, 'trf' : trfsArray[index],'x_strcode' : x_strcode},
                success : function(response)
                {
                    var jsonData = JSON.parse(response);
                    var ref = jsonData.ref;
                    var str = jsonData.str;
                    var trf = jsonData.trf;
                    var host = jsonData.host;

                    link = host +'trf_in/htmls/download.php?x_ref='+ref+'&&trfs='+trf+'&&x_strcode='+str+'';
                    window.open(link,'_blank');
                    
                }
            });      
                  
            counter +=1;
            
        }

    });
});
//=============================================================



/*
=========================
    TO SET PRINTER IP
=========================
*/
$(document).ready(function(){
    $('#setprinter_form').submit(function(e){
        e.preventDefault();
        let printerip = $('#printerip').val();
        $.ajax({
            type    : "POST",
            url     : "Functions/fx_print.php",
            data    : {'print':'setprinter','printer_ip' : printerip},
            success : function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.success == "1")
                {
                    //alert('Successfully Scheduled to print.');
                    location.reload();
                    //load();
                    //console.log('printed');
                }
                else
                {
                    alert('Failed to set printer!');
                }
                
            }
        });
    });
});