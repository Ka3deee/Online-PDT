let mssr_list  = [];

function getURLParam(param_name) {
    let url = window.location.href
    return new URL(url).searchParams.get(param_name)
}

function scanItem(ar){
    let url         = window.location.protocol +"//"+ window.location.hostname + window.location.pathname
    let params      = window.location.href.replace(url, "")
    let new_params  = "?scan" + params.replace("?", "&") +"&ar="+ ar.trim()
    let new_url     = url + new_params
    
    if (getURLParam("mssr_ref") == null) return
    window.location.href = new_url
}

function confirmMssr(){
    let formData = new FormData()
    formData.append("mssr_ref", getURLParam("mssr_ref"))
    formData.append("confirm", 0)

    let xmlHttp = new XMLHttpRequest()
    xmlHttp.onreadystatechange = function() {
        if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let data = JSON.parse(xmlHttp.responseText)
            if (data.status == "error" && !confirm(data.message)) return

            formData.append("confirm", 1)

            let xmlHttp2 = new XMLHttpRequest()
            xmlHttp2.onreadystatechange = function() {
                if(xmlHttp2.readyState == 4 && xmlHttp2.status == 200) {
                    alert(JSON.parse(xmlHttp2.responseText).message)
                }
            }
            xmlHttp2.open("POST", "../fn.php?mssr_confirm")
            xmlHttp2.send(formData)
        }
    }
    xmlHttp.open("POST", "../fn.php?mssr_confirm")
    xmlHttp.send(formData)
}


/*
|--------------------------------------------------------------------------
| START: download.php
|--------------------------------------------------------------------------
*/
function addMssrList(mssr_no){
    document.getElementById("mssr_no").select()
    document.getElementById("show_errmsg").innerHTML=""

    // CHECK IF ALREADY IN THE LIST
    for (let i = 0; i < mssr_list.length; i++) {
        if (mssr_list[i]['mssr_no'] == mssr_no) {
            document.getElementById("show_errmsg").innerHTML="Your input is already in the list"
            return
        }
    }

    mssr_list.push({mssr_no: mssr_no, td2: "", td3: ""})
    showMssrList()
}

function clearMssrList(){
    mssr_list = [];
    document.getElementById("mssr_no").select()
    document.getElementById("show_errmsg").innerHTML = ""
    document.getElementById("mssrList").innerHTML = "<tr><td colspan='3'></td></tr>"
}

function showMssrList(){
    let list = ""
    mssr_list.forEach(element => {
        list += "<tr>"
        list += "<td>"+ element.mssr_no +"</td>"
        list += "<td>"+ element.td2 +"</td>"
        list += "</tr>"
    });

    document.getElementById("mssrList").innerHTML = list
}

function download(){
    if (mssr_list.length == 0) {
        alert("List is empty")
        return
    }
    if (!confirm("Are you sure?")) return

    // Referance: https://stackoverflow.com/a/30414515/18159572
    let formData = new FormData()
    formData.append("mssr_list", JSON.stringify(mssr_list))

    let xmlHttp = new XMLHttpRequest()
    xmlHttp.onreadystatechange = function() {
        if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            mssr_list = JSON.parse(xmlHttp.responseText)
            showMssrList()
        }
    }
    xmlHttp.open("POST", "../fn.php?mssr_download")
    xmlHttp.send(formData)
    document.getElementById("show_errmsg").innerHTML = ""
}
/*
|--------------------------------------------------------------------------
| END: download.php
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| START: details.php
|--------------------------------------------------------------------------
*/
function selectCheckDtl(ar, details){
    let desc = "Item: "+ ar +"\nDesc: "+ details
    document.getElementById("checkDtlDesc").innerHTML = desc
}
/*
|--------------------------------------------------------------------------
| END: details.php
|--------------------------------------------------------------------------
*/