$(document).ready(function(){

    //FOR ADDING NEW ENTRY
    $('#add').on('click',function(){
        let trf = $('#trf').val();

        //console.log(trf);

        if (trf === ""){
            alert("Please enter TRF#");
        }
        else
        {
            let newElem = '<div id="div'+trf+'" class="row trfs" style="margin-bottom:10px;"><div class="col-8"><input type="text" id="trf'+trf+'" name="trf[]" class="form-control" value="'+trf+'" readonly/></div><div class="col-4"><button id="'+trf+'" class="btn btn-danger btn-sm del_entry">Remove</button></div></div>';


            var counter = $('#counter').val();
    
            $("#dynamichere").append(newElem);
    
    
            counter = Number(counter) + 1;
    
            $("#trf").val("");
            $('#counter').val(counter);
        }
    });


    //FOR REMOVING ENTRY
    $('#trf_form').on('click','.del_entry',function(){
        var counter = $('#counter').val();
        let trf = $(this).attr("id");
        $("#div"+trf).remove();
        counter = counter - 1;
        $('#counter').val(counter);
    });


    $('#download').on('click',function(){
        var counter = $('#counter').val();
        if(counter < 1)
        {
            alert("Please enter TRF#. Thank you!");
        }
        
    });
});



//TO DOWDLOAD =================================================
$(document).ready(function(){
    $('#downloadform').submit(function(e){

        e.preventDefault();

        $('#download_progressbar').css("width","0%");
        $('#download_progressbar').attr('aria-valuenow','0');
        $('#download_progressbar').html('0%');
        $('.divbtn').hide();
        $('#notif').html("PLEASE WAIT...SYSTEM IS DOWNLOADING DATA FROM MMS");
        

        var values = $("input[name='trf[]']").map(function(){return $(this).val();}).get();
        var ref = $('#refdownload').val();

        var progressbar_value = 0;

        $.each(values, function( index, value ) {
            que = index + 1;
            //console.log( index + ": " + value );
            //console.log(Math.round(que / values.length * 100));
            
            $.ajax({
                type    : "POST",
                url     : "Functions/fx_download.php",
                data    : {'trf':value,'ref': ref},
                success : function(response)
                {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success == "1")
                    {
                        progressbar_value = Math.round(que / values.length * 100);
                        console.log(progressbar_value);
                        //to update progressbar
                        $('#download_progressbar').css("width",progressbar_value+"%");
                        $('#download_progressbar').attr('aria-valuenow',progressbar_value);
                        $('#download_progressbar').html(progressbar_value+'% - SUCCESSFULLY DONE!');
                        //remove all inputs
                        $('#div'+jsonData.trf).remove();
                        $('#notif').html("");
                        $('.divbtn').show();
                    }
                    else if(jsonData.success == "3"){
                        let newEle = 'TRF # '+jsonData.trf+'..already downloaded......<br>';
                        $('#errlog').append(newEle);
                        $('#notif').html("");
                        $('.divbtn').show();
                    }
                    else
                    {
                        let newEle = 'TRF # '+jsonData.trf+'..not found in the MMS......<br>';
                        $('#errlog').append(newEle);
                        $('#notif').html("");
                        $('.divbtn').show();
                    }
                }
            });
            
        });
    });
});
//=============================================================



