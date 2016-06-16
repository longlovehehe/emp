<h2 class="title">编辑区域</h2>

<form id="form" class="base mrbt10" action="?modules=area&action=area_save" method="post">
        <input autocomplete="off"  name="am_id" value="{$data.am_id}" type="hidden" />
        <div class="block">
                <label class="title">区域名称：</label>
                <input autocomplete="off" maxlength="10" value="{$data.am_name}" name="am_name" type="text" required="true" />
        </div>
        <div class="buttons mrtop40">
                <a goto="?m=area&a=index" form="form" class="ajaxpost button normal">保存</a>
                <a class="goback button">取消</a>
        </div>
</form>