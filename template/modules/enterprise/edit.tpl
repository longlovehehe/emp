{strip}
<h2 class="title">{$title}</h2>
<form id="form" class="base mrbt10" action="?modules=enterprise&action=save_shell">
    <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />
    <div class="block">
        <label class="title">企业名称：</label>
        <input maxlength="64" autocomplete="off"  value="{$data.e_name}" name="e_name" type="text" required="true" />
    </div>
    <div class="block">
        <label class="title">所属区域：</label>
        <label>{$data.e_area|mod_area_name}</label>
        <!--<select value="{$data.e_area}" name="e_area" class="autofix autoedit" action="?m=area&a=option" required="true" readonly></select>-->
    </div>
    <div class="block radio" value="{$data.e_status}">
        <label class="title">企业状态：</label>
        <label>{$data.e_status|modifierStatus}</label>
    </div>
    <div class="block">
        <label class="title">所属GQT-Server</label>
        <label id="mds_id" class="renderjson">{$data.e_mds_id|modmdsid}</label>
        {*
        {if $data.e_mds_id > 0}
        <input autocomplete="off"  class='block' id="mds_limit"  type="text" disabled="true" />
        <input autocomplete="off"  value="{$data.e_mds_id}"  name="e_mds_id" type="hidden"/>
        {/if}
        <select {if $data.e_mds_id > 0}disabled="true"{/if} id='mds' value="{$data.e_mds_id}" size="10" name="e_mds_id" class="autofix autoedit long" action="?modules=api&action=get_mds_list" required="true"  ></select>
        *}
    </div>

    <div class="block">
        <label class="title">企业密码</label>
        <input maxlength="32" autocomplete="off"  name="e_pwd" value="{$data.e_pwd}" type="text" />
    </div>

    <div class="block">
        <label class="title">企业用户数</label>
        <input maxlength="32" autocomplete="off"  value="{$data.e_mds_users}" name="e_mds_users" type="text" required="true" digits ="true" />
    </div>
    <div class="block">
        <label class="title">企业并发数</label>
        <input maxlength="32" autocomplete="off"  value="{$data.e_mds_call}" name="e_mds_call" type="text" required="true" digits ="true" />
    </div>

    <!--
    {*
    <hr class="none"/>
    <div class="block checkbox_defined none" value="{$data.e_has_vcr}">
    <label class="title">录制功能</label>
    <input autocomplete="off"  name="e_has_vcr" class="auto_toggle_defined" action="d_rec_toggle" type="checkbox" />
    </div>
    <div class="d_rec_toggle hide none">
    <div class="block">
    <label class="title">所属VCR</label>
    <input autocomplete="off"  class='block' id="vcr_limit" d_space_free="{$data.d_space_free}" d_audiorec="{$data.d_audiorec}" d_videorec="{$data.d_videorec}" value="{$data.vcr_d_ip1}" type="text" disabled="true" />
    {if $data.e_has_vcr == 1}
    <input autocomplete="off"  value="{$data.e_vcr_id}"  name="e_vcr_id" type="hidden"/>
    {/if}
    <select disabled="true" id='vcr' size='10' value="{$data.e_vcr_id}" name="e_vcr_id" class="autofix auto_toggle_open autoedit long" action="?modules=api&action=get_vcr_list"  required="true" ></select>
    </div>

    <div class="block">
    <label class="title">录音并发数</label>
    <input autocomplete="off"  value="{$data.e_vcr_audiorec}" name="e_vcr_audiorec" id="d_audiorec" type="text" required="true" digits ="true" />
    </div>

    <div class="block">
    <label class="title">录像并发数</label>
    <input autocomplete="off"  value="{$data.e_vcr_videorec}" name="e_vcr_videorec" type="text" required="true" digits ="true" />
    </div>

    <div class="block">
    <label class="title">存储空间（单位MB）</label>
    <input autocomplete="off"  value="{$data.e_vcr_space}" name="e_vcr_space" type="text"  required="true" digits ="true" />
    </div>
    <div class="block radio" value="{$data.e_storage_function}">
    <label class="title">存储功能</label>
    <div class="line">
    <input autocomplete="off"  name="e_storage_function" value="1" name="type" type="radio">
    <label for="radio_synchronous">同步</label>
    </div>
    <div class="line">
    <input autocomplete="off"  name="e_storage_function" value="2" name="type" type="radio" checked="checked">
    <label for="radio_storage">存储</label>
    </div>
    </div>
    </div> *}
    -->
    <div class="buttons mrtop40">
        <a goto="?m=enterprise&a=index" form="form" class="ajaxpost button normal">保存</a>
        <a class="goback button">取消</a>
    </div>
</form>
<script {'type="ready"'}>
    (function () {
        var json = eval($("#mds_id").text());
        var d_user = json[0]['diff_user'];
        var d_call = json[0]['diff_call'];
        range = "[0," + d_user + "]";
        $("input[name=e_mds_users]").attr("range", range);
        range = "[0," + d_call + "]";
        $("input[name=e_mds_call]").attr("range", range);
    })();
</script>
{*
<script  {'type="ready"'}>
    $.ajaxSetup({
        async: false
    });

    {if $data.e_mds_id > 0}
    $("select#mds").bind("change", function () {
        var d_user = $(this).children('option:selected').attr("d_user");
        var d_call = $(this).children('option:selected').attr("d_call");
        $("#mds_limit").val($(this).children('option:selected').text());
        if (d_user != "") {
            range = "[0," + d_user + "]";
            $("input[name=e_mds_users]").attr("range", range);
        }
        if (d_call != "") {
            range = "[0," + d_call + "]";
            $("input[name=e_mds_call]").attr("range", range);
        }
        $("select#mds").hide();
    });
    { else}
    $("select#mds").bind("change", function () {
        var d_user = $(this).children('option:selected').attr("d_user");
        var d_call = $(this).children('option:selected').attr("d_call");
        if (d_user != "") {
            range = "[0," + d_user + "]";
            $("input[name=e_mds_users]").attr("range", range);
        }
        if (d_call != "") {
            range = "[0," + d_call + "]";
            $("input[name=e_mds_call]").attr("range", range);
        }
    });
    {/if}


            $("select#vcr").bind("change", function () {
        var d_space = $(this).children('option:selected').attr("d_space");
        var d_audiorec = $(this).children('option:selected').attr("d_audiorec");
        var d_videorec = $(this).children('option:selected').attr("d_videorec");
        $("#vcr_limit").val($(this).children('option:selected').text());
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
        {if $data.e_has_vcr == 1}
        $("select#vcr").hide();
        {/if}
    });
    $("select#mds").trigger('change');
    $("select#vcr").trigger('change');
    (function () {
        $("input.auto_toggle_defined").bind("click", function () {
            var url = $(this).attr("action");

            var owner = $("." + url);
            if ($(this).is(":checked")) {
                owner.show();
                /*$(".auto_toggle_open").attr("disabled", false);*/
            } else {
                owner.hide();
                /*$(".auto_toggle_open").attr("disabled", true);*/
            }
        });
        var val = $("div.checkbox_defined").attr("value");
        if (val == "1") {
            $("input.auto_toggle_defined").trigger("click");
        }
    })();

    {if $data.e_has_vcr == 0}
    $("#vcr_limit").hide();
    $(".auto_toggle_open").attr("disabled", false).show();
    {/if}

</script>

*}
{/strip}
