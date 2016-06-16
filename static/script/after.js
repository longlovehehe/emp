/*
 禁止嵌套攻击
 */
if (self != top)
{
    top.location.href = self.location.href;
}

/*
 Synchronises the given end.
 @return	.
 */

function sync(end)
{
    var request = eval($("span.request").text());
    var request = request[0];
    var e_id = request.e_id;

    $.ajax({
        url: "?m=enterprise&a=sync&e_id=" + e_id,
        dataType: "json",
        success: function (result)
        {
            if (end == 1)
            {
                window.location.href = "?m=logout";
            }
            if (end == 2)
            {
                notice(result.msg);
            }
        }
    });
}

/*
 $s the given " .logout".
 @return	.
 */

$("#logout").click(function ()
{
    sync(1);
});
$(".sync").click(function ()
{
    sync(2);
});

$("input[required]").focus(function ()
{
    $("#form").valid();
});

$("a.toggle").click(function ()
{
    var owner = $(this);
    var toggle = $("." + owner.attr("data"));
    if (owner.text() == "<%'收缩'|L%>↑")
    {
        owner.text("<%'展开'|L%>↓");
        toggle.addClass("none");
    } else
    {
        owner.text("<%'收缩'|L%>↑");
        toggle.removeClass("none");
    }
});
var request = eval($("span.request").text());
var request = request[0];
(function ()
{
    var nav = request.a;
    if (nav != "")
    {
        $("nav a." + nav).addClass("active");
    }
})();

$("div.content").delegate("select.only_show", "change", function () {
    $(this).val(1);
});
/**
*调度台号码选择其他号码
*/

$("select[name=u_alarm_inform_svp_num]").change(function(){
       $("input[name=u_alarm_inform_svp_num]").val($(this).val());
        if($("select[name=u_alarm_inform_svp_num] option:selected").val()=="@"){
               $("input[name=u_alarm_inform_svp_num]").removeClass("none");
               $("input[name=u_alarm_inform_svp_num]").val("");
            }else{
                $("input[name=u_alarm_inform_svp_num]").addClass("none");
            }
});

$("#report-jump").bind("click",function(){
    $.ajax({
        url:"?m=get_sessionid",
        success:function(res){
            sessionid=res;
        }
    });
    var url=$("#report-jump").attr('action');
$.ajax({
    url:"?m=ajaxcheck_out",
    dataType:"json",
    success:function(res){
        if(res==-1){
            layer.alert("<%'帐号长时间未操作,请重新登录'|L%>", {icon: 2,title:false,closeBtn:0},function(){
                window.location.href='?m=login';
            });

        }else{
            $.ajax({
                url:url+'/validate.php?session_id='+sessionid,
                dataType:"jsonp",
                success:function(data){
                    if(data==-2||data==-1){
                        layer.msg("账号验证不通过，请检查账号");
                    }else{
                        window.open(data); 
                    }

                }
            });
        }
    }
}); 
   
});