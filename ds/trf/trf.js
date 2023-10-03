let trf_list  = [];

function getURLParam(param_name) {
    let url = window.location.href
    return new URL(url).searchParams.get(param_name)
}

function scanItem(sku){
    let url         = window.location.protocol +"//"+ window.location.hostname + window.location.pathname
    let params      = window.location.href.replace(url, "")
    let new_params  = "?scan" + params.replace("?", "&") +"&sku="+ sku.trim()
    let new_url     = url + new_params
    
    if (getURLParam("trf_ref") == null) return
    window.location.href = new_url
}

function confirmTrf(){
    let formData = new FormData()
    formData.append("trf_ref", getURLParam("trf_ref"))
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
                    console.log(xmlHttp2.responseText);
                    alert(JSON.parse(xmlHttp2.responseText).message)
                }
            }
            xmlHttp2.open("POST", "../fn.php?trf_confirm")
            xmlHttp2.send(formData)
        }
    }
    xmlHttp.open("POST", "../fn.php?trf_confirm")
    xmlHttp.send(formData)
}


/*
|--------------------------------------------------------------------------
| START: download.php
|--------------------------------------------------------------------------
*/
function addTrfList(trf_no){
    document.getElementById("trf_no").select()
    document.getElementById("show_errmsg").innerHTML=""

    // CHECK IF ALREADY IN THE LIST
    for (let i = 0; i < trf_list.length; i++) {
        if (trf_list[i]['trf_no'] == trf_no) {
            document.getElementById("show_errmsg").innerHTML="Your input is already in the list"
            return
        }
    }

    trf_list.push({trf_no: trf_no, td2: "", td3: ""})
    showTrfList()
}

function clearTrfList(){
    trf_list = [];
    document.getElementById("trf_no").select()
    document.getElementById("show_errmsg").innerHTML = ""
    document.getElementById("trfList").innerHTML = "<tr><td colspan='3'></td></tr>"
}

function showTrfList(){
    let list = ""
    trf_list.forEach(element => {
        list += "<tr>"
        list += "<td>"+ element.trf_no +"</td>"
        list += "<td>"+ element.td2 +"</td>"
        list += "</tr>"
    });

    document.getElementById("trfList").innerHTML = list
}

function download(){
    if (trf_list.length == 0) {
        alert("List is empty")
        return
    }
    if (!confirm("Are you sure?")) return

    // Referance: https://stackoverflow.com/a/30414515/18159572
    let formData = new FormData()
    formData.append("trf_list", JSON.stringify(trf_list))

    let xmlHttp = new XMLHttpRequest()
    xmlHttp.onreadystatechange = function() {
        if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            trf_list = JSON.parse(xmlHttp.responseText)
            showTrfList()
        }
    }
    xmlHttp.open("POST", "../fn.php?trf_download")
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