<table class="base full">
        <tr class='head'>
                <th width="5%"><input autocomplete="off"  type="checkbox" id="checkall" />全选</th>
                <th width="5%">管理员帐号</th>
                <th class="rich none">描述</th>
                <th class="rich" width="10%">手机号</th>
                <th class="rich" width="10%">安全登录</th>
                <th class="rich" width="10%">邮箱</th>
                <th class="rich none" width="10%">所属企业</th>
                <th class="rich" width="10%">最后登录时间</th>
                <th class="rich" width="10%">最后登录IP</th>
                <th width="5%">操作</th>
        </tr>
        {foreach name=list item=item from=$list}
                <tr title="管理员帐号【{$item.em_id}】手机号【{$item.em_phone}】邮箱【{$item.em_mail}】所属企业【{$item.e_name}】最后登录时间【{$item.em_lastlogin_time}】最后登录IP【{$item.em_lastlogin_ip}】描述【{$item.em_desc}】">
                        <td><input autocomplete="off"  type="checkbox" name="checkbox" value="{$item.em_id}" class="cb" /></td>
                        <td>{$item.em_id}</td>
                        <td class="rich none">{$item.em_desc}</td>
                        <td class="rich">{$item.em_phone}</td>
                        <td class="rich">{$item.em_safe_login|modifierSafeLogin}</td>
                        <td class="rich">{$item.em_mail}</td>
                        <td class="rich none">{$item.e_name}</td>
                        <td class="rich">{$item.em_lastlogin_time}</td>
                        <td class="rich">{$item.em_lastlogin_ip}</td>
                        <td><a href="?m=enterprise&a=admins_edit&e_id={$data.e_id}&em_id={$item.em_id}" class="link">编辑</a></td>
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