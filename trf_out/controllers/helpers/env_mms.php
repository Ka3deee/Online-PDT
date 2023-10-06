<?php if ($conn_m != null && $db_name == 'mmsmrlib' || $db_name == 'mmlciobj') { ?>
    <div class="success mb tc">Connection Successful !</div>
<?php } else if ($conn_m != null && $db_name == 'mmsmtsml') { ?>
    <div class="success mb tc">Connected to MMS test environment</div>
<?php } else { ?>
    <div class="error mb tc">Check connection settings !</div>
<?php } ?> 