<?php if ($conn_m != null && $lib_name == 'mmsmrlib' || $lib_name == 'mmlciobj') { ?>
    <div class="success mb tc text-sm">Connection Successful !</div>
<?php } else if ($conn_m != null && $lib_name == 'mmsmtsml') { ?>
    <div class="success mb tc text-sm">Connected to MMS test environment</div>
<?php } else { ?>
    <div class="error mb tc text-sm">Check connection settings !</div>
<?php } ?> 