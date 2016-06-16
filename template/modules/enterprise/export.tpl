{strip}
        <h2 class="title">企业信息导入导出</h2>
        {include file="modules/enterprise/nav.tpl" }

        <div class="info hide"></div>
        <div class="block">
                <h3 class="title">企业用户</h3>
                <button class="inputfile" action="user">导入</button>
                <button class="export" action="user">导出</button>
                <a class="link" action="user">模版下载</a>
        </div>

        <div class="block">
                <h3 class="title">企业群组</h3>
                <button  class="inputfile" action="ptt_group">导入</button>
                <button class="export" action="ptt_group">导出</button>
                <a class="link" action="ptt_group">模版下载</a>
        </div>

        <div class="block">
                <h3 class="title">企业通讯录</h3>
                <button class="inputfile" action="user_group">导入</button>
                <button class="export" action="user_group">导出</button>
                <a class="link" action="user_group">模版下载</a>
        </div>

        <form class="hide" id="fileupdate" name="fileupdate" method="post" action="?modules=api&action=exportfile&e_id={$data.e_id}"  enctype="multipart/form-data" target="hidden_frame">
                <input autocomplete="off"  name="do" type="text" />
                <input autocomplete="off"  id="fileToUpload" name="fileToUpload" type="file"  />
        </form>
        <iframe id="ifr" name="hidden_frame"></iframe>
        <script  {'type="ready"'}>
                $("div.autoactive[action=export]").addClass("active");

                $(".inputfile").click(function() {
                        var act = $(this).attr("action");

                        $("input[name=do]").val(act);
                        $("#fileToUpload").trigger("click");
                });
                $("#fileToUpload").bind("change", function() {
                        $("div.info").text($("#fileToUpload").val()).removeClass("hide");
                        if ($("#fileToUpload").val() != "") {
                                notice("上传中");
                                $("#fileupdate").submit();
                        }
                });



                $(".export").click(function() {
                        var action = $(this).attr("action");
                        var url = "?modules=api&action=export&do=" + action + "&e_id={$data.e_id}";
                        $("#ifr").attr("src", url);
                });

                $("a.link").click(function() {
                        var action = $(this).attr("action");
                        var url = "?modules=api&action=export&template=1&do=" + action + "&e_id={$data.e_id}";
                        $("#ifr").attr("src", url);
                });
                function callback(result) {
                        if (result.status == 0) {
                                notice(result.msg);
                        } else {
                                notice(result.msg);
                        }
                        setTimeout(function() {
                                window.location.reload();
                        }, 2999);
                }
        </script>
{/strip}