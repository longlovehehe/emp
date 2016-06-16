{strip}
<h2 class="title">{$title}</h2>
<form id="form" class="base mrbt10" action="?modules=enterprise&action=save_shell">
    <div class="block">
        <label class="title">企业名称：</label>
        <input  maxlength="64" autocomplete="off" chinese="true"  maxlength="32" name="e_name" type="text" required="true" />
    </div>
    <div class="block">
        <label class="title">区域：</label>
        <select name="e_area" class="autofix autoselect" action="?m=area&a=option" selected="true" data='[{ "to": "e_mds_id","field": "d_area","view":"false" }]'>
            <option value='@'>未选择</option>
        </select>
    </div>
    <input autocomplete="off"  value="0" name="e_status" type="hidden" checked="checked" />
    <div class="block">
        <label class="title">企业密码：</label>
        <input  maxlength="32" autocomplete="off"  onpaste="return false" maxlength="32" e_pwd="true" name="e_pwd" type="text"/>
    </div>
    <div class="block">
        <label class="title">所属GQT-Server：</label>
        <select value="" id="e_mds_id" name="e_mds_id" size="10"  class=" long" action="?m=device&action=mds_option" selected="true"></select>
    </div>
    <div class="block">
        <label maxlength="32" class="title">企业用户数：</label>
        <input  maxlength="32" autocomplete="off"  value='0' name="e_mds_users" type="text" required="true" digits ="true" />
    </div>
    <div class="block">
        <label class="title">企业并发数：</label>
        <input  maxlength="32" autocomplete="off"  maxlength="32" value='0' name="e_mds_call" type="text" required="true" digits ="true" />
    </div>

    {*
    <hr class="none"/>
    <div class="block none">
        <label class="title">录制功能：</label>
        <input autocomplete="off"  name="e_has_vcr" class="auto_toggle" action="d_rec_toggle" type="checkbox" />
    </div>
    <div class="d_rec_toggle hide">
        <div class="block">
            <label class="title">所属VCR：</label>
            <select id="vcr" name="e_vcr_id" class="autofix1 auto_toggle_open long" size="10" action="?modules=api&action=get_vcr_list"  disabled="true" required="true">
            </select>
        </div>

        <div class="block">
            <label class="title">录音并发数：</label>
            <input autocomplete="off"  value="0" name="e_vcr_audiorec" id="d_audiorec" type="text" required="true" digits ="true" />
        </div>

        <div class="block">
            <label class="title">录像并发数：</label>
            <input autocomplete="off"  value="0" name="e_vcr_videorec" type="text" required="true" digits ="true" />
        </div>

        <div class="block">
            <label class="title">存储空间（单位MB）：</label>
            <input autocomplete="off"  value="0" name="e_vcr_space" type="text" />
        </div>
        <div class="block">
            <label class="title">存储功能：</label>
            <div class="line">
                <input autocomplete="off"  name="e_storage_function" value="1" name="type" type="radio">
                <label for="radio_synchronous">同步</label>
            </div>
            <div class="line">
                <input autocomplete="off"  name="e_storage_function" value="2" name="type" type="radio" checked="checked">
                <label for="radio_storage">存储</label>
            </div>
        </div>
    </div>
    *}
    <div class="buttons mrtop40">
        <a goto="?m=enterprise&a=index" form="form" class="ajaxpost button normal">保存</a>
        <a class="goback button">取消</a>
    </div>
</form>


<script {'type="ready"'}>
    jQuery.validator.addMethod("resource_less", function (value, element) {
        var flag = false;
        if (value == 0) {
            flag = true;
        }
        return flag;
    }, "该资源已用完，只能输入0");

    $("select#e_mds_id").bind("change", function () {
        var d_user = $(this).children('option:selected').attr("d_user");
        var d_call = $(this).children('option:selected').attr("d_call");
        $("input[name=e_mds_users]").removeAttr('resource_less').removeAttr('range');
        $("input[name=e_mds_call]").removeAttr('resource_less').removeAttr('range');

        if (d_user == 'undefined' || d_user == "" || d_user == 0) {
            $("input[name=e_mds_users]").attr("resource_less", 'TRUE');
        } else {
            urange = "[0," + d_user + "]";
            $("input[name=e_mds_users]").attr("range", urange);
        }
        if (d_call == 'undefined' || d_user == "" || d_call == 0) {
            $("input[name=e_mds_call]").attr("resource_less", 'TRUE');
        } else {
            crange = "[0," + d_call + "]";
            $("input[name=e_mds_call]").attr("range", crange);
        }
        $("#form").valid();
    });

    (function () {
        var url = $("select#e_mds_id").attr("action");
        url += "&d_area=@";
        $.ajax({
            url: url,
            success: function (result) {
                $("select#e_mds_id").html(result);
            }
        });
    })();

    { *
            $("select#vcr").bind("change", function () {
        var d_space = $(this).children('option:selected').attr("d_space");
        var d_audiorec = $(this).children('option:selected').attr("d_audiorec");
        var d_videorec = $(this).children('option:selected').attr("d_videorec");
        if (d_space != "") {
            range = "[0," + d_space + "]";
            $("input[name=e_vcr_space]").attr("range", range);
        }
        if (d_audiorec != "") {
            range = "[0," + d_audiorec + "]";
            $("input[name=e_vcr_audiorec]").attr("range", range);
        }
        if (d_videorec != "") {
            range = "[0," + d_videorec + "]";
            $("input[name=e_vcr_videorec]").attr("range", range);
        }
    });
            * }
    $("#form").valid();
</script>
{/strip}
