$.timepicker.regional['ru'] = {
    timeOnlyTitle: '',
    timeText: "<%'时间'|L%>",
    hourText: "<%'小时'|L%>",
    minuteText: "<%'分钟'|L%>",
    secondText: "<%'秒钟'|L%>",
    millisecText: '',
    timezoneText: '',
    currentText: "<%'当前时间'|L%>",
    closeText: "<%'确定'|L%>",
    timeFormat: 'HH:mm',
    amNames: ['AM', 'A'],
    pmNames: ['PM', 'P'],
    isRTL: false
};
$.timepicker.setDefaults($.timepicker.regional['ru']);

/*<%*
 * @param {type} notice 消息
 * @param {type} type 消息类型
 * 1 自动消失
 * 2 进行遮罩
 * 4 具有关闭按钮
 * 8 具有loading
 * 16 自动刷新当前页
 * @returns {undefined}
 *%>*/
function log(msg) {
    if (typeof (console.log) !== "undefined") {
        console.log(msg);
    }
}
function notice(notice, url) {
    var id = "notice_" + new Date().getTime();
    $("div.notice_mask").remove();
    $notice_mask = $("<div class='notice_mask sync '></div>");
    $notice_content = $("<div class='notice_content animated fadeIn'></div>");
    $notice = $("<div class='notice'></div>");
    $notice_mask.attr("id", id);
    $notice.html(notice);
    $notice_content.append($notice);
    $toolbar = $("<div class='toolbar'><a class='button close notransition'><%'关闭'|L%></a></div>");
    $notice_content.append($toolbar);
    $notice_mask.append($notice_content);
    $("body").append($notice_mask);
    $("#" + id + " div.notice_content").draggable({containment: "parent"});

    if (typeof (url) != 'undefined') {
        $("#" + id + " a.close").bind("click", function () {
            window.location.href = url;
        });
    } else {
        $("#" + id + " a.close").bind("click", function () {
            $("#" + id).remove();
        });
    }
    return id;
}
var con = "取消";
function confirm(notice) {
    var id = "notice_" + new Date().getTime();
    $("div.notice_mask").remove();
    $notice_mask = $("<div class='notice_mask sync '></div>");
    $notice_content = $("<div class='notice_content animated fadeIn'></div>");
    $notice = $("<div class='notice'></div>");
    $notice_mask.attr("id", id);
    $notice.html(notice);
    $notice_content.append($notice);
    $toolbar1 = $("<div style='float:right' class='toolbar'><a class='button cancel notransition'><%'取消'|L%></a></div>");
    $toolbar = $("<div  class='toolbar'><a class='button determine notransition'><%'确定'|L%></a></div>");
    $notice_content.append($toolbar1);
    $notice_content.append($toolbar);
    $notice_mask.append($notice_content);
    $("body").append($notice_mask);
    $("#" + id + " div.notice_content").draggable({containment: "parent"});


    $("#" + id + " a.determine").bind("click", function () {
        con = $("a.determine").html();
        $("#" + id).remove();
        del_pg();
    });
    $("#" + id + " a.cancel").bind("click", function () {
        con = $("a.cancel").html();
        $("#" + id).remove();
    });

    return con;
}
$.ajaxSetup({
    type: 'POST',
    async: 'FALSE',
    error: function (XMLHttpRequest) {
        if (XMLHttpRequest.status == 401) {
            notice("<%'登录异常，请重新登录'|L%>", '?m=login');
        } else {
            /*notice('服务器错误，错误信息：' + XMLHttpRequest.responseText);*/
        }
    }
});

function  valid() {
    if ($("#form").length != 0) {
        $("#form").valid();
    }
}
function send(reset) {
     if (typeof (reset) != "undefined" && reset == "prev") {
        var page = $('input[name=page]').val();
        page--;
        if (page < 0) {
            page = 0;
        }

        $('input[name=page]').val(page);
    }
    var form = $(".submit").attr("form");
    var formown = $("#" + form);
    var url = $("#" + form).attr("action");

    var option = $("#" + form).attr("data");

    if (typeof (option) == 'undefined') {
        option = {"type": "next"};
    }

    if (formown.valid()) {
        if (option['type'] == 'next') {
            $("div.content").html("");
            $("div.content").addClass("loading _301_1_gif");
        }

        $.ajax({
            url: url,
            method: "POST",
            async: true,
            data: formown.serialize(),
            success: function (result) {

                if (option['type'] == 'next') {

                    $("div.content").removeClass("loading _301_1_gif");
                    if (result != "") {
                        $("div.content").html(result);
                    }


                    (function () {
                        var cur = Number($("span.totalpages").text());
                        var pages = Number($("span.pages").text());

                        if (cur == pages) {
                            $(".page .next").addClass("lock");
                        }
                        if (cur == '1') {
                            $(".page .prev").addClass("lock");
                        }

                        /** 产生页码 */
                        var gotopage = $("<select></select");

                        for (var i = 1; i <= pages; i++) {
                            var tmp = $("<option></option>");
                            tmp.text(i);
                            tmp.attr("page", i);
                            gotopage.append(tmp);
                        }
                        gotopage.val(cur);
                        $(".page .next").after(gotopage);
                    })();
                } else {
                    if (result == "false") {
                        $(".addmore").addClass("none");
                        $("div.newtable").unbind("scroll");
                        $("a.init_button").trigger("click");
                        return;
                    }

                    if (result == "none") {
                        $(".addmore").addClass("none");
                        $("div.newtable").unbind("scroll");
                        /*notice("没有查询到任何项");*/
                        $("table.base tr").not(".head").remove();
                        $("a.init_button").trigger("click");
                        return;
                    }
                    var page = formown.find('input[name=page]');
                    if (page.val() == '0') {
                        $("table.content tr").remove();
                    }
                    $("table.content").append(result);
                    $("div.newtable").unbind("scroll");
                    $("div.newtable").bind("scroll", scr);
                    $(".addmore").removeClass("none");
                    $("a.init_button").trigger("click");
                }

                /** Tools Tips*/
                $("tr[title],a.title,td[title],.tips_title").tooltip({
                    content: function () {
                        return $(this).attr('title');
                    }
                    , track: true
                    , show: false
                    , hide: false
                });

                $("a.init_button").trigger("click");
            }
        });
    }

}

jQuery.validator.addMethod("selected", function (value, element) {

    if (value == "" || value == null || value == "@") {
        return false;
    } else {
        return true;
    }
}, "必须选择");
$.ajaxSetup({
    async: false
});
var timestamp = new Date().getTime();

function initPage() {
    $("div.content").delegate(".page .prev,.page .next", "click", function () {
        if (!$(this).hasClass("lock")) {
            var page = $(this).attr("page");
            $("input[name=page]").val(page);
            send();
        }
    });
    var pa = $("input[name=page]").val();
    if (pa > 0) {
        $(function () {
            $("input[name=page]").val(pa);
            send();
        });
    }
    $("div.content").delegate(".page select", "change", function () {
        var page = $(this).val();
        $("input[name=page]").val(Number(page) - 1);
        send();

    });
}

function initTable() {
    $("div.content").delegate("#checkall", "click", function () {
        if ($("#checkall").is(":checked")) {
            $("input.cb:not([disabled])").prop("checked", "checked");
            var checkd = [];
            $("input.cb:checkbox:checked").each(function () {
                checkd.push($(this).val());
            });
            $("#num").html(checkd.length);
        } else {
            var checkd = [];
            $("input.cb").removeAttr("checked");
            $("input.cb:checkbox:checked").each(function () {
                checkd.push($(this).val());
            });
            $("#num").html(checkd.length);
        }
    });
}

function initSubmit() {
    $(".submit").click(function () {
        $("input[name=page]").val(0);
        send();
    });
}

function autoEditInit() {
    if ($("select.autofix").length === 0) {
        autoEdit();
        $("script[type=ready]").each(function () {
            eval($(this).html());
            $(this).removeAttr("type");
        });
        valid();
    }
     if ($("div.autofix").length === 0) {
        autoEdit();
        $("script[type=ready]").each(function () {
            eval($(this).html());
            $(this).removeAttr("type");
        });
        valid();
    }
}

/*<%*autoedit*%>*/
function autoEdit() {
    $("select.autoedit").each(function () {
        var val = $(this).attr("value");
        $(this).val(val);
    });
    $("select.autoeditselect").each(function () {
        var val = eval($(this).attr("value"));
        $(this).val(val);
    });
    $("div.radioset").each(function () {
        var val = $(this).attr("value");
        if (val === "") {
            $(this).find("input").first().prop("checked", "checked");
        } else {
            $(this).find("input[value=" + val + "]").prop("checked", "checked");
        }
        $(this).buttonset();
    });
    /*<%*单选框自动选中*%>*/
    $("div.checkbox").each(function () {
        var val = $(this).attr("value");
        if (val == "1") {
            $(this).find("input[type=checkbox]").prop("checked", "checked");
        }
    });
    /*<%*多选框自动选中*%>*/
    $("div.radio").each(function () {
        var val = $(this).attr("value");
        if (val !== "") {
            $(this).find("input[value=" + val + "]").trigger("click");
        }
    });
}

function initFix() {
    $("select.autofix").each(function () {
        var url = $(this).attr("action");
        var owner = $(this);
        $.ajax({
            url: url,
            success: function (result) {
                owner.append(result);
                owner.removeClass("autofix");
                autoEditInit();
            }
        });
    });
     $("div.autofix").each(function () {
        var url = $(this).attr("action");
        var owner = $(this);
        $.ajax({
            url: url,
            success: function (result) {
                owner.append(result);
                owner.removeClass("autofix");
                autoEditInit();
            }
        });
    });
}


var scr = function () {
    var own = $(this);
    var height = $(".newtable > table").height() - own.scrollTop();
    if ((parseInt(height) - 300) < 0) {
        $("a.addmore").trigger("click");
    }
};

$("a.addmore").bind("click", function () {
    var owner = $(this);
    var page = $("input[name=page]").val();
    page++;
    $("input[name=page]").val(page);
    owner.attr("page", page);
    var pagen = $("input[name=page]").val();
    var pagem = Math.ceil(($("#ninfo").text()) / 10);
    if (pagen >= pagem) {
        $("a.addmore").addClass("none");
    }
    send();
});

/*$("a.addmore").trigger("click");*/

(function autoInit() {
    initSubmit();
    initFix();
    initPage();
    initTable();
    autoEditInit();
    $(".submit").trigger("click");
})();
(function () {
    $("input.auto_toggle").bind("click", function () {
        var url = $(this).attr("action");
        var owner = $("." + url);
        if ($(this).is(":checked")) {
            owner.show();
            $(".auto_toggle_open").attr("disabled", false);
        } else {
            owner.hide();
            $(".auto_toggle_open").attr("disabled", true);
        }
    });
})();


(function () {
    $(".auto_next_toggle").click(function () {
        $(this).next().toggle(222);
    });
})();

/**
 * 日历控件时间格式化
 * @returns {undefined}
 */
(function () {
    $("input.datepicker.start").datetimepicker({timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd"});
    $("input.datepicker.end").datetimepicker({timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd"});
    $("input.datepickers.start").datepicker({timeFormat: "HH:mm:ss",
    dateFormat: "dd/mm/yy"});
})();
(function () {
    valid();
    var submiting = function () {
        notice("<%'正在提交中，无法再次提交。如需要提交，请刷新后操作'|L%>");
    };
    var submitpost = function () {
        if ($("#form").valid()) {
            var form = $("a.ajaxpost").attr("form");
            var goto = $("a.ajaxpost").attr("goto");
            var url = $("#" + form).attr("action");
            var data = $("#form").serialize();
            $(this).unbind("click").bind("click", submiting);
            $.ajax({
                url: url,
                method: "POST",
                dataType: "json",
                data: data,
                success: function (result) {
                    if (result.status == 0) {
                        notice(result.msg, goto);
                    } else {
                        $("a.ajaxpost").bind("click", submitpost);
                        notice(result.msg);
                    }
                }
            });
        }
    };
    $("a.ajaxpost").bind("click", submitpost);
})();
(function () {
    $("a.goback").click(function () {
        var href = typeof ($(this).attr("action"));
        if (href == "undefined") {
            window.history.back();
        } else {
            location.href = $(this).attr("action");
        }
    });
})();
$("select.clickfix").each(function () {
    $(this).bind("click", function () {
        var owner = $(this);
        var url = $(this).attr("action");
        $.ajax({
            url: url,
            success: function (result) {
                owner.append(result);
                owner.unbind("click");
            }
        });
    });
});
function hereDoc(f) {
    return f.toString().replace(/^[^\/]+\/\*!?\s?/, '').replace(/\*\/[^\/]+$/, '');
}
var bug = hereDoc(function () {
    /*
     */
});
(function () {
    $(".renderjson").each(function () {
        var owner = $(this);
        var json = eval(owner.text());
        var span = $("<span></span>");
        span.text(json[0].name);
        span.attr('data', owner.text());
        owner.html(span);
        owner.removeClass('renderjson').addClass('rendered');
    });
})();
/**
 * 查看密码
 */
$("label.show_passwd").mousedown(function(){
        //$(this).val($("#password").val());
        $("#password").attr("type","text");
        $("label.show_passwd").html("<%'隐藏密码'|L%>");
});
$("label.show_passwd").mouseup (function(){
        //$(this).prev().val($("input[type=password]").val());
        $("#password").attr("type","password");
        $("label.show_passwd").html("<%'查看密码'|L%>");
});
/**
 * 密码框 延迟加载
 */
/*
$(document).ready(function(){	
    $("input[type=password]").iPass();
    if($("input[name=do]").val()=="edit"){
            $("input[name=password-0]").val("●●●●●●●●●●●●●●");
    }
});*/    
//列表页分条数显示
function clickPage(self){
    var url = $(self).parent().attr("url");
    var num = parseInt($(self).html());
    var type = $(self).parent().attr("type");
    var args = {num:num};
    switch (type) {
        case 'ent':
            args = {ent_num:num};
            break;
        case 'user':
            args = {user_num:num};
            break;
        case 'gprs':
            args = {gprs_num:num};
            break;
        case 'ter':
            args = {ter_num:num};
            break;
    }
    $("input[name=page]").val(0);
    $("input[name=num]").val(num);
    $(self).css('background','#E5E5E5');
    $(self).siblings().css('background','#CCCCCC');
    $.ajax({
        url: url,
        method: "POST",
        data: args,
        success: function (result) {
           setTimeout( function(){ send(); }, 100 );
        }
    });
}