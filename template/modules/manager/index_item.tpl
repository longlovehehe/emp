
<div id="dialog-confirm-reset" class="hide" title="重置密码?">
        <p>确定要重置管理员密吗？</p>
</div>

<form class="data">
        <table class="base full">
                <tr class='head'>      
                        <th width="20px"><input autocomplete="off"  type="checkbox" id="checkall" />全选</th>
                        <th width="70px">管理员帐号</th>
                        <th width="80px">管理区域</th>
                        <th class='none' width="150px">描述</th>
                        <th width="90px">手机号</th>
                        <th class='none' width="30px">动态登陆</th>
                        <th width="140px">邮箱</th>
                        <th width="160px">最后登录时间</th>
                        <th width="100px">最后登录IP</th>
                        <th width="150px">操作</th>
                </tr>
                {foreach name=list item=item from=$list}
                        <tr title="管理员帐号{$item.om_id}，管理区域{$item.om_area|mod_area_name}，手机号{$item.om_phone|default:'未填写'}，邮箱{$item.om_mail|default:'未填写'}，最后登录时间{$item.om_lastlogin_time|default:'暂无记录'}，最后登录IP{$item.om_lastlogin_ip|default:'暂无记录'}，描述{$item.om_desc|default:'未填写'}">
                                <td><input autocomplete="off"  type="checkbox" name="checkbox" value="{if $item.om_id  neq 'admin'}{$item.om_id}{/if}" class="cb" {if $item.om_id  eq 'admin'}disabled{/if}/></td>
                                <td><span class="ellipsis" style="width: 60px">{$item.om_id}</span></td>
                                <td class="info"><span class="ellipsis" style="width: 30px">{$item.om_area|mod_area_name}</span></td>
                                <td class="none info">{$item.om_desc|default:'未填写'}</td>
                                <td>{$item.om_phone|default:'未填写'}</td>
                                <td class='none'>{$item.om_safe_login}</td>
                                <td>{$item.om_mail|default:'未填写'}</td>
                                <td>{$item.om_lastlogin_time|default:'暂无记录'}</td>
                                <td>{$item.om_lastlogin_ip|default:'暂无记录'}</td>
                                <td>
                                        <a href="?m=manager&a=om_edit&om_id={$item.om_id}&flag=edit" class="link">编辑</a>
                                        <a data="?m=manager&a=om_reset&reset_id={$item.om_id}" class="mrlf5 link reset">重置密码</a>
                                </td>
                        </tr>
                {/foreach}
        </table>
        <div class="page none_select">
                <div class="num">{$numinfo}</div>
                <div class="turn">
                        <a page="{$prev}" class="prev">上一页</a>
                        <a page="{$next}" class="next">下一页</a>
                </div>
        </div>
</form>
