{strip}
<!-- 基本信息 -->
<div class="userinfo">
    <h2 class="title _3_jpg" ><em class='none'>{"帐号信息"|L}</em></h2>
    <div class="uname"><label class="title">{"姓名"|L}：</label>{$smarty.session.eown.em_admin_name} {$smarty.session.eown.em_surname}</div>
    <!--帐号状态-->
    <ul class="list logininfo">
        <li><label class="title">{"手机"|L}：</label>{$smarty.session.eown.em_phone}</li>
        <li><label class="title">{"邮箱"|L}：</label>{$smarty.session.eown.em_mail}</li>
        <li><label class="title">{"上次登录地址"|L}：</label>{$smarty.session.em_lastlogin_ip}</li>
        <li><label class="title">{"上次登录时间"|L}：</label>{$smarty.session.eown.em_lastlogin_time}</li>
    </ul>
</div>
<hr class="hr" />
{include file="modules/enterprise/nav.tpl" }
<h2 class="title">{"企业信息"|L}</h2>
{if $data.e_sync != "0"}
{*<div class="info big center animated nonselect">
    <p>{"编辑了用户，但是没有同步至设备"|L}。状态码：{$data.e_sync}</p>
</div>
*}
{/if}
<div class="form mrbt20">
    <div class="block ">
        <label class="title">{"企业编号"|L}：</label>
        <span>{$data.e_id}</span>
    </div>

    <div class="block ">
        <label class="title">{"企业名称"|L}：</label>
        <span title='{$data.e_name}' class='title ellipsis2' style='max-width: 350px;height: 20px;'>{$data.e_name|mbsubstr:20}</span>
    </div>
    <div class="block ">
        <label class="title">{"企业地址"|L}：</label>
        <span>{$data.e_addr}</span>
    </div>
     <div class="block">
        <label class="title">{"行业"|L}：</label>
        <span>{$data.e_industry}</span>
    </div>
    <div class="block">
        <label class="title">{"联系人"|L}：</label>
       <span>{$data.e_contact_name}</span>&nbsp;
       <span>{$data.e_contact_surname}</span>
    </div>
    <div class="block">
        <label class="title">{"电话"|L}：</label>
        <span>{$data.e_contact_phone}</span>
    </div>
    <div class="block">
        <label class="title">{"传真"|L}：</label>
        <span>{$data.e_contact_fox}</span>
    </div>
    <div class="block">
        <label class="title">{"邮箱"|L}：</label>
        <span>{$data.e_contact_mail}</span>
    </div>

    <div class="block ">
        <label class="title">{"区域"|L}：</label>
        <span>{$data.e_area|mod_area_name}</span>
    </div>
    <div class="block ">
        <label class="title">{"状态"|L}：</label>
        <span title='{"不启用"|L}|{"启用"|L}|{"处理中"|L}|{"发布失败，启用时不能迁移{$smarty.session.ident}-Server,只有具有录制功能才能迁移VCR。处于处理中时无法编辑企业"|L}。{"当前状态"|L}{$data.e_status}'>{$data.e_status|modifierStatus} <span style="font-size: 16px;color: red;">{if $data.e_status eq 3}({"错误码"|L}:403){else if $data.e_status eq 4}({"错误码"|L}:404){/if}</span></span>
    </div>
    <div class="block ">
        <label class="title">{"{$smarty.session.ident}-Server"|L}：</label>
        <span>{$data.mds_d_name}<!-- 【{$data.mds_d_ip1}】 --></span>
    </div>
    <div class="block ">
        <label class="title">{$smarty.session.ident}-RS：</label>
        <span>{$data.rs_name}</span>
    </div>
    <div class="block ">
        <label class="title">{$smarty.session.ident}-SS：</label>
        <span>{$data.ss_name}</span>
    </div>
    <div class="block ">
        <label class="title">{"用户数"|L}：</label>
        <span>{$phone_num+$dispatch_num+$gvs_num}/{$data.e_mds_users}</span>
    </div>
    <div class="block ">
        <label class="title">{"手机用户数"|L}：</label>
        <span>{$phone_num}/{$data.e_mds_phone}</span>
    </div>
    <div class="block ">
        <label class="title">{"调度台用户数"|L}：</label>
        <span>{$dispatch_num}/{$data.e_mds_dispatch}</span>
    </div>
    <!--
    <div class="block ">
        <label class="title">{"GVS用户数"|L}：</label>
        <span>{$gvs_num}/{$data.e_mds_gvs}</span>
    </div>
    <div class="block none">
        <label class="title">{"并发数"|L}：</label>
        <span>{$data.e_mds_call}</span>
    </div>

    {if $data.e_has_vcr eq "1"}
    {*具有VCR功能*}
    <div class="block none">
        <label class="title">{"VCR"|L}：</label>
        <span>{$data.vcr_d_ip1}</span>
    </div>
    <div class="block ">
        <label class="title">{"存储功能"|L}：</label>
        <span>{$data.e_storage_function|modifierStorage}</span>
    </div>
    <div class="block ">
        <label class="title">{"录音并发数"|L}：</label>
        <span>{$data.e_vcr_audiorec}</span>
    </div>
    <div class="block ">
        <label class="title">{"录像并发数"|L}：</label>
        <span>{$data.e_vcr_videorec}</span>
    </div>
    <div class="block ">
        <label class="title">{"存储空间"|L}：</label>
        <span>{$data.e_vcr_space} MB</span>
    </div>
    {/if}
-->
</div>

<div id="dialog-confirm-warn" class="hide" title="重要操作确认？">
    <p>{"确认要重建该企业数据？该操作会导致该企业所有用户，企业群组，企业日志，企业通讯录数据丢失"|L}。<br />[{"一般在创建企业时，如果未能正常使用时，才考虑该项"|L}！]<br /><span class="red">如果您不知道此项是做什么用的，请不要点击！</span></p>
</div>
{/strip}
