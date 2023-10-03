//TO ADD STORE =================================================
$(document).ready(function(){
    $('#addstoreform').submit(function(e){
        e.preventDefault();
        $.ajax({
            type    : "POST",
            url     : "Functions/fx_store.php",
            data    : $(this).serialize(),
            success : function(response)
            {
                var jsonData = JSON.parse(response);
                //succesfully saved
                //redirection
                //location.href = 'url?'
                //reload page
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
    });
});
//=============================================================

//TO SAVE GENERATED REFERENCE # ==============================
$(document).ready(function(){
    $('#addrefform').submit(function(e){
        e.preventDefault();
        $.ajax({
            type    : "POST",
            url     : "Functions/fx_reference.php",
            data    : $(this).serialize(),
            success : function(response)
            {
                var jsonData = JSON.parse(response);
                //succesfully saved
                //redirection
                //location.href = 'url?'
                //reload page
                if (jsonData.success == "1")
                {
                    alert('Successfully Saved.');
                    location.reload();
                }
                else
                {
                    alert('Failed to add REFERENCE');
                }
                
            }
        });
    });
});
//=================================================================

//TO SET REF===========================================

$(document).ready(function(){
    $('#setrefform').submit(function(e){
        e.preventDefault();
        $.ajax({
            type    : "POST",
            url     : "Functions/fx_reference.php",
            data    : $(this).serialize(),
            success : function(response)
            {
                var jsonData = JSON.parse(response);
                //succesfully saved
                //redirection
                //location.href = 'url?'
                //reload page
                if (jsonData.success == "1")
                {
                    //alert('Successfully set store.');
                    location.reload();
                }
                else
                {
                    alert('Failed to set reference!');
                }
                
            }
        });
    });
});

//===============================================================


//TO SET STORE====================================================
$(document).ready(function(){
    $('#setstoreform').submit(function(e){
        e.preventDefault();
        $.ajax({
            type    : "POST",
            url     : "Functions/fx_store.php",
            data    : $(this).serialize(),
            success : function(response)
            {
                var jsonData = JSON.parse(response);
                //succesfully saved
                //redirection
                //location.href = 'url?'
                //reload page
                if (jsonData.success == "1")
                {
                    //alert('Successfully set store.');
                    location.reload();
                }
                else
                {
                    alert('Failed to set store!');
                }
                
            }
        });
    });
});
//============================================================

//TO GENERATE REFERENCE NUMBER=========================
$(document).ready(function(){
    $('#generate').on('click',function(){

        String.prototype.shuffle = function () {
            var a = this.split(""),
                n = a.length;
        
            for(var i = n - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var tmp = a[i];
                a[i] = a[j];
                a[j] = tmp;
            }
            return a.join("");
        }

        let ref = "0123456789".shuffle();

        $('#generated_ref').val(ref);

    });
});
//=================================================



//TO ADD USER =================================================
$(document).ready(function(){
    $('#registerform').submit(function(e){
        var ee = $('#eenum').val();
        var pass = $('#pass').val();
        var repass = $('#repass').val();
        var mpass = $('#mpass').val();

        console.log(pass);
        console.log(repass);

        var tdata = $(this).serialize();

        if(repass != pass)
        {
            alert("ERROR: User secret key not match.");
        }
        else
        {
            
            e.preventDefault();

            $.ajax({
                type    : "POST",
                url     : "Functions/fx_validation.php",
                data    : tdata,
                success : function(response)
                {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success == "1")
                    {
                        $.ajax({
                            type    : "POST",
                            url     : "Functions/fx_register.php",
                            data    : tdata,
                            success : function(response)
                            {
                                var jsonData = JSON.parse(response);
                                if (jsonData.success == "1")
                                {
                                    alert('Successfully Registered.');
                                    location.reload();
                                }
                                else
                                {
                                    alert('Failed to register!');
                                    location.reload();
                                }
                                
                            }
                        });
                    }
                    else
                    {
                        alert('Wrong admin security key!');
                        location.reload();
                    }
                    
                }
            });
            
        }

    });
});
//=============================================================






