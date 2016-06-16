(function () {
        var resethand = function () {
                var url = $(this).attr("data");
                $("#dialog-confirm-reset").dialog({
                        resizable: false,
                        height: 180,
                        modal: true,
                        buttons: {
                                "重置": function () {
                                        $(this).dialog("close");
                                        notice("正在重置");
                                        $.ajax({
                                                type: "post",
                                                url: url,
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
                return false;
        };

        $("div.content").delegate("a.reset", "click", resethand);
})();
$("#delall").click(function () {
        var checkd = "";

        $("input.cb:checkbox:checked").each(function () {
                checkd += $(this).val() + ",";
        });

        if (checkd === "") {
                notice("未选中任何管理员");
        } else {
                $("#dialog-confirm").dialog({
                        resizable: false,
                        height: 180,
                        modal: true,
                        buttons: {
                                "删除": function () {
                                        $(this).dialog("close");
                                        notice("正在删除");
                                        $.ajax({
                                                url: "?modules=manager&action=om_del",
                                                data: "list=" + checkd,
                                                success: function (result) {
                                                        if (result == 0) {
                                                                notice("没有管理员被删除。");
                                                        } else {
                                                                notice("成功删除" + result + "记录");
                                                        }
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