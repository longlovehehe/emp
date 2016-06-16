var request = eval($("span.request").text());
var request = request[0];

function next() {
        var step = parseInt($("input[name=step]").val());
        var u_auto_number = parseInt($("input[name=u_auto_number]").val());
        if (step < u_auto_number) {
                step++;
                $("input[name=step]").val(step);
                $("progress").attr("value", step);
                $("#u_step_text").text(step);
                $("#u_step_number_text").text(u_auto_number - step);
        }
}
$("#create").click(function () {
        if ($("#form").valid()) {
                var maxnumber = parseInt($("input[name=u_auto_pre]").val()) + parseInt($("input[name=u_auto_number]").val());
                if (maxnumber > 99999) {
                        alert("添加的用户数量超过企业总数量！");
                } else {
                        $("#form").hide(222);
                        $("progress").attr("max", parseInt($("input[name=u_auto_number]").val()));
                        $(".info_text").removeClass("hide");
                        var step = parseInt($("input[name=step]").val());
                        var u_auto_number = parseInt($("input[name=u_auto_number]").val());
                        if (step < u_auto_number) {
                                step++;
                                $("input[name=step]").val(step);
                                $("progress").attr("value", step);
                                $("#u_step_text").text(step);
                                $("#u_step_number_text").text(u_auto_number - step);
                                $("#form").submit();
                        }
                }
        }
});

(function () {
        function utypeedit(cur) {
                $('div.sw').hide();
                if (cur == "手机用户") {
                        $('div.user').show();
                }
                if (cur == '调度台用户') {
                        $('div.shelluser').show();
                        $("input[name=u_auto_config][value=0]").trigger("click");
                }
                if (cur == 'GVS用户') {
                        $('div.gvsuser').show();
                }
        }
        $("#radioset>label").bind('click', function () {
                utypeedit($(this).text());
        });
        utypeedit('手机用户');
})();