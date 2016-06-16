{strip}
{include file="modules/enterprise/nav.tpl" }
<div class="toolbar mactoolbar">
    <a href="?m=enterprise&a=groups&e_id={$data.e_id}" class="button ">{"企业群组"|L}</a>
    <a href="?m=enterprise&a=cust_pggroup&e_id={$data.e_id}" class="button active">{"自建组"|L}</a>
    <a href1="?m=device&a=vcrs" class="button none">{"车辆管理"|L}</a>
</div>
<h2 class="title">{"{$title}"|L}</h2>
<div class="groupcon" style="height: 540px;">
    <div class="user-left">
        <br />
        <div >
            <div class="line" style="margin-bottom: 0px;">
                {"创建者"|L}：
                <select name="search_style">
                    <option value="1">{"号码"|L}</option>    
                    <option value="2">{"名字"|L}</option>    
                </select>
                <input style="width:100px;border:0px;border-bottom: 1px solid #BFBFBF;background: transparent;" maxlength="32" autocomplete="off"  class="autosend" name="c_pg_creater_num" type="text" />
            </div>
            <div class="line" style="margin-bottom: 0px;float: right;">
                <a id="seach" class="button" style="min-width:35px; margin-right: 0px; " ><i class="icon-search"></i>{"查询"|L}</a>
            </div>
        </div>
        <br />
        {*<div class="e_index" onclick="getindex({$data.e_id});"><a style="display: block;"  class="usergroup " href="javascript:void(0);" >{$smarty.session.ep.e_name}</a></div>*}
        <div style="width:185px;height:350px;border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;overflow-y: auto;overflow-x: hidden">
            <ul class="user-right-list">
                <li class="selecthover parent_node" ondblclick="aseffects();" onclick="getindex({$data.e_id});"><span style="float: left;">&nbsp;&nbsp;&nbsp;&nbsp;{"企业"|L}&nbsp;</span><a style="width: 110px;display: block; padding-left: 55px;"  class="usergroup " href="javascript:void(0);" >{$data.e_name}</a></li>
                <div id="child_node">
                    {foreach name=list item=item from=$list}
                    <li class="li_select" onclick="getinfo({$item.c_pg_number});"><a title="{$item.c_pg_name}" pg_number="{$item.c_pg_number}"  {if $item.c_pg_level eq 0}style="display: block;width: 160px;color:#A43838"{else}style="display: block;width: 160px;{/if} class="usergroup title" href="javascript:void(0);" ><div style="padding-left: 40px;">{if mb_strlen($item.c_pg_name)<=12}{$item.c_pg_name}{else}{$item.c_pg_name|truncate: 12:''}... {/if}(<span class="getnum">{$item.total}</span>)</div></a></li>
                    {/foreach}
                </div>
            </ul>

        </div>
        <div style="width:184px;height:112px;border:1px solid #CDCDCD;text-align: center;line-height: 112px;">
            {$info}
        </div>

    </div>
    <div class="user-right">
        <br />
        <div class="toolbar " style="margin-bottom: 0px">
            <form action="?modules=enterprise&action=cust_groups_item&e_id={$data.e_id}" id="form" method="get" data='{literal}{"type":"append"}{/literal}'>
                <input autocomplete="off"  name="modules" value="enterprise" type="hidden" />
                <input autocomplete="off"  name="action" value="cust_groups_item" type="hidden" />
                <input autocomplete="off"  name="page" value="0" type="hidden" />
                <input autocomplete="off"  name="total" value="0" type="hidden" />
                <input autocomplete="off"  name="c_pg_number" value="{$item.c_pg_number}" type="hidden" />


                {*<input autocomplete="off"  name="modules" value="enterprise" type="hidden" />*}
                {*<input autocomplete="off"  name="action" value="users_item" type="hidden" />*}
                <input autocomplete="off"  name="e_id" value="{$data.e_id}" type="hidden" />
                {*<input autocomplete="off"  name="page" value="0" type="hidden" />*}

                <div class="line" style="margin-bottom: 10px">
                    {"姓名"|L}：<input style="width:140px;" maxlength="32" autocomplete="off"  class="autosend" name="u_name" type="text" />
                </div>
                <div class="line" style="margin-bottom: 10px">
                    {"号码"|L}：<input style="width:140px;" autocomplete="off"  class="autosend" name="u_number" type="text" />
                </div>
                <br/>
                <div class="line" style="margin-bottom: 0px">
                    {"部门"|L}：
                    <select name="u_ug_id" style="width: 160px;" class="autofix" action="?modules=api&action=get_groups_list&e_id={$data.e_id}" >
                        <option value="">{"请选择"|L}</option>
                    </select>
                </div>
                <div class="line" style="margin-bottom: 0px">
                    {"类型"|L}：
                    <select name="u_sub_type">
                        <option value="">{"全部"|L}</option>
                        <option value="1">{"手机用户"|L}</option>
                        <option value="2">{"调度台用户"|L}</option>
                    </select>
                </div>
                <div class="line" style="margin-bottom: 0px;float: right;">
                    <a form="form" class="button submit form" style="min-width:50px; margin-right: -10px; " ><i class="icon-search"></i>{"查询"|L}</a>
                </div>
            </form>

        </div>
        <br />
        {*<div class="content" id="get_userpg"></div>*}

        <div class="content">
            <form class='data'>
                <table class="base">
                    <tr class='head'>
                        <th style="padding:0px ;"><div style="width:30px;"></div></th>
                    <th><div style="width:150px;">{"姓名"|L}</div></th>
                    <th><div style="width:100px;">{"号码"|L}</div></th>
                    <th><div style="width:130px;">{"所属群组"|L}</div></th>
                    <th><div style="width:100px;">{"部门"|L}</div></th>
                    </tr>
                </table>
                <div class='tablebox newtable break_all' style="overflow-x:hidden;">
                    <table class="base full content two" id="gettrig">

                    </table>
                </div>
            </form>
        </div>
        <br />
        {*<a class="addmore {if $num/10|string_format:'%d'<=1}none{/if} " page="0">点击加载更多...</a>*}
        <a class="getall none" onclick="getalllist();">{"点击加载全部"|L}</a>
        <br />
        <div class="block ">
            {"共"|L} <span id="ninfo">{$num}</span> {"已选中"|L} <span id="num">0</span>
        </div>
        <div class="infom none">
            <p style="color: #A43838;width: 600px;">{"注：标红的用户为自建组创建者"|L}</p>
        </div>



    </div>
    <h2 class="title"></h2>
    <a class="init_button"></a>
    <form id="form1" class="base mrbt10" name="work_form" method="post">
        <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />
        <input  value="{$data.pg_level|default:7}" range='[0,7]' name="pg_level" type="hidden" required="true" digits="true" />
        <input value="{$data.pg_grp_idle|default:600}" range='[1,1800000]' name="pg_grp_idle" type="hidden" required="true" digits="true" />
        <input  value="{$data.pg_speak_idle|default:20}" range='[1,1800000]' name="pg_speak_idle" type="hidden" required="true" digits="true" />
        <input  value="{$data.pg_speak_total|default:120}" range='[1,1800000]' name="pg_speak_total" type="hidden" required="true" digits="true" />
        <input  value="{$data.pg_queue_len|default:5}" range='[0,1800000]' name="pg_queue_len" type="hidden" required="true" digits="true" />
        <input  value="{$data.pg_chk_stat_int|default:1800}" range='[0,1800000]' name="pg_chk_stat_int" type="hidden" required="true" digits="true" />
        <input  value="{$data.pg_buf_size|default:0}" range='[0,1800000]' name="pg_buf_size" type="hidden" required="true" digits="true" />
        <input  value="2" name="pg_record_mode" type="hidden" checked="checked" />
        <div  id="light" class="white_content none">
            <div style="background-color:#DCE0E1;"><div style="float:left;width: 20px;">&nbsp;</div><div class="c_dir">新建目录</div></div>
            <br />
            <div class="block">
                {"群组号码"|L}：
                <input autocomplete="off" oninput="getnumval();" style="width: 150px;"  maxlength="32" class="get_pg_number"  name="pg_number"  range="[00000,09999]" type="text" required="true" digits="true"/>
                <label id="pg_num_title"  style="color:#A43838;">{"必须填写"|L}</label>
            </div>
            <br />
            <div class="block">
                {"群组名称"|L}：
                <input autocomplete="off" oninput="getnamval();" style="width: 150px;"  maxlength="32"  class= "get_pg_name"  name="pg_name" type="text" required="true" />
                <label id="pg_name_title"  style="color:#A43838;">{"必须填写"|L}</label>
            </div>

            <div class="buttons mrtop40" style="float: right;">
                <a class="button normal" onclick="do_set();">{"保存"|L}</a>
                <a class=" button" onclick="closed();">{"取消"|L}</a>
            </div>
        </div>
    </form>

    <div id="dialog-confirm" class="hide" title="{'删除选中项'|L}？">
        <p>{"确定要删除选中的群组吗"|L}？</p>
    </div>
    <script  {'type="ready"'}>

                $("div.autoactive[action=groups]").addClass("active");
                $("#delall").click(function () {
        var checkd = ""; $("input.cb:checkbox:checked").each(function () {
        checkd += $(this).val() + ",";
        });
                if (checkd === "") {
        notice("未选中任何群组");
        } else {
        $("#dialog-confirm").dialog({
        resizable: false,
                height: 180,
                modal: true,
                buttons: {
                "{"删除"|L}": function () {
                $(this).dialog("close");
                        $.ajax({
                        url: "?modules=enterprise&action=groups_del&e_id={$data.e_id}",
                                data: "list=" + checkd,
                                success: function (result) {
                                notice("{"成功删除"|L} " + result + " 个群组！");
                                        setTimeout(function () {
                                        send();
                                        }, 888);
                                }
                        });
                },
                        "{"取消"|L}": function () {
                        $(this).dialog("close");
                        }
                }
        });
        }
        });
    </script>
    {/strip}