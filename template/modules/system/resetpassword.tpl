
<h2 class="title" >{"修改密码"|L}</h2>
<form id="form" action="?modules=system&action=changepassword" class="base mrbt10">
    <div class="block">
        <label class="block">
            <span class="title">{"原密码"|L}：</span>
            <input autocomplete="off"   paswd="true" type="password" name="old_pwd" required="true" class="autosend"/>
        </label>
        <label  class="block">
            <span  class="title">{"新密码"|L}：</span>
            <input autocomplete="off" paswd="true"  type="password" name="new_pwd" required="true"  class="autosend"/>
        </label>
        <label  class="block">
            <span  class="title">{"重复新密码"|L}：</span>
            <input autocomplete="off" paswd="true" type="password" name="new_rpwd" required="true" equalto="input[name='new_pwd']" class="autosend"/>
        </label>
    </div>
    <div class="buttons mrtop40">
        <a form="form" class="ajaxpost1 button normal">{"修改"|L}</a>
    </div>
</form>

<script {'type="ready"'}>
    (function () {
        if (typeof ($("#form").valid) != 'undefined') {
            $("#form").valid();
        }

        var submitpost = function () {
            if ($("#form").valid()) {
                var form = $("a.ajaxpost1").attr("form");
                var url = $("#" + form).attr("action");
                var data = $("#form").serialize();

                $.ajax({
                    url: url,
                    method: "POST",
                    dataType: "json",
                    data: data,
                    success: function (result) {
                        if (result.status == 1) {
                            notice(result.msg, '?m=logout');
                        } else {
                            notice(result.msg);
                        }
                    }
                });
            } else {
                $("input[name='new_pwd'],input[name='new_rpwd']").val("");
            }
        };

        $("a.ajaxpost1").bind("click", submitpost);
    })();

</script>