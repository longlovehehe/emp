var request = eval($("span.request").text());
var request = request[0];
var e_id = request.e_id;
$("div.autoactive[action=users]").addClass("active");
$("li").click(function () {
    $(this).addClass("selecthover").siblings().removeClass("selecthover");
    var pg_num = $(".selecthover a").attr("pg_number");
    var url = "?m=enterprise&a=groups_edit&e_id=" + e_id + "&pg_number=" + pg_num;
    $("#edit_pg").attr("href", url);
    $("div.autoactive[action=groups]").addClass("active");
}).hover(function () {
    var val = $(this).attr("class");
    /*var array = val.split(" ");*/
    if (val.indexOf("selecthover") >= 0) {
        return false;
    }
    $(this).addClass("lihover");
}, function () {
    $(this).removeClass("lihover");
});

function getinfo(obj) {
    $("#num").html(0);
    $("a.addmore").removeClass("none");
    $(".parent_node").removeClass("selecthover");

    var url = "?m=enterprise&a=groups_item_pguser&pg_number=" + obj;
    $("input[name=pg_number]").val(obj);
    $("input[name=page]").val(0);
    $("input[name=action]").val('groups_item_pguser');
    $.ajax({
        url: "?m=enterprise&a=groups_option&e_id=" + e_id,
        success: function (result) {
            $("#e_select").empty();
            var option = "<option id='clear_pg'  value='0'>从当前组移除</option>";
            option += "<option id = 'save_pg'  value = " + obj + " selected = 'selected' > 保留群组信息 </option>";
            option += result;
            $("#e_select").html(option);
            /*$("#e_select").prepend("<option id='clear_pg'  value='0'>从当前组移除</option>");
             $("#e_select").prepend("<option id='save_pg'  value=" + obj + " selected='selected'>保留群组信息</option>");*/
        }
    });

    $("tr.head").html("<th style='display:inline;padding: 0px ;'><div style='width:60px;'><input style='margin-left: 3px;' autocomplete='off'  type='checkbox' id='checkall' />全选</div></th> <th><div style='width:120px;'>姓名</div></th> <th><div style='width:100px;'>号码</div></th> <th><div style='width:65px;'>级别</div></th> <th><div style='width:65px;'>默认组</div></th> <th><div style='width:90px;'>部门</div></th>");

    $("#edit_pg").removeClass("none");
    $("#del_pg").removeClass("none");
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'html',
        success: function (result) {
            $.ajax({
                url: "?m=enterprise&a=getgpnum&pg_number=" + obj,
                method: "GET",
                dataType: 'html',
                success: function (result) {
                    $("#ninfo").html(result);
                    if (Math.floor(result / 15) >= 1) {
                        $("a.addmore").removeClass("none");
                    } else {
                        $("a.addmore").addClass("none");
                    }
                }
            });
            $("input[name=pg_number]").val(obj);
            $("#gettrig").empty();
            $("form").attr('action', url);
            send();
            /*$("#gettrig").html(result);*/
        }
    });

}
function del_pg() {
    confirm('确定要删除此群组?');
    if (con == "取消") {
        return false;
    } else {
        var pg_num = $(".selecthover a").attr("pg_number");
        $.ajax({
            url: "?modules=enterprise&action=groups_del&e_id=" + e_id + "&list=" + pg_num,
            dataType: "json",
            method: "POST",
            success: function () {
                notice("群组删除成功", "?m=enterprise&a=groups&e_id=" + e_id);
                send();
            }
        });
    }
}
var init = close;
function getindex(obj) {

    $("#gettrig").empty();
    $("#num").html(0);
    $("#clear_pg").remove();
    $("#save_pg").remove();
    $("a.addmore").removeClass("none");
    $(".li_select").removeClass("selecthover");
    if (init == open) {
        $("#child_node").removeClass("none");
        $(".parent_node").css("background", "url(images/close.png) 4px 8px no-repeat");
        init = close;
    } else {
        $("#child_node").addClass("none");
        $(".parent_node").css("background", "url(images/open.png) 4px 8px no-repeat");
        init = open;
    }


    $.ajax({
        url: "?m=enterprise&a=groups_option&e_id=" + e_id,
        success: function (result) {
            var option = "<option value='' selected='selected'>请选择群组</option>";
            option += result;
            $("#e_select").empty();
            $("#e_select").html(option);
        }
    });
    var url = "?m=enterprise&a=groups_item&e_id=" + obj;
    $("#edit_pg").addClass("none");
    $("#del_pg").addClass("none");
    $("input[name=page]").val(0);
    $("tr.head").html("<th style='display:inline;padding: 0px ;'><div style='width:60px;'><input style='margin-left: 3px;' autocomplete='off'  type='checkbox' id='checkall' />全选</div></th> <th><div style='width:120px;'>姓名</div></th> <th><div style='width:100px;'>号码</div></th> <th><div style='width:130px;'>所属群组</div></th> <th><div style='width:100px;'>部门</div></th>");
    $.ajax({
        url: url,
        method: "GET",
        dataType: 'html',
        success: function (result) {

            $.ajax({
                url: "?m=enterprise&a=getugnum",
                method: "GET",
                dataType: 'html',
                success: function (result) {
                    $("#ninfo").html(result);
                    if (Math.floor(result / 15) > 1) {
                        $("a.addmore").removeClass("none");
                    } else {
                        $("a.addmore").addClass("none");
                    }
                }
            });
            $("input[name=action]").val('groups_item');

            $("form").attr('action', url);
            send();
            /*$("#gettrig").html(result);*/
        }
    });
}

$("#groups_move_all").click(function () {
    var checkd = "";
    var move_u_default_pg = $("select[name=move_u_default_pg]").val();
    $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
    });
    var pg_num = $("input[name=pg_number]").val();
    if (checkd === "") {
        notice("未选中任何企业用户");
    } else if (move_u_default_pg == "") {
        notice("未选中转移群组");
    } else if ($("form.move_u_default_pg").valid()) {
        var data = $("form.move_u_default_pg").serialize() + "&" + $("form.data").serialize();
        $("input[name=u_number]").val("");
        $("select[name=u_product_id]").val("");
        $("select[name=u_default_pg]").val("");
        $("select[name=u_ug_id]").val("");
        $("select[name=u_pic]").val("");
        $("select[name=u_sex]").val("");
        $("input[name=u_sex]").val("");
        $("input[name=u_sex]").val("");
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
                $(".submit").trigger('click');
                var num = -2;
                $("ul li a").each(function () {
                    var pgnum = $(this).attr("pg_number");
                    if ($("ul li a").attr("pg_number") == $("select[name=move_u_default_pg]").val()) {
                        pgnum = $("select[name=move_u_default_pg]").val();
                    }
                    num++;
                    $.ajax({
                        url: "?m=enterprise&a=getgpnum&pg_number=" + pgnum,
                        method: "GET",
                        dataType: 'html',
                        success: function (result) {
                            var a = new Array();
                            if ($("select[name=move_u_default_pg]").val() == 0) {
                                if (pgnum == pg_num) {
                                    $("ul li.selecthover a span.getnum").html(result);
                                }
                            } else {
                                if (pgnum == $("select[name=move_u_default_pg]").val()) {
                                    $("ul li a span.getnum").eq(num).html(result);
                                }
                            }
                        }
                    });
                });
                send();
            }
        });
    }
});

function new_creat() {
    $(".c_dir").html('新建群组');
    $("input[name='pg_name']").val('');
    $("input[name='pg_number']").val('');
    document.getElementById('light').style.display = 'block';
    document.getElementById('fade').style.display = 'block';
}
/**
 * Comment
 */
function do_set() {
    if (getnumval() === false) {
        return false;
    }
    if (getnamval() === false) {
        return false;
    } else {
        $.ajax({
            url: "?modules=enterprise&action=groups_save_v2",
            method: "POST",
            dataType: 'json',
            data: {pg_number: $("input.get_pg_number").val(), pg_name: $("input[name='pg_name']").val(), pg_level: $("input[name='pg_level']").val(), pg_grp_idle: $("input[name='pg_grp_idle']").val(), pg_speak_idle: $("input[name='pg_speak_idle']").val(), pg_speak_total: $("input[name='pg_speak_total']").val(), pg_queue_len: $("input[name='pg_queue_len']").val(), pg_chk_stat_int: $("input[name='pg_chk_stat_int']").val(), pg_buf_size: $("input[name='pg_buf_size']").val(), pg_record_mode: $("input[name='pg_record_mode']").val()},
            success: function (result) {
                $("div.autoactive[action=groups]").addClass("active");
                notice(result.msg, '?m=enterprise&a=groups');
                /*location.reload();*/
            }
        });
        document.getElementById('light').style.display = 'none';
        document.getElementById('fade').style.display = 'none';
    }
}
function closed() {
    document.getElementById('light').style.display = 'none';
    document.getElementById('fade').style.display = 'none';
}
function init_table() {
    $("input#checkall").removeAttr("checked");
    $("#num").text($("input.cb:checkbox:checked").length);
    if ($("input[name=page]").val() < Math.floor(Number($(".total").first().text()) / 10)) {
        $("a.addmore").removeClass("none");
    } else {
        $("a.addmore").addClass("none");
    }
}
$("a.init_button").on("click", function () {
    init_table();
    var numtotal = Number($(".total").first().text());
    if (numtotal > 0) {
        $("#ninfo").text(numtotal);
        if (Math.floor(numtotal / 10) <= 1) {
            $("a.addmore").addClass("none");
        } else if ($("input[name=page]").val() < Math.floor(numtotal / 10)) {
            $("a.addmore").removeClass("none");
        }
    } else {
        $("#ninfo").text(0);
        $("a.addmore").addClass("none");
    }
});

/**
 *
 */
function getnumval() {

    var match = /^[\d]+$/;
    var a = $("input.get_pg_number").val();

    if (a == "") {
        $("#pg_num_title").html("必须填写");
        return false;
    }
    else if (!match.test(a)) {
        $("#pg_num_title").html("只可输入数字");
        return false;
    }
    else if (a < 0 || a > 9999) {
        $("#pg_num_title").html("请输入0-9999之间的数字");
        return false;
    } else {
        $("#pg_num_title").html("");
        return true;
    }
}
/**
 * getnamval
 */
function getnamval() {
    var a = $("input.get_pg_name").val();
    if (a == "") {
        $("#pg_name_title").html("必须填写");
        return false;
    } else {
        $("#pg_name_title").html("");
        return true;
    }
}
