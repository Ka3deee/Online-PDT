$('#selecttrf').on('select2:select', function (e) {
    // Do something
    $('#selecttrf').select2('data');
    var selected = $('#selecttrf :selected').val();

    $.ajax({
        type    : "POST",
        url     : "Functions/fx_scan.php",
        data    : {'trfid' : selected},
        success : function(response)
        {
            var arr = jQuery.parseJSON(response);
            $('#descr').html(arr[0].idescr);
            $('#skufield').val(arr[0].inumbr);
            $('#trfidfield').val(arr[0].id);
            $('#iupc').val(arr[0].iupc);
        }
    })
    
});


$(document).keyup(function(e){
    let unicode= e.which;
    //alert($('#searchtrf').serialize());
    if(unicode === 0){


        //alert($('#searchtrf').serialize());
        //alert($('#selecttrf').attr('id'));
        //var barcode = $(this).val();
        //alert(barcode);;

        $.ajax({
            type    : "POST",
            url     : "Functions/fx_scan.php",
            data    : $('#searchtrf').serialize(),
            success : function(response)
            {
                $('#selecttrf').val(null).trigger('change');
                $('#selecttrf').empty().trigger("change");                
                //alert('success');
                var arr = jQuery.parseJSON(response)
                //alert(arr.toString);
                if (arr.length < 1){
                    $('#selecttrf').val(null).trigger('change');
                    $('#selecttrf').empty().trigger("change");
                    //alert("Invalid Barcode/UPC.");
                }else{
                    //arr.length = 1 ? alert("single") : alert("many");

                    for (var i = 0; i < arr.length; i++) {
                        //console.log('index: ' + i + ', id: ' + arr[i].id + ', name: ' + arr[i].trfbch);
                        var newOption = new Option(arr[i].trfbch, arr[i].id, false, false);
                        $('#selecttrf').append(newOption).trigger('change');
                    }


                    //alert(arr.length);

                    if(arr.length > 1){
                        alert("More than 1 trf# found. Please select one.");
                        $('#selecttrf').val(null).trigger('change');
                    }else{
                        // Do something
                        $('#selecttrf').select2('data');
                        var selected = $('#selecttrf :selected').val();

                        $.ajax({
                            type    : "POST",
                            url     : "Functions/fx_scan.php",
                            data    : {'trfid' : selected},
                            success : function(response)
                            {
                                var arr = jQuery.parseJSON(response);
                                $('#descr').html(arr[0].idescr);
                                $('#skufield').val(arr[0].inumbr);
                                $('#trfidfield').val(arr[0].id);
                            }
                        })
                    }
                }
            }
        })
    }
});


$(document).ready(function(){
    $('#searchtrf').submit(function(e){
        e.preventDefault();

        //var selecttrf = $('#selecttrf');

        $.ajax({
            type    : "POST",
            url     : "Functions/fx_scan.php",
            data    : $('#searchtrf').serialize(),
            success : function(response)
            {
                $('#selecttrf').val(null).trigger('change');
                $('#selecttrf').empty().trigger("change");                
                //alert('success');
                var arr = jQuery.parseJSON(response)
                //alert(arr.toString);
                if (arr.length < 1){
                    $('#selecttrf').val(null).trigger('change');
                    $('#selecttrf').empty().trigger("change");
                    //alert("Invalid Barcode/UPC.");
                }else{
                    //arr.length = 1 ? alert("single") : alert("many");

                    for (var i = 0; i < arr.length; i++) {
                        //console.log('index: ' + i + ', id: ' + arr[i].id + ', name: ' + arr[i].trfbch);
                        var newOption = new Option(arr[i].trfbch, arr[i].id, false, false);
                        $('#selecttrf').append(newOption).trigger('change');
                    }


                    //alert(arr.length);

                    if(arr.length > 1){
                        alert("More than 1 trf# found. Please select one.");
                        $('#selecttrf').val(null).trigger('change');
                    }else{
                        // Do something
                        $('#selecttrf').select2('data');
                        var selected = $('#selecttrf :selected').val();

                        $.ajax({
                            type    : "POST",
                            url     : "Functions/fx_scan.php",
                            data    : {'trfid' : selected},
                            success : function(response)
                            {
                                var arr = jQuery.parseJSON(response);
                                $('#descr').html(arr[0].idescr);
                                $('#skufield').val(arr[0].inumbr);
                                $('#trfidfield').val(arr[0].id);
                            }
                        })
                    }
                }
            }
        })
        
    });
});

//TO SET USER===========================================

$(document).ready(function(){
    $('#setuserform').submit(function(e){
        e.preventDefault();
        $.ajax({
            type    : "POST",
            url     : "Functions/fx_setuser.php",
            data    : $(this).serialize(),
            success : function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.success == "1")
                {
                    location.reload();
                }
                else
                {
                    alert('Error: Please register your User# first. Thank you!');
                }
                
            }
        });
    });
});

//===============================================================


$(document).ready(function(){
   //FOR ADDING NEW ENTRY
   $('#enter').on('click',function(){
    let barcode = $('#barcode').val();

    //console.log(trf);

    if (barcode === ""){
        alert("Please enter BARCODE");
    }
    else
    {

        //e.preventDefault();
        $.ajax({
            type    : "POST",
            url     : "Functions/fx_scan.php",
            data    : {'barcode':barcode},
            success : function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.success == "1")
                {
                    alert('Successfully Saved.');
                    location.reload();
                }
                else
                {
                    alert('Failed to add store!');
                }
                
            }
        });
    }

    });

});



//DONE SCANNING===========================================

$(document).ready(function(){
    $('#scandoneform').submit(function(e){
        var trfid = $('#trfidfield').val();
        if(trfid == ""){
            alert("PLEASE SCAN BARCODE OR SELECT TRF!");
        }else{
            e.preventDefault();
            $.ajax({
                type    : "POST",
                url     : "Functions/fx_scan.php",
                data    : $(this).serialize(),
                success : function(response)
                {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success == "1")
                    {
                        location.reload();
                    }
                    else
                    {
                        alert('Failed to saved scanned quantity!');
                    }
                    
                }
            });
        }

    });
});

//===============================================================


//EXIT SCANNING
$(document).ready(function(){
    $('#exitscanning').on('click',function(){
        $.ajax({
            type    : "POST",
            url     : "Functions/fx_scan.php",
            data    : {'exit' : 1},
            success : function(response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.success == "1")
                {
                    location.reload();
                }
                else
                {
                    alert('There\s an error occured while eciting scanning!');
                }
                
            }
        });        
    });
});