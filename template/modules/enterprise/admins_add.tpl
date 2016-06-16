{strip}
        <h2 class="title">{$title}</h2>

        <form id="form" class="base mrbt10" action="?modules=enterprise&action=admins_save">
                <input autocomplete="off"  value="{$data.e_id}" name="em_ent_id" type="hidden" />
                <input autocomplete="off"  value="{$data.do}" name="do" type="hidden" />
                <div class="block">
                        <label class="title">帐号：</label>
                        {if $data.do eq "edit"}
                                <input autocomplete="off"   maxlength="32" value="{$data.em_id}" name="em_id" type="text" required="true" readonly="true" />
                        {else}
                                <input autocomplete="off"    maxlength="32" name="em_id" type="text" required="true" />
                        {/if}
                </div>
                <div class="block">
                        <label class="title">密码：</label>
                        <input autocomplete="off"  maxlength="32" value="{$data.em_pswd}" name="em_pswd" type="text" required="true" />
                </div>
                <div class="block">
                        <label class="title">手机号：</label>
                        <input autocomplete="off"  maxlength="32" value="{$data.em_phone}" name="em_phone" type="text" required="true" mobile="true" />
                </div>
                <div class="block">
                        <label class="title">邮箱：</label>
                        <input autocomplete="off"  maxlength="32" value="{$data.em_mail}" name="em_mail" type="text" required="true"  email="true"  />
                </div>
                <div class="block">
                        <label class="title">安全登录：</label>
                        <div class="checkbox inline" value="{$data.em_safe_login}">
                                <input autocomplete="off"  name="em_safe_login" type="checkbox" />
                        </div>
                </div>
                <div class="block">
                        <label class="title">描述：</label>
                        <input maxlength="1024" autocomplete="off" value="{$data.em_desc}" name="em_desc" type="text" />
                </div>

                <div class="buttons mrtop40">
                        <a goto="?m=enterprise&a=admins&e_id={$data.e_id}" form="form" class="ajaxpost button normal">保存</a>
                        <a class="goback button">取消</a>
                </div>
        </form>
{/strip}