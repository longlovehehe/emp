{strip}
<div class="toolbar ">
    <a href="?m=device&a=index" class="button active">GQT-Server管理</a>
    <a href="?m=device&a=vcr" class="button none">VCR管理</a>
    <a href="?m=device&a=vcrs" class="button none">VCR-S管理</a>
</div>
<h2 class="title">{$title}</h2>

<div class="toptoolbar">
    <a href="?m=device&a=device_add&d_type=mds" class="button orange">{"新增设备"|L}</a>
</div>
<div class="toolbar">
    <form action="?m=device&a=mds_item" id="form" method="post">
        <input autocomplete="off"  name="modules" value="device" type="hidden" />
        <input autocomplete="off"  name="action" value="mds_item" type="hidden" />
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <div class="line">
            <label>{"设备ID"|L}：</label>
            <input autocomplete="off"  class="autosend" name="d_id" type="text" />
        </div>
        <div class="line">
            <label>{"设备IP地址"|L}：</label>
            <input autocomplete="off"  class="autosend" name="d_ip1" type="text" />
        </div>

        <div class="line">
            <label>{"设备名称"|L}：</label>
            <input autocomplete="off"  class="autosend" name="d_name" type="text" />
        </div>

        <div class="line">
            <label>{"设备状态"|L}：</label>
            <select name="d_status">
                <option value="">{"全部"|L}</option>
                <option value="0">{"处理中"|L}</option>
                <option value="1">{"正常"|L}</option>
                <option value="2">{"异常"|L}</option>
            </select>
        </div>
        <div class="line">
            <label>{"设备所属区域"|L}：</label>
            <select name="d_area" class="autofix" action="?m=area&a=option">
                <option value='#'>{"全部"|L}</option>
            </select>
        </div>

        <a form="form" class="button submit">{"查询"|L}</a>
    </form>
</div>

<div class="toolbar">
    <a id="delall" class="button">{"批量删除"|L}</a>
    <a id="refreshall" data="?m=device&a=refresh"  class="button">{"批量状态刷新"|L}</a>
</div>
<div class="content"></div>

<div id="dialog-confirm" class="hide" title="{"删除选中项？"|L}">
    <p>{"确定要删除选中的设备吗？"|L}</p>
</div>
<script  {'type="ready"'}>
    $("#refreshall").click(function() {
    var checkd = "";
            var url = $(this).attr("data");
            $("input.cb:checkbox:checked").each(function() {
    checkd += $(this).val() + ",";
    });
            if (checkd === "") {
    notice("{"未选中任何项"|L}");
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
    notice("{"未选中任何项"|L}");
    } else {
    $("#dialog-confirm").dialog({
    resizable: false,
            height: 180,
            modal: true,
            buttons: {
            "{"删除"|L}": function() {
            $(this).dialog("close");
                    notice("{"正在删除"|L}");
                    $.ajax({
                    url: "?modules=device&action=mds_del",
                            data: $("form.data").serialize(),
                            success: function(result) {
                            notice("{"成功删除 "|L}" + result + " {" 台设备"|L}");
                                    setTimeout(function() {
                                    send();
                                    }, 888);
                            }
                    });
            },
                    "{"取消"|L}": function() {
                    $(this).dialog("close");
                    }
            }
    });
    }
    });
</script>
{/strip}