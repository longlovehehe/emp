new nicEditor({
        maxHeight: 400
}).panelInstance('content');
(function () {
        valid();
        var submitpost = function () {

                if ($("#form").valid()) {
                        var form = $("a.ajaxpost").attr("form");
                        var goto = $("a.ajaxpost1").attr("goto");
                        var url = $("#form").attr("action");
                        $("#content").val($("div.nicEdit-main").html());

                        var data = $("#form").serialize() + "&an_status=0";
                        $.ajax({
                                url: url,
                                method: "POST",
                                dataType: "json",
                                data: data,
                                success: function (result) {

                                        if (result.status == 0) {
                                                notice(result.msg, goto);
                                        } else {
                                                notice(result.msg);
                                        }
                                }
                        });
                }
        };
        $("a.ajaxpost1").bind("click", submitpost);
})();

(function () {
        valid();
        var submitpost = function () {
                if ($("#form").valid()) {
                        var goto = $("a.ajaxpost2").attr("goto");
                        var url = $("#form").attr("action");
                        $("#content").val($("div.nicEdit-main").html());
                        var data = $("#form").serialize() + "&an_status=1";
                        $.ajax({
                                url: url,
                                method: "POST",
                                dataType: "json",
                                data: data,
                                success: function (result) {
                                        if (result.status == 0) {
                                                notice(result.msg, goto);
                                        } else {
                                                notice(result.msg);
                                        }
                                }
                        });
                }
        };
        $("a.ajaxpost2").bind("click", submitpost);
})();