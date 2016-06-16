{strip}
        {include file="modules/enterprise/nav.tpl" }
        <h2 class="title"><span class='ellipsis2' style='max-width: 350px;height: 20px;'>{$data.e_name}</span> - {$title}</h2>

        <div class="toptoolbar">
                <a href="?m=enterprise&a=admins_add&e_id={$data.e_id}" class="button orange">新增企业管理员</a>
        </div>
        <div class="toolbar">
                <form id="form" method="post">
                        <input autocomplete="off"  name="modules" value="enterprise" type="hidden" />
                        <input autocomplete="off"  name="action" value="admins_item" type="hidden" />
                        <input autocomplete="off"  name="page" value="0" type="hidden" />
                        <input autocomplete="off"  name="e_id" value="{$data.e_id}" type="hidden" />
                        <div class="line">
                                <label>管理员帐号：</label>
                                <input autocomplete="off"  class="autosend" name="em_id" type="text" />
                        </div>
                        <div class="line">
                                <label>手机号：</label>
                                <input autocomplete="off"  class="autosend" name="em_phone" type="text" />
                        </div>

                        <div class="line">
                                <label>邮箱：</label>
                                <input autocomplete="off"  class="autosend" name="em_mail" type="text"/>
                        </div>

                        <div class="line">
                                <label>最后登录IP：</label>
                                <input autocomplete="off"  class="autosend" name="em_lastlogin_ip" type="text"/>
                        </div>

                        <div class="line">
                                <label>需要安全登录：</label>
                                <select name="em_safe_login">
                                        <option value="">未指定</option>
                                        <option value="1">需要</option>
                                        <option value="0">不需要</option>
                                </select>
                        </div>
                        <div class="line">
                                <label>最后登录时间：</label>
                                <input autocomplete="off"  class="datepicker start" name="start" type="text"  date="true"  />
                                <span>-</span>
                                <input autocomplete="off"  class="datepicker end" name="end" type="text"  date="true"  />
                        </div>

                        <a form="form" class="button submit">查询</a>
                </form>
        </div>

        <div class="toolbar">
                <a id="delall" class="button">批量删除</a>
        </div>
        <div class="content"></div>

        <div id="dialog-confirm" class="hide" title="删除选中项？">
                <p>确定要删除选中的管理员吗？</p>
        </div>
        <script {'type="ready"'}>
                $("div.autoactive[action=admins]").addClass("active");
                $("#delall").click(function () {
                        var checkd = "";

                        $("input.cb:checkbox:checked").each(function () {
                                checkd += $(this).val() + ",";
                        });

                        if (checkd === "") {
                                notice("未选中任何管理员");
                        } else {
                                $("#dialog-confirm").dialog({
                                        resizable: false,
                                        height: 180,
                                        modal: true,
                                        buttons: {
                                                "删除": function () {
                                                        $(this).dialog("close");
                                                        $.ajax({
                                                                url: "?modules=enterprise&action=admins_del&e_id={$data.e_id}",
                                                                data: "list=" + checkd,
                                                                success: function (result) {
                                                                        notice("成功删除 " + result + " 个管理员！");
                                                                        setTimeout(function () {
                                                                                send();
                                                                        }, 888);
                                                                }
                                                        });
                                                },
                                                "取消": function () {
                                                        $(this).dialog("close");
                                                }
                                        }
                                });
                        }
                });
        </script>
{/strip}