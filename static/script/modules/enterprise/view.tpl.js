var request = eval($("span.request").text());
var request = request[0];
var e_id = request.e_id;
$("div.autoactive[action=view]").addClass("active");
$("#stop_status").click(function () {
        notice("操作进行中");
        $.ajax({
                url: "?modules=enterprise&action=stop&e_id=" + e_id,
                dataType: "json",
                success: function (result) {
                        notice(result.msg, "?m=enterprise&a=view&e_id=" + e_id);
                }
        });
});
$("#start_status").click(function () {
        notice("操作进行中");
        $.ajax({
                url: "?modules=enterprise&action=start&e_id=" + e_id,
                dataType: "json",
                success: function (result) {
                        notice(result.msg, "?m=enterprise&a=view&e_id=" + e_id);
                }
        });
});
$("#initdb").click(function () {
        $("#dialog-confirm-warn").dialog({
                resizable: false,
                width: 440,
                height: 240,
                modal: true,
                buttons: {
                        "重建": function () {
                                notice("正在重建中，请稍候");
                                $(this).dialog("close");
                                $.ajax({
                                        url: "?modules=enterprise&action=initdb&e_id=" + e_id,
                                        dataType: "json",
                                        success: function (result) {
                                                notice(result.msg);
                                        }
                                });
                        },
                        "取消": function () {
                                $(this).dialog("close");
                        }
                }
        });
});