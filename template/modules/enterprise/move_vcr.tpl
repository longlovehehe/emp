{strip}
    <div class="toolbar">
        <a href="?modules=enterprise&action=view&e_id={$data.e_id}" class="button active">企业信息</a>
        <a href="?modules=enterprise&action=admins&e_id={$data.e_id}" class="button">企业管理员</a>
        <a href="?modules=enterprise&action=users&e_id={$data.e_id}" class="button">企业用户</a>
        <a href="?modules=enterprise&action=groups&e_id={$data.e_id}" class="button">企业群组</a>
        <a href="?modules=enterprise&action=usergroup&e_id={$data.e_id}" class="button">企业通讯录</a>
        <a href="?modules=enterprise&action=export&e_id={$data.e_id}" class="button">导入导出</a>
    </div>
    <h2 class="title">迁移VCR</h2>
    <div class="info lineheight25">
        <div class="block ">
            <label class="title">企业ID</label>
            <span>{$data.e_id}</span>
        </div>

        <div class="block ">
            <label class="title">企业名称</label>
            <span>{$data.e_name}</span>
        </div>

        <div class="block ">
            <label class="title">所属区域</label>
            <span>{$data.am_name}</span>
        </div>
    </div>

    <form id="form" class="base mrbt10">
        <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />
        <div class="block ">
            <label class="title">原VCR</label>
            <span>{$data.vcr_d_ip1}</span>
        </div>
        <div class="block ">
            <label class="title">新的VCR地址</label>
            <select name="new_vcr_id" class="autofix" action="?modules=api&action=get_vcr_list" required="true">
                <option value="">未选择</option>
            </select>
        </div>
        <div class="buttons mrtop40">
            <a id="move_mds" class="button green">迁移VCR</a>
            <a class="button goback">取消</a>
        </div>
    </form>
    <div id="dialog-confirm" class="hide" title="删除选中项？">
        <p>确定要迁移吗？</p>
    </div>
    
    <script  {'type="ready"'}>
        $("#move_mds").click(function() {
            if ($("#form").valid()) {
                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: 180,
                    modal: true,
                    buttons: {
                        "迁移": function() {
                            $(this).dialog("close");
                            notice("正在操作中");
                            $.ajax({
                                url: "?modules=enterprise&action=move_vcr_item",
                                data: $("#form").serialize(),
                                dataType: "json",
                                success: function(result) {
                                    notice(result.msg,true);
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 5999);
                                }
                            });
                        },
                        "取消": function() {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        });
    </script>
{/strip}