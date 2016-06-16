{strip}
        <h2 class="title">{$title}</h2>
        <div class="toptoolbar">
                <a href="?m=manager&a=om_add" class="button orange">新增运营管理员</a>
        </div>
        <div class="toolbar">
                <form action="?m=manager&a=index_item" id="form" method="post">
                        <input autocomplete="off"  name="modules" value="manager" type="hidden" />
                        <input autocomplete="off"  name="action" value="index_item" type="hidden" />
                        <input autocomplete="off"  name="page" value="0" type="hidden" />
                        <div class="line">
                                <label>管理员帐号：</label>
                                <input autocomplete="off"  class="autosend" name="om_id" type="text" value="" style="width:110px"/>
                        </div>
                        <div class="line">
                                <label>动态登陆：</label>
                                <select name="om_safe_login" style="width:120px">
                                        <option value="">全部</option>
                                        <option value="1">是</option>
                                        <option value="0">否</option>
                                </select>
                        </div>
                        <div class="line">
                                <label>最后登录时间：</label>
                                <input autocomplete="off"  class="datepicker start" name="start" type="text" date="true" />
                                <span>-</span>
                                <input autocomplete="off"  class="datepicker end" name="end" type="text" date="true" />
                        </div>

                        <a form="form" class="button submit">查询</a>
                </form>
        </div>

        <div class="toolbar">
                <a id="delall" class="button">批量删除</a>
        </div>
        <div class="content"></div>
        <div id="dialog-confirm" class="hide" title="删除选中项？">
                <p>确定要删除选中的管理员吗？</p>
        </div>
{/strip}