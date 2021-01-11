window.onload = function () {
    
    //指定したクラスの要素をcsに入れる
    var cs=document.getElementsByClassName("utc"); // console.log('cs:',cs);

    //csの中からIDの情報をループで取り出す 
    for (var i=0; i< cs.length;i++){ 
        idd=cs[i].getAttribute("id"); // console.log('idd:',idd);
        var utctime = document.getElementById(idd).dataset.name; // console.log('utctime:',utctime);
        var utc = new Date(utctime); // console.log('UTC:',utc);
        var offset = utc.getTimezoneOffset(); // console.log('offset:',offset);
        var hours =  parseInt(offset / -60);
        var minutes = (offset % 60) * -1;
        utc.setHours(utc.getHours() + hours,utc.getMinutes() + minutes);
        var tztime = utc.toLocaleString().slice(0,-3); // console.log('tztime:',tztime);

        document.getElementById(idd).innerHTML = tztime;
    }
}

function commentedit(params) {
    var bt_id = "bt_" + params;
    var p_id = "p_" + params;
    var id = "area_" + params;

    var msg = document.getElementById(p_id).textContent; // console.log("p:", msg);
    var input_message = '<textarea class="form-control" id="msg_' + params + '" name="modmsg">' + msg + '</textarea><div class="text-right mt-2"><input type="button" class="btn btn btn-secondary btn-sm m-1" id="btcan_' + params + '" onclick="bt_cancel(' + params + ')" value="キャンセル"><input type="submit" class="btn btn-info btn-sm m-1"  id="btedt_' + params + '" name="edit" value="更新"></div>';
    document.getElementById(id).innerHTML = input_message;

    document.getElementById(p_id).style.display = 'none';
    document.getElementById(bt_id).classList.remove("btn-outline-success");
    document.getElementById(bt_id).classList.add("btn-outline-secondary");
    document.getElementById(bt_id).disabled = true;
}

function bt_cancel(params) {
    var bt_id = "bt_" + params;
    var p_id = "p_" + params;
    // var id = "area_" + params;

    var msg_id = 'msg_' + params;
    var d_id = 'd_' + params;
    var btcan_id = 'btcan_' + params;
    var btedt_id = 'btedt_' + params;
    var tx = document.getElementById(msg_id).value; console.log("p:", tx);

    document.getElementById(msg_id).remove();
    document.getElementById(btcan_id).remove();
    document.getElementById(btedt_id).remove();
    document.getElementById(p_id).style.display = "block";

    document.getElementById(bt_id).classList.remove("btn-outline-secondary");
    document.getElementById(bt_id).classList.add("btn-outline-success");
    document.getElementById(bt_id).disabled = false;

}