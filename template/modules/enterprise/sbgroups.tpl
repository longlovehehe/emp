{strip}
{include file="modules/enterprise/nav.tpl" }
<div class="toolbar mactoolbar">
    <a href="?m=enterprise&a=groups" class="button ">企业群组</a>
    <a href="?m=enterprise&a=sbgroups" class="button active">自建组</a>
    <a href1="?m=device&a=vcrs" class="button none">车辆管理</a>
</div>
<h2 class="title">{$title}</h2>
<div class="groupcon" style="height: 540px;">
    <div class="user-left">
        <br />
        <div >
            <div class="line" style="margin-bottom: 0px;">
                创建者：<input style="width:60px;border:0px;border-bottom: 1px solid #BFBFBF;background: transparent;" maxlength="32" autocomplete="off"  class="autosend" name="u_name" type="text" />
            </div>
            <div class="line" style="margin-bottom: 0px;float: right;">
                <a form="form" class="button submit form" style="min-width:35px; margin-right: 0px; " ><i class="icon-search"></i>查询</a>
            </div>
        </div>
        <br />
        {*<div class="e_index" onclick="getindex({$data.e_id});"><a style="display: block;"  class="usergroup " href="javascript:void(0);" >{$smarty.session.ep.e_name}</a></div>*}
        <div style="width:185px;height:350px;border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;overflow-y: auto;overflow-x: hidden">
            <ul class="user-right-list">
                <li class="selecthover parent_node" onclick="getindex({$data.e_id});"><span style="float: left;">&nbsp;&nbsp;&nbsp;企业</span><a style="width: 110px;display: block; padding-left: 55px;"  class="usergroup " href="javascript:void(0);" >{$smarty.session.ep.e_name}</a></li>
                <div id="child_node">
                    {foreach name=list item=item from=$list}
                    <li class="li_select" onclick="getinfo({$item.pg_number});"><a title="{$item.pg_name}" pg_number="{$item.pg_number}"  style="display: block;width: 160px;" class="usergroup title" href="javascript:void(0);" ><div style="padding-left: 40px;">{if mb_strlen($item.pg_name)<=12}{$item.pg_name}{else}{$item.pg_name|truncate: 12:''}... {/if}(<span class="getnum">{$item.total}</span>人)</div></a></li>
                    {/foreach}
                </div>
            </ul>

        </div>
        <div style="width:184px;height:112px;border:1px solid #CDCDCD;text-align: center;line-height: 112px;">
            {$numinfo}
        </div>

    </div>
    <div class="user-right">
        <br />
        <div class="toolbar " style="margin-bottom: 0px">
            <form action="?modules=enterprise&action=groups_item&e_id={$data.e_id}" id="form" method="get" data='{literal}{"type":"append"}{/literal}'>
                <input autocomplete="off"  name="modules" value="enterprise" type="hidden" />
                <input autocomplete="off"  name="action" value="groups_item" type="hidden" />
                <input autocomplete="off"  name="page" value="0" type="hidden" />
                <input autocomplete="off"  name="total" value="0" type="hidden" />
                <input autocomplete="off"  name="pg_number" value="{$item.pg_number}" type="hidden" />


                {*<input autocomplete="off"  name="modules" value="enterprise" type="hidden" />*}
                {*<input autocomplete="off"  name="action" value="users_item" type="hidden" />*}
                <input autocomplete="off"  name="e_id" value="{$data.e_id}" type="hidden" />
                {*<input autocomplete="off"  name="page" value="0" type="hidden" />*}

                <div class="line" style="margin-bottom: 0px">
                    姓名：<input style="width:85px;" maxlength="32" autocomplete="off"  class="autosend" name="u_name" type="text" />
                </div>
                <div class="line" style="margin-bottom: 0px">
                    号码：<input style="width:85px;" autocomplete="off"  class="autosend" name="u_number" type="text" />
                </div>
                <div class="line" style="margin-bottom: 0px">
                    部门：
                    <select name="u_ug_id" style="width: 100px;" class="autofix" action="?modules=api&action=get_groups_list&e_id={$data.e_id}" >
                        <option value="">请选择</option>
                    </select>
                </div>
                <div class="line" style="margin-bottom: 0px;float: right;">
                    <a form="form" class="button submit form" style="min-width:50px; margin-right: -10px; " ><i class="icon-search"></i>查询</a>
                </div>
            </form>

        </div>
        <br />
        {*<div class="content" id="get_userpg"></div>*}

        <div class="content">
            <form class='data'>
                <table class="base">
                    <tr class='head'>
                        <th style="display:inline;padding:0px ;"><div style="width:60px;"><input style="margin-left: 3px;" autocomplete="off"  type="checkbox" id="checkall" />全选</div></th>
                    <th><div style="width:120px;">姓名</div></th>
                    <th><div style="width:100px;">号码</div></th>
                    <th><div style="width:130px;">所属群组</div></th>
                    <th><div style="width:100px;">部门</div></th>
                    </tr>
                </table>
                <div class='tablebox newtable break_all' style="overflow-x:hidden;">
                    <table class="base full content two" id="gettrig">

                    </table>
                </div>
            </form>
        </div>
        <a class="addmore {if $num/10|string_format:'%d'<=1}none{/if}" page="0">点击加载更多...</a>
        <br />
        <div>
            共 <span id="ninfo">{$num}</span> 个 已选中 <span id="num">0</span> 个
        </div>
        <div class="toolbar none">
            <form class="move_u_default_pg ">
                <input autocomplete="off"  name="pg_number" value="{$item.pg_number}" type="hidden" />
                <div class="line">
                    <label>群组：</label>
                    <select id="e_select" name="move_u_default_pg" class="autofix" action="?m=enterprise&a=groups_option&e_id={$data.e_id}" required="true">
                        <option value="" selected='selected'>请选择群组</option>
                    </select>
                </div>

                <div class="line">
                    <label><input autocomplete="off"  name="move_u_default" type="checkbox" />设为默认组</label>
                </div>

                <div class="line" >
                    <label>级别：</label>
                    <input autocomplete="off"  name="move_u_level" value="255" range='[0,255]' />
                </div>
                <div class="line" >
                    <label><input autocomplete="off"  name="move_u_hangup" type="checkbox" />被叫挂断权限</label>
                </div>
                <div class="buttons right" style="float: right;">
                    <a id="groups_move_all" class="button ">确认操作</a>
                </div>
            </form>
        </div>
    </div>
    <h2 class="title"></h2>

</div>
<a class="init_button"></a>
<div class="none">
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
        <div  id="light" class="white_content">
            <div style="background-color:#DCE0E1;"><div style="float:left;width: 20px;">&nbsp;</div><div class="c_dir">新建目录</div></div>
            <br />
            <div class="block">
                群组号码：
                <input autocomplete="off" oninput="getnumval();" style="width: 150px;"  maxlength="32" class="get_pg_number"  name="pg_number"  range="[00000,09999]" type="text" required="true" digits="true"/>
                <label id="pg_num_title"  style="color:#A43838;">必须填写</label>
            </div>
            <br />
            <div class="block">
                群组名称：
                <input autocomplete="off" oninput="getnamval();" style="width: 150px;"  maxlength="32"  class= "get_pg_name"  name="pg_name" type="text" required="true" />
                <label id="pg_name_title"  style="color:#A43838;">必须填写</label>
            </div>

            <div class="buttons mrtop40" style="float: right;">
                <a class="button normal" onclick="do_set();">保存</a>
                <a class=" button" onclick="closed();">取消</a>
            </div>
        </div>
    </form>
</div>

<div id="dialog-confirm" class="hide" title="删除选中项？">
    <p>确定要删除选中的群组吗？</p>
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
            "删除": function () {
            $(this).dialog("close");
                    $.ajax({
                    url: "?modules=enterprise&action=groups_del&e_id={$data.e_id}",
                            data: "list=" + checkd,
                            success: function (result) {
                            notice("成功删除 " + result + " 个群组！");
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