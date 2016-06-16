{strip}
<h2 class="title">{$title}</h2>

<form id="form" action="?modules=log&action=index_item" method="post">
    <div class="toolbar">
        <input autocomplete="off"  name="modules" value="log" type="hidden" />
        <input autocomplete="off"  name="action" value="index_item" type="hidden" />
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <div class="line">
            <label>日志显示级别：</label>
            <select name="el_level" value="{$smarty.request.el_level}" class="autoedit">
                <option value="">全部</option>
                <option value="2">错误</option>
                <option value="1">警告</option>
                <option value="0">信息</option>
            </select>
        </div>
        {if $smarty.session.eown.om_id == 'admin'}
        <div class="line">
            <label>用户名称：</label>
            <input autocomplete="off"  class="autosend" name="el_user" type="text" />
        </div>
        {/if}
        <div class="line">
            <label>日志内容：</label>
            <input autocomplete="off"  class="autosend" name="el_content" type="text" />
        </div>
        <div class="line">
            <label>起始时间：</label>
            <input autocomplete="off"  class="datepicker start" name="start" type="text" date='true' />
            <span>-</span>
            <input autocomplete="off"  class="datepicker end" name="end" type="text" date="true" />
        </div>

    </div>
    <div class="toolbar">
        <label>显示来源：</label>

        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="7"/>登录模块</label>
        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="1"/>企业模块</label>
        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="2"/>设备模块</label>
        {'<label><input autocomplete="off"  type="checkbox" name="el_type[]" value="3"/>角色模块</label>'|isadmin}
        {'<label><input autocomplete="off"  type="checkbox" name="el_type[]" value="4"/>区域模块</label>'|isadmin}
        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="5"/>产品模块</label>
        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="6"/>日志模块</label>
        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="8"/>公告模块</label>
        <div class="buttons right"><a form="form" class="button submit">查询</a></div>
    </div>
</form>

<div class="content"></div>
{/strip}