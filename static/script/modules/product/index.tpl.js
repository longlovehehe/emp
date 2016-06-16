$("div.content").delegate("#del", "click", function () {
        var id = $(this).attr("data");
        $("#dialog-confirm").dialog({
                resizable: false,
                height: 180,
                modal: true,
                buttons: {
                        "删除": function () {
                                $(this).dialog("close");
                                notice("正在删除");
                                $.ajax({
                                        url: "?modules=product&action=p_del",
                                        data: "id=" + id,
                                        dataType: "json",
                                        success: function (result) {
                                                notice(result.msg);
                                                send();
                                        }
                                });
                        },
                        "取消": function () {
                                $(this).dialog("close");
                        }
                }
        });
});