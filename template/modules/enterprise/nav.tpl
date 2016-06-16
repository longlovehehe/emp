{*
{strip}
<div class="toolbar e_view clear">
    <div class="mask_e_view"></div>
    <div class="ListIcon autoactive" action="view">
        <div class="Icon icon1 _1_jpg"></div>
        <div class="ListIconItem">
            <h3><a href="?m=enterprise&amp;a=view&amp;e_id={$data.e_id}">{"企业信息"|L}</a></h3>
            <p>{"查看企业信息、启用停用企业，以及数据同步重建"|L}</p>
        </div>
    </div>
    <div class="ListIcon autoactive" action="admins">
        <div class="Icon icon2 _3_jpg"></div>
        <div class="ListIconItem ">
            <h3><a href="?m=enterprise&amp;a=admins&amp;e_id={$data.e_id}">{"企业管理员"|L}</a></h3>
            <p>{"查看当前选择的企业的管理员，创建删除等"|L}</p>
        </div>
    </div>
    <div class="ListIcon autoactive" action="users">
        <div class="Icon icon3  _2_jpg "></div>
        <div class="ListIconItem">
            <h3><a href="?m=enterprise&amp;a=users&amp;e_id={$data.e_id}" >{"企业用户"|L}</a></h3>
            <p>{"批量新增删除，修改转移企业用户，企业用户群组分配等"|L}</p>
        </div>
    </div>
    <div class="ListIcon autoactive"  action="groups">
        <div class="Icon icon4 _4_jpg"></div>
        <div class="ListIconItem ">
            <h3><a href="?m=enterprise&amp;a=groups&amp;e_id={$data.e_id}" >{"企业群组"|L}</a></h3>
            <p>{"企业群组配置删除等"|L}</p>
        </div>
    </div>
    <div class="ListIcon autoactive "  action="usergroup">
        <div class="Icon icon5 _5_jpg"></div>
        <div class="ListIconItem ">
            <h3><a href="?m=enterprise&amp;a=usergroup&amp;e_id={$data.e_id}">{"企业部门"|L}</a></h3>
            <p>{"企业部门分配创建等"|L}</p>
        </div>
    </div>
    <div class="ListIcon autoactive  none "  action="export">
        <div class="Icon icon6 _6_jpg"></div>
        <div class="ListIconItem">
            <h3><a href="?m=enterprise&amp;a=export&amp;e_id={$data.e_id}">{"导入导出"|L}</a></h3>
            <p>{"导入导出企业用户、群组、通讯录、区域等信息"|L}</p>
        </div>
    </div>
    {if $ep.e_sync != "0" && $ep.e_status == "1"}
    <div id='sync' class="ListIcon"  action="export">
        <div class="Icon icon6 _6_jpg"></div>
        <div class="ListIconItem">
            <h3><a>{"数据同步"|L}</a></h3>
            <p>{"下发设备信息至服务器"|L}</p>
        </div>
    </div>
    <div id="dialog-confirm-sync" class="hide" title="{操作开始确认提示|L}">
        <p>{"开始同步吗"|L}？</p>
    </div>
    <script  {'type="ready"'}>
        $("#sync").click(function () {
        $("#dialog-confirm-sync").dialog({
        resizable: false,
                width: 440,
                height: 240,
                modal: true,
                buttons: {
                "{"开始同步"|L}": function () {
                notice("{"正在同步中，请稍候"|L}");
                        $(this).dialog("close");
                        $.ajax({
                        url: "?modules=enterprise&action=sync&e_id={$ep.e_id}",
                                dataType: "json",
                                success: function (result) {
                                notice(result.msg, "?m=enterprise&a=view&e_id={$ep.e_id}");
                                        /*
                                         * window.location.reload();

                                         notice(result.msg);
                                         setTimeout(function() {
                                         window.location.reload();
                                         }, 1999);
                                         */
                                }
                        });
                },
                        "{"取消"|L}": function () {
                        $(this).dialog("close");
                        }
                }
        });
        });
                $("div.autoactive").click(function () {
        var href = $(this).find("a").attr("href");
                window.location.href = href;
        });    </script>
    {/if}
    <script>
                $("div.autoactive").click(function () {
        var href = $(this).find("a").attr("href");
                window.location.href = href;
        });
    </script>
</div>
{/strip}
*}