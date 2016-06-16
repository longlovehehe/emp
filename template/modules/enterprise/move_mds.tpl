{strip}
<h2 class="title">迁移GQT-Server</h2>
<div class="info lineheight25">
    <div class="block ">
        <label class="title">企业ID：</label>
        <span>{$data.e_id}</span>
    </div>

    <div class="block ">
        <label class="title">企业名称：</label>
        <span>{$data.e_name}</span>
    </div>

    <div class="block ">
        <label class="title">所属区域：</label>
        <span>{$data.e_area|mod_area_name}</span>
    </div>
    <div class="block ">
        <label class="title">当前企业分配的用户数：</label>
        <span class="cur_e_mds_users">{$data.e_mds_users|default: 0}</span>
    </div>
    <div class="block ">
        <label class="title">当前企业分配的并发数：</label>
        <span class="cur_e_mds_call">{$data.e_mds_call|default: 0}</span>
    </div>
</div>

<form id="form" class="base mrbt10">
    <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />
    <div class="block ">
        <label class="title">原GQT-Server</label>
        <span>{$data.mds_d_ip1}</span>
    </div>
    <div class="block">
        <label class="title">企业区域：</label>
        <select name="e_area" class="autofix autoselect" action="?m=area&a=option" selected="true" data='[{ "to": "e_mds_id","field": "d_area","view":"false" }]'>
            <option value='@'>未选择</option>
        </select>
    </div>
    <div class="block ">
        <label class="title">新的GQT-Server地址</label>
        <select id="e_mds_id" name="new_mds_id" class=" long " size="10" action="?m=device&a=mds_option" selected="true"></select>
    </div>
    <div class="buttons mrtop40">
        <a id="move_mds" class="button green">迁移GQT-Server</a>
        <a href="?m=enterprise&a=view&e_id={$data.e_id}" class="button">取消</a>
    </div>
</form>
<div id="dialog-confirm" class="hide" title="操作确认">
    <p>确定要迁移吗？</p>
</div>

<script  {'type="ready"'}>
    (function () {
        var url = $("select#e_mds_id").attr("action");
        url += "&d_area=@";
        $.ajax({
            url: url,
            success: function (result) {
                $("select#e_mds_id").html(result);
            }
        });
    })();

    $("#move_mds").click(function () {
        if ($("#form").valid()) {
            $flag = false;
            var cur_e_mds_users = parseInt($(".cur_e_mds_users").text());
            var sel_e_mds_users = parseInt($("select[name=new_mds_id] option:selected").attr("d_user"));

            var cur_e_mds_call = parseInt($(".cur_e_mds_call").text());
            var sel_e_mds_call = parseInt($("select[name=new_mds_id] option:selected").attr("d_call"));

            if (cur_e_mds_users > sel_e_mds_users) {
                notice("迁移到的GQT-Server可用用户数比当前企业用户数小，无法迁移，如果没有这么多用户，请尝试减少用户数");
                $flag = true;
            }

            if (cur_e_mds_call > sel_e_mds_call) {
                notice("迁移到的GQT-Server可用并发数比当前企业并发数小，无法迁移");
                $flag = true;
            }

            if (!$flag) {
                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: 180,
                    modal: true,
                    buttons: {
                        "迁移": function () {
                            $(this).dialog("close");
                            notice("正在操作中");
                            $.ajax({
                                url: "?modules=enterprise&action=move_mds_item",
                                data: $("#form").serialize(),
                                dataType: "json",
                                success: function (result) {
                                    notice(result.msg, '?m=enterprise&a=view&e_id={$data.e_id}');
                                }
                            });
                        },
                        "取消": function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        }
    });
</script>
{/strip}
