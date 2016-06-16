{strip}
<div class="toolbar">
        <a href="?modules=device&action=mds" class="button">GQT-Server管理</a>
        <a href="?modules=device&action=vcr" class="button active">VCR管理</a>
        <a href="?modules=device&action=vcrs" class="button none">VCR-S管理</a>
</div>
<h2 class="title">VCR 列表</h2>

<div class="toptoolbar">
        <a href="?modules=device&action=device_add&d_type=vcr" class="button orange">新增设备</a>
</div>
<div class="toolbar">
        <form action="?modules=device&action=vcr_item" id="form" method="post">
                <input autocomplete="off"  name="modules" value="device" type="hidden" />
                <input autocomplete="off"  name="action" value="vcr_item" type="hidden" />
                <input autocomplete="off"  name="page" value="0" type="hidden" />
                <div class="line">
                        <label>设备ID：</label>
                        <input autocomplete="off"  class="autosend" name="d_id" type="text" />
                </div>
                <div class="line">
                        <label>设备IP地址：</label>
                        <input autocomplete="off"  class="autosend" name="d_ip1" type="text" />
                </div>

                <div class="line">
                        <label>设备名称：</label>
                        <input autocomplete="off"  class="autosend" name="d_name" type="text" />
                </div>
                <div class="line">
                        <label>设备状态：</label>
                        <select name="d_status">
                                <option value="">全部</option>
                                <option value="0">处理中</option>
                                <option value="1">正常</option>
                                <option value="-1">异常</option>
                        </select>
                </div>
                <div class="line">
                        <label>设备所属区域：</label>
                        <select name="d_area" class="autofix" action="?m=area&a=option">
                                <option value="">全部</option>
                        </select>
                </div>

                <a form="form" class="button submit">查询</a>
        </form>
</div>

<div class="toolbar">
        <a id="delall" class="button">批量删除</a>
        <a id="refreshall" data="?p=modules/device/action/refresh"  class="button">批量状态刷新</a>
</div>
<div class="content"></div>

<div id="dialog-confirm" class="hide" title="删除选中项？">
        <p>确定要删除选中的设备吗？</p>
</div>
<script src="libs/jquery.form.js" type="text/javascript"></script>
<script src="script/common.js" type="text/javascript"></script>
<script  {'type="ready"'}>
        $("#refreshall").click(function() {
                var checkd = "";
                var url = $(this).attr("data");
                $("input.cb:checkbox:checked").each(function() {
                        checkd += $(this).val() + ",";
                });
                if (checkd === "") {
                        notice("未选中任何项");
                } else {
                        $.ajax({
                                url: url,
                                dataType: "JSON",
                                data: $("form.data").serialize(),
                                success: function(result) {
                                        notice(result.msg);
                                        setTimeout(function() {
                                                send();
                                        }, 888);
                                }
                        });
                }
        });
        $("#delall").click(function() {
                var checkd = "";

                $("input.cb:checkbox:checked").each(function() {
                        checkd += $(this).val() + ",";
                });

                if (checkd === "") {
                        notice("未选中任何设备项");
                } else {
                        $("#dialog-confirm").dialog({
                                resizable: false,
                                height: 180,
                                modal: true,
                                buttons: {
                                        "删除": function() {
                                                $(this).dialog("close");
                                                notice("正在删除");
                                                $.ajax({
                                                        url: "?modules=device&action=vcr_del",
                                                        data: "list=" + checkd,
                                                        success: function(result) {
                                                                notice("成功删除 " + result + " 台设备", 9999);
                                                                setTimeout(function() {
                                                                        send();
                                                                }, 888);
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