<?php
    if (str_contains($_SERVER['REQUEST_URI'], '.php')) header("Location: ./?download");
?>

<div class="text-left" style="margin-bottom: 5px;">
    <div style="margin-bottom: 10px;">
        <label>MSSR No.</label>
        <input type="text" class="form-control" id="mssr_no" placeholder="MSSR No" required autofocus onfocus="this.select()"
            onkeypress="javascript: if(event.keyCode == 13 && this.value.trim() != '') addMssrList(this.value)"
        >
    </div>

    <button type="button" class="btn btn-primary btn-block btn-sm" onclick="document.getElementById('mssr_no').value=''; document.getElementById('mssr_no').focus(); document.getElementById('show_errmsg').innerHTML='';">CLEAR</button>
    <button type="button" class="btn btn-primary btn-block btn-sm" disabled>PREVIOUS LIST</button>

    <div style="margin-top: 10px;">
        <label>MSSR LIST (List Count)</label>
        <table class="table table-bordered table-sm">
            <thead class="text-center">
                <th width="50%">MSSR #</th>
                <th width="50%"></th>
            </thead>
            <tbody id="mssrList">
                <tr>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <button type="button" class="btn btn-primary btn-block btn-sm" onclick="clearMssrList()">CLEAR LIST</button>
    <button type="button" class="btn btn-primary btn-block btn-sm" onclick="download()">DOWNLOAD</button>
</div>

<?php
?>