{strip}
{include file="modules/enterprise/nav.tpl" }
<h2 class="title"><span class='ellipsis2' style='max-width: 350px;height: 20px;'>{$data.e_name|mbsubstr:20}</span> - {"企业部门"|L}</h2>

<div class="left_or_right" style="background: #EEE; height: 540px;border-bottom: 1px solid #a43838">
    {*<!-- left? -->*}
    <div class="user-left" style="background-color: #DADADA;height: 521px;border: 1px solid #CDCDCD;">
        <div class="toolbar" style="padding: 10px; margin: 0;">
            <a class="button" style="min-width:35px;margin-right: 5px;" id="create">{"添加"|L}</a>
            <a class='none' style="min-width:35px;margin-right: 5px;" id="create_peer">{"创建平行部门"|L}</a>
            <a class="button" style="min-width:35px;margin-right: 5px;" id="edit">{"编辑"|L}</a>
            <a class="button" style="min-width:35px;margin-right: 5px;" id="del">{"删除"|L}</a>
        </div>
        <div id="tree" class="none_select" style="width: 186px;height: 352px;overflow: scroll;">
            <a class="child title " ug_parent_id="0" lock="true" ><span style="float: left;">{"企业"|L}&nbsp;</span> <div style="width:110px;padding-left: 35px; height:auto;white-space:normal; word-wrap:break-word; word-break:break-all;">{$data.e_name}</div> </a>
        </div>
        <a class="button"  title="{"导出所选部门成员的所属群组列表，主要用于无屏对讲机群组旋钮所在位置的查看"|L}" id="export" style="margin: 10px 22px;">{"导出部门成员所属群组"|L}</a>
    </div>

    <div class="user-right" style="background: #EEEEEE;padding-top: 10px;">
        <div class='user_show'>
            <div class="toolbar">
                <form action="?modules=enterprise&action=users_item&e_id={$data.e_id}" id="userform" method="post" data='{literal}{"type":"append"}{/literal}'>
                    <input autocomplete="off"  name="modules" value="enterprise" type="hidden" />
                    <input autocomplete="off"  name="action" value="users_item_v2" type="hidden" />
                    <input autocomplete="off"  name="e_id" value="{$data.e_id}" type="hidden" />
                    <input autocomplete="off"  name="type" value="append" type="hidden" />
                    <input autocomplete="off"  name="page" value="0" type="hidden" />
                    <input name="search" type="hidden"/>
                    <input type="hidden" name='num' value='10' />
                    <input name='u_ug_id' type="hidden" value=""/>
                    <input name='ug_name' type="hidden" value=""/>
                    <div class="line" style="margin-bottom: 5px;">
                        {"姓名"|L}：<input style="width:155px;" autocomplete="off"  class="autosend" name="u_name" type="text" />
                    </div>
                    <div class="line" style="margin-bottom: 5px;">
                        {"号码"|L}：<input style="width:155px;" autocomplete="off"  class="autosend" name="u_number" type="text" />
                    </div>
                    <div class="line" style="margin-bottom: 0px">
                        {"类型"|L}：
                        <select name="u_sub_type">
                            <option value="">{"全部"|L}</option>
                            <option value="1">{"手机用户"|L}</option>
                            <option value="2">{"调度台用户"|L}</option>
                            <option value="3">{"GVS用户"|L}</option>
                        </select>
                    </div>
                    {*<div class="line">
                        部门：
                        <select onchange="changepg();" name="u_ug_id" style="width: 180px;" class="autofix" action="?modules=api&action=get_groups_list&e_id={$data.e_id}" >
                            <option value="">请选择</option>
                        </select>
                    </div>*}
                    <a form="userform" class="button submit userform" style="margin: 0;float: right;">{"查询"|L}</a>
                </form>
            </div>
            <div class="content " style="height: 330px;">
                <form class='data'>
                    <table class="base" >
                        <tr class='head' id="user_list">
                        <th>
                            <div style="width: 20px;"><input autocomplete="off" style="" type="checkbox" id="checkall" /></div>
                        </th>
                        <th><div style="width:145px;">{"姓名"|L}</div></th>
                        <th><div style="width:38px;">{"类型"|L}</div></th>
                        <th><div style="width:93px;">{"号码"|L}</div></th>
                        <th class="rich group "><div style="width:204px;">{"部门"|L}</div></th>
                        </tr>
                    </table>
                    <div class='tablebox newtable break_all'>
                        <table class="base full content two" ></table>
                    </div>
                </form>
            </div>
            {*<a class="addmore ">{"点击加载更多"|L}...</a>*}
            <a class="getall none" onclick="getalllist();">{"点击加载全部"|L}</a>
            <br />
            <div>
                {"共"|L} <span id="ninfo">{$num}</span> {"已选中"|L} <span id="num">0</span>
            </div>
            <br />
            <div class='user_batch'>
                <div class="toolbar ">
                    <form class="batch " id="batch" action="?modules=enterprise&action=users_item&e_id={$data.e_id}">
                        <div class="line" style="float: left;">
                            <label>{"所属部门"|L}：</label>
                            <select name="u_ug_id" class="autofix" action="?modules=api&action=get_groups_list&e_id={$data.e_id}" required="true">
                                <option value="0">{"清除部门信息"|L}</option>
                            </select>
                        </div>
                        <div class="buttons right" >
                            <a id="batch_submit" class="button">{"批量修改部门"|L}</a>
                        </div>
                    </form>

                    <form class="move_u_default_pg hide">
                        <div class="line">
                            <label>{"分配至该群组"|L}：</label>
                            <select name="move_u_default_pg" class="autofix" action="?modules=api&action=get_ptt_member_list&e_id={$data.e_id}" required="true"></select>
                        </div>
                        <div class="line">
                            <label>{"设置成员级别"|L}：</label>
                            <input autocomplete="off"  name="move_u_level" value="255" range='[0,255]' />
                        </div>
                        <div class="line">
                            <label title='默认组'><input autocomplete="off"  name="move_u_default" type="checkbox" />{"默认组"|L}</label>
                            <label><input autocomplete="off"  name="move_u_hangup" type="checkbox" />{"被叫挂断权限"|L}</label>
                        </div>
                        <a id="groups_move_all" class="button">{"批量分配"|L}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 组件 -->
<div id="dialog-confirm" class="hide" title="{'确认删除部门'|L}?">
    <p>{"请选择操作"|L}（{"警告：删除过程不可逆"|L}！）</p>
</div>
<a class="init_button"></a>
<div id="dialog-edit" class="hide" title="Title">
    <form action="?modules=enterprise&action=usergroup_save&e_id={$data.e_id}" id="form" class="form">
        <input autocomplete="off"  name="do" type="hidden" />
        <input autocomplete="off"  name="ug_id" type="hidden" />
        <input autocomplete="off"  name="ug_parent_id" type="hidden" />
        <input autocomplete="off"  name="ug_path" type="hidden"/>
        <div class="block">
            <label class="title">{"部门名称"|L}：</label>
            <input chinese="true" autocomplete="off"  name="ug_name" type="text" required="true" maxlength="32" >
        </div>
        <div class="block">
            <label class="title">{"权重"|L}：</label>
            <input autocomplete="off"  value="0" name="ug_weight" type="text" required="true" range="[-999999999,999999999]"><br/>
            <span style="color:#a43838; font-size: 12px;">{"所填数字越大，部门排位越靠上"|L}</span>
        </div>
    </form>
</div>
<div id="dialog-confirm-batch" class="hide" title="{'更新选中项'|L}？">
    <p>{"确定要批量更新选中的企业用户吗"|L}？</p>
</div>
{/strip}