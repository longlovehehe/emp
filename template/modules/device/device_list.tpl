{strip}
    <h2 class="title">{$data.do|upper} [{$data.d_ip1}] 使用详情</h2>
    <div class="toolbar">
        <form action="?modules=device&action=device_list_item" id="form" method="post">
            <input autocomplete="off"  name="device_id" value="{$data.device_id}" type="hidden" />
            <input autocomplete="off"  name="do" value="{$data.do}" type="hidden" />
            <input autocomplete="off"  name="page" value="0" type="hidden" />
            <a form="form" class="button submit none">查询</a>
            <a class="goback button">返回</a>
        </form>
    </div>
    <div class="content"></div>
{/strip}