{strip}
        <div class="toolbar">
                <a href="?m=product&a=index" class="button active">产品管理</a>
                <a href="?m=product&a=p_function" class="button">产品功能库</a>
        </div>
        <h2 class="title">{$title}</h2>
        <div class="toptoolbar">
                <a href="?m=product&a=p_add" class="button orange">新增产品</a>
        </div>
        <form id="form" action="?modules=product&action=index_item" method="post">
                <div class="toolbar">
                        <input autocomplete="off"  name="modules" value="product" type="hidden" />
                        <input autocomplete="off"  name="action" value="index_item" type="hidden" />
                        <input autocomplete="off"  name="page" value="0" type="hidden" />
                        <div class="line">
                                <label>产品代码：</label>
                                <input autocomplete="off"  class="autosend" name="p_id" type="text" />
                        </div>
                        <div class="line">
                                <label>产品名称：</label>
                                <input autocomplete="off"  class="autosend" name="p_name" type="text" />
                        </div>
                        <div class="line">
                                <label>运营区域：</label>
                                <select name="p_area" class="autofix" action="?m=area&a=option">
                                        <option value="#">全部</option>
                                </select>
                        </div>
                        <div class="line">
                                <label>产品价格区间：</label>
                                <input autocomplete="off"  name="start" type="number" min="0" digits='true' range="[0,2147483647]" />
                                <span>-</span>
                                <input autocomplete="off"  name="end" type="number" min="0" digits='true' range="[0,2147483647]" />
                        </div>
                        <a form="form" class="button submit">查询</a>
                </div>
        </form>
        <div class="content"></div>
        <div id="dialog-confirm" class="hide" title="删除选中项？">
                <p>确定要删除该产品吗？</p>
        </div>
{/strip}
<script  {"type='ready'"}>
        $(document).ready(function() {
               
        })
</script>