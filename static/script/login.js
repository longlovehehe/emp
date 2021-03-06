/**
 * 登录自动提交
 */
/*
function loginSubmit() {
    if ($("input[name=username]").val().length === 0 || $("input[name=password]").val().length === 0) {
        $("div.tips").removeClass("none").addClass("pulse");
        $("div.login").addClass("bounce");
        $("div.tips span").text("<%'帐号或密码为空，请检查输入'|L%>");
        setTimeout(function () {
            $("div.login").removeClass("bounce");
            if ($("input[name=username]").val().length === 0) {
                $("input[name=username]").focus();
            } else {
                $("input[name=password]").focus();
            }
        }, 555);
        return;
    }
    if (!$("a.submit").hasClass("lock")) {
        $("a.submit").addClass("lock");
        $("#login1").submit();
    }
}*/
function loginSubmit() {
    if ($("input[name=username]").val().length === 0 || $("input[name=password]").val().length === 0) {
        //$("div.tips").removeClass("none").addClass("pulse");
        $("div.login").addClass("bounce");
        layer.msg("<%'帐号或密码为空，请检查输入'|L%>",{
            offset: 0,
            shift: 6
        });
        setTimeout(function () {
            $("div.login").removeClass("bounce");
            if ($("input[name=username]").val().length === 0) {
                $("input[name=username]").focus();
            } else {
                $("input[name=password]").focus();
            }
        }, 555);
        return;
    }
    if (!$("a.submit").hasClass("lock")) {
        $("a.submit").addClass("lock");
        $("#login1").submit();
    }
}
$("a.submit").click(function () {
    loginSubmit();
});

(function () {
    $("input.autosend").keydown(function (e) {
        var key = e.keyCode;
        if (key === 13) {
            loginSubmit();
        }
    });
})();

$("a.lang").bind('click', function () {
    var lang = $(this).attr('data');
    $.cookie('lang', lang, {expires: 999999});
    window.location.reload();
});

$(function () {
    if (!placeholderSupport()) {   // 判断浏览器是否支持 placeholder
        $('[placeholder]').focus(function () {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
                input.removeClass('phcolor');
                if (input.attr('placeholder') == "<%'输入密码'|L%>") {
                    $("input[name=password]").attr("type", "password");
                }
            }

        }).blur(function () {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.addClass('phcolor');
                input.val(input.attr('placeholder'));
                if (input.attr('placeholder') == "<%'输入密码'|L%>") {
                    $("input[name=password]").attr("type", "");
                }
            }
        }).blur();
    }
    ;
})
function placeholderSupport() {
    return 'placeholder' in document.createElement('input');
}