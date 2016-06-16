
<style type="text/css">
        .tablebox tbody
        {
                font-family: Arial,Helvetica,sans-serif;
                background-color:#FFFFFF;
                overflow:auto;
        }
        .tablescr{
                overflow-y:auto;
                border:1px solid ;
        }

</style>
{strip}
        <div class="toolbar">
                <a href="?m=product&a=index" class="button">产品管理</a>
                <a href="?m=product&a=p_function" class="button active">产品功能库</a>
        </div>
        <h2 class="title">产品功能库</h2>

        {'
        <form id="form" action="?modules=product&action=p_save" method="post" class="base mrbt10">
                <div class="toolbar">
                        <input autocomplete="off"  name="page" value="0" type="hidden" />
                        <input autocomplete="off"  type="hidden" id="sel">
                        <div class="line">
                                <label>功能名称：</label>
                                <input autocomplete="off"  class="autosend" name="pi_name" type="text" required="TRUE" />
                        </div>
                        <div class="line">
                                <label>功能编号：</label>
                                <input value="gn_" autocomplete="off"  class="autosend" name="pi_code" type="text" required="TRUE"  />
                        </div>
                        <div class="line">
                                <label>功能状态：</label>
                                <input autocomplete="off"  class="autosend" name="pi_status" type="text" required="TRUE"  />
                        </div>
                        <a form="form" class="ajaxpostr button normal">新增功能</a>
                </div>
        </form>
        '|isadmin}
        <div class="content">
                <table class="base full" id="tablebox111">
                        <tr class='head'>
                                <th width="100px">功能名称</th>
                                <th width="200px">功能编号</th>
                                <th width="100px">功能状态</th>
                        </tr>
                        {foreach name=list item=item from=$list}
                                <tr onClick="sel(this);" id="{$item.pi_id}">
                                        <td style="height: 16px">{$item.pi_name}</td>
                                        <td style="height: 16px">{$item.pi_code}</td>
                                        <td style="height: 16px">{$item.pi_status}</td>
                                </tr>
                        {/foreach}
                </table>
        </div>

        <p class="info">产品功能规则，功能编号以gn_开头，功能状态使用格式【值,描述】，每一项使用|分隔</p>
        {'
        <div id="dialog-confirm" class="hide" title="删除选中项？">
                <p>确定要删除该项吗？</p>
        </div>
        <div id="dialog-confirm-clearall" class="hide" title="清空全部？">
                <p>确定要清空全部吗？</p>
        </div>

        <div class="buttons mrtop40">
                <a class="button" onclick="del();">选中删除</a>
                <a class="button" onclick="delAll()">清空全部</a>
        </div>
        '|isadmin}
        <script>
                function sel(obj) {
                        var t = document.getElementById("tablebox111");
                        for (var i = 0; i < t.rows.length; i++) {
                                t.rows[i].style.backgroundColor = "#fff";
                        }
                        obj.style.backgroundColor = "#a9a9a9";
                        $("#sel").val(obj.id);
                }

                function del() {
                        var id = $("#sel").val();
                        $("#dialog-confirm").dialog({
                                resizable: false,
                                height: 180,
                                modal: true,
                                buttons: {
                                        "删除": function () {
                                                $(this).dialog("close");
                                                notice("正在删除");
                                                $.ajax({
                                                        url: "?modules=product&action=pro_del",
                                                        data: "id=" + id,
                                                        dataType: "json",
                                                        success: function (result1) {
                                                                if (result1.status == 0) {
                                                                        window.location.reload();
                                                                } else {
                                                                        notice(result1.msg, true);
                                                                        setTimeout(function () {
                                                                                window.location.reload();
                                                                        }, 5);
                                                                }
                                                        }
                                                });
                                        },
                                        "取消": function () {
                                                $(this).dialog("close");
                                        }
                                }
                        });
                }
                function delAll() {
                        $("#dialog-confirm-clearall").dialog({
                                resizable: false,
                                height: 180,
                                modal: true,
                                buttons: {
                                        "删除": function () {
                                                $(this).dialog("close");
                                                notice("正在删除");
                                                $.ajax({
                                                        url: "?modules=product&action=del_all",
                                                        dataType: "json",
                                                        success: function (result1) {
                                                                if (result1.status == 0) {
                                                                        window.location.reload();
                                                                } else {
                                                                        window.location.reload();
                                                                }
                                                        }
                                                });
                                        },
                                        "取消": function () {
                                                $(this).dialog("close");
                                        }
                                }
                        });
                }
        </script>
        <script  {"type='ready'"}>
        $(document).ready(function () {
                $("a.ajaxpostr").click(function () {
                        if ($("#form").valid()) {
                                var form = $("a.ajaxpostr").attr("form");
                                var url = $("#" + form).attr("action");
                                $.ajax({
                                        url: url,
                                        method: "POST",
                                        dataType: "json",
                                        data: $("#form").serialize(),
                                        success: function (result) {
                                                result;
                                                window.location.reload();
                                        }
                                });
                        }
                });
        });
</script>
{/strip}
