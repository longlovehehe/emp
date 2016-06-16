{strip}
<h2 class="title">{"{$title}"|L}</h2>
<script  {'type="ready"'}>
    $('nav a.log').addClass('active');
</script>

<form id="form" action="?modules=log&action=index_item" method="post">
    <div class="toolbar">
        <input autocomplete="off"  name="modules" value="log" type="hidden" />
        <input autocomplete="off"  name="action" value="index_item" type="hidden" />
        <input autocomplete="off"  name="page" value="0" type="hidden" />
        <div class="line">
            <label>{"日志显示级别"|L}：</label>
            <select name="el_level" value="{$smarty.request.el_level}" class="autoedit">
                <option value="">{"全部"|L}</option>
                <option value="2">{"错误"|L}</option>
                <option value="1">{"警告"|L}</option>
                <option value="0">{"信息"|L}</option>
            </select>
        </div>
        {if $smarty.session.eown.om_id == 'admin'}
        <div class="line">
            <label>{"用户名称"|L}：</label>
            <input autocomplete="off"  class="autosend" name="el_user" type="text" />
        </div>
        {/if}
        <div class="line">
            <label>{"日志内容"|L}：</label>
            <input autocomplete="off"  class="autosend" name="el_content" type="text" />
        </div>
        <div class="line">
            <label>{"创建时间"|L}：</label>
            <input autocomplete="off"  class="datepicker start" name="start" type="text" date='true' />
            <span>-</span>
            <input autocomplete="off"  class="datepicker end" name="end" type="text" date="true" />
        </div>

    </div>
    <div class="toolbar mrlabel5 ">
        <label>{"显示来源"|L}：</label>

        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="7"/>{"登录"|L}</label>
        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="1"/>{"用户"|L}</label>
        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="2"/>{"群组"|L}</label>
        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="3"/>{"部门"|L}</label>
        <label><input autocomplete="off"  type="checkbox" name="el_type[]" value="6"/>{"日志"|L}</label>

    </div>
    <div class="toolbar">
        <div class="buttons right"><a form="form" class="button submit">{"查询"|L}</a></div>
    </div>
</form>

<div class="content"></div>
{/strip}