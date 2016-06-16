
        <h2 class="title">{$title}</h2>
        <div class="toptoolbar">
                <a href="?m=announcement&a=an_add" class="button orange">发布公告</a>
        </div>

        <form id="form" action="?modules=announcement&action=index_item" method="post">
                <div class="toolbar">
                        <input autocomplete="off"  name="modules" value="announcement" type="hidden" />
                        <input autocomplete="off"  name="action" value="index_item" type="hidden" />
                        <input autocomplete="off"  name="page" value="0" type="hidden" />

                        <div class="line">
                                <label>公告标题：</label>
                                <input autocomplete="off"  class="autosend" name="an_title" type="text" />
                        </div>
                        {'<div class="line">
                                <label>发布人：</label>
                                <input autocomplete="off"  class="autosend" name="an_user" type="text" />
                        </div>'|isadmin}
                        <div class="line">
                                <label>发布状态：</label>
                                <select name="an_status">
                                        <option value="">全部</option>
                                        <option value="1">已发布</option>
                                        <option value="0">草稿</option>
                                </select>
                        </div>
                        <div class="line">
                                <label>可见区域：</label>
                                <select value='#' name="an_area" class="autofix" action="?m=area&a=option">
                                        <option value="#">全部</option>
                                </select>
                        </div>
                        <div class="line">
                                <label>发布时间：</label>
                                <input autocomplete="off"  class="datepicker start" name="start" type="text" date="true" />
                                <span>-</span>
                                <input autocomplete="off"  class="datepicker end" name="end" type="text" date="true" />
                        </div>
                        <a form="form" class="button submit">查询</a>
                </div>
        </form>

        <div class="content"></div>
        <div id="dialog-confirm" class="hide" title="删除选中项？">
                <p>确定要删除该公告吗？</p>
        </div>
        <script {"type='ready'"}>
                $('nav a.announcement').addClass('active');
                $(document).ready(function() {
                        $("div.content").delegate("#del", "click", function() {
                                var id = $(this).attr("data");
                                $("#dialog-confirm").dialog({
                                        resizable: false,
                                        height: 180,
                                        modal: true,
                                        buttons: {
                                                "删除": function() {
                                                        $(this).dialog("close");
                                                        notice("正在删除");
                                                        $.ajax({
                                                                url: "?modules=announcement&action=an_del",
                                                                data: "id=" + id,
                                                                success: function(result) {
                                                                        if (result == 0) {
                                                                                notice("没有记录被删除。非停用状态企业无法直接删除");
                                                                        } else {
                                                                                notice("成功删除" + result + "记录");
                                                                        }
                                                                        setTimeout(function() {
                                                                                send();
                                                                        }, 888);
                                                                }
                                                        });
                                                },
                                                "取消": function() {
                                                        $(this).dialog("close");
                                                }
                                        }
                                });

                        });
                })
        </script>
