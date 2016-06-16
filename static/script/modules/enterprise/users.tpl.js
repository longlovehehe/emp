var request = eval($("span.request").text());
var request = request[0];
var e_id = request.e_id;
$("input[name=u_alarm_inform_svp_num]").keydown(function(event){
        if(event.keyCode == 13){
                return false;
        }
});

$("div.autoactive[action=users]").addClass("active");
$("a#batch_toggle").click(function () {
    $("form.move_user").hide();
    $("form.move_u_default_pg").hide();
    $("form.batch").toggle();
});
$("a#move_user").click(function () {
    $("form.batch").hide();
    $("form.move_u_default_pg").hide();
    $("form.move_user").toggle();
});
$("a#move_u_default_pg").click(function () {
    $("form.move_user").hide();
    $("form.batch").hide();
    $("form.move_u_default_pg").toggle();
});

function getnum() {
    var checkd = new Array();
    $("input.cb:checkbox:checked").each(function () {
        checkd.push($(this).val());
    });

    $("#num").html(checkd.length);
}
$("input[name=u_alarm_inform_svp_num]").bind("change", function () {
    var length=$(this).val().length;
    var u_number = $(this).val();
    $.ajax({
        url:'?modules=enterprise&action=check_number',
        data:{e_id:e_id,u_number:u_number},
        success:function(res){
            if(res=="1"&&length==11||u_number==""){
                $("#u_alarm_inform_svp_num-error").attr('class','error none');
            }
            else{
                $("#u_alarm_inform_svp_num-error").attr('class','error');
            }
        }
    });
});

$("select[name=u_alarm_inform_svp_num]").bind("change", function () {
    $("#u_alarm_inform_svp_num-error").attr('class','error none');
});
/*
 $("#delall").click(function () {
 var checkd = "";

 $("input.cb:checkbox:checked").each(function () {
 checkd += $(this).val() + ",";
 });

 if (checkd === "") {
 notice("未选中任何企业用户");
 } else {
 $("#dialog-confirm").dialog({
 resizable: false,
 height: 180,
 modal: true,
 buttons: {
 "删除": function () {
 $(this).dialog("close");
 $.ajax({
 url: "?modules=enterprise&action=users_del&e_id=" + e_id,
 data: $("form.data").serialize(),
 success: function (result) {
 notice("成功删除 " + result + " 个企业用户！");
 setTimeout(function () {
 send();
 }, 888);
 }
 });
 },
 "取消": function () {
 $(this).dialog("close");
 }
 }
 });
 }
 });

 $("#move_all").click(function () {
 var checkd = "";
 var to_e_id = $("select[name=to_e_id]").val();
 $("input.cb:checkbox:checked").each(function () {
 checkd += $(this).val() + ",";
 });
 if (checkd === "") {
 notice("未选中任何企业用户");
 } else if (to_e_id == "") {
 notice("未选中转移企业");
 } else {
 var data = "to_e_id=" + to_e_id + '&';
 data += $("form.data").serialize();
 $.ajax({
 url: "?m=enterprise&a=users_move&e_id=" + e_id,
 data: data,
 dataType: "json",
 success: function (result) {
 if (result.status == 0) {
 notice(result.msg);
 } else {
 notice(result.msg);
 }
 send();
 }
 });
 }
 });
 */
$("#groups_move_all").click(function () {
    var checkd = "";
    var move_u_default_pg = $("select[name=move_u_default_pg]").val();
    if ($("input[name=move_u_default]").is(":checked")) {
        $.ajax({
            url: "?m=enterprise&a=getimpgroups&pg_number=" + move_u_default_pg,
            method: "GET",
            dataType: 'json',
            success: function (result) {
                if (result.status == "-1") {
                    notice(result.msg);
                    exit();
                }
            }
        });
    }
    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });
    if (checkd === "") {
        notice("<%'未选中任何企业用户'|L%>");
    } else if (move_u_default_pg == "") {
        notice("<%'未选中转移群组'|L%>");
    } else if ($("form.move_u_default_pg").valid()) {
        var data = $("form.move_u_default_pg").serialize() + "&" + $("form.data").serialize();
        $("input[name=u_number]").val("");
        $("select[name=u_product_id]").val("");
        $("select[name=u_default_pg]").val("");
        $("select[name=u_ug_id]").val("");
        $("select[name=u_pic]").val("");
        $("select[name=u_sex]").val("");

        $("input[name=u_terminal_type]").val("");
        $("input[name=u_terminal_model]").val("");
        $("input[name=u_imsi]").val("");
        $("input[name=u_imei]").val("");
        $("input[name=u_iccid]").val("");
        $("input[name=u_mac]").val("");
        $("input[name=u_zm]").val("");

        $.ajax({
            url: "?m=enterprise&a=groups_users_move&e_id=" + e_id,
            data: data,
            dataType: "json",
            success: function (result) {
                notice(result.msg);
                send();
            }
        });
    }
});
/*
 $("input[name=move_u_default]").on('click', function () {
 var own = $(this);
 $('select[name=move_u_default_pg]>option').remove();
 if (own.is(":checked")) {
 $('select[name=move_u_default_pg]').addClass('autofix').attr('action', '?m=enterprise&a=groups_option&safe=true&e_id=' + e_id);
 } else {
 $('select[name=move_u_default_pg]').addClass('autofix').attr('action', '?m=enterprise&a=groups_option&e_id=' + e_id);
 }
 initFix();
 });
 */

/*
 $("#batch_submit").click(function () {
 var checkd = "";
 $("input.cb:checkbox:checked").each(function () {
 checkd += $(this).val() + ",";
 });
 if (checkd === "") {
 notice("未选中任何企业用户");
 } else {
 var data = $("form#batch").serialize() + "&" + $("form.data").serialize();
 $("#dialog-confirm-batch").dialog({
 resizable: false,
 height: 180,
 modal: true,
 buttons: {
 "更新": function () {
 $(this).dialog("close");
 $.ajax({
 url: "?modules=enterprise&action=users_batch&e_id=" + e_id,
 data: data,
 success: function () {
 send();
 }
 });
 },
 "取消": function () {
 $(this).dialog("close");
 }
 }
 });
 }
 });
 */