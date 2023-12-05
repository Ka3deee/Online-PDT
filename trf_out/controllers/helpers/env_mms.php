<?php if ($conn_m != null && $lib_name == 'mmsmrlib' || $lib_name == 'mmlciobj') { ?>
    <div class="success mb tc">Connection Successful !</div>
<?php } else if ($conn_m != null && $lib_name == 'mmsmtsml') { ?>
    <div class="success mb tc">Connected to MMS test environment</div>
<?php } else { ?>
    <div class="error mb tc">Check connection settings !</div>
<?php } ?> 