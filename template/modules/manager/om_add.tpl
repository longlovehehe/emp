{strip}
        <h2 class="title">{$title}</h2>

        <form id="form" class="base mrbt10" action="?m=manager&a=om_save">
                <input autocomplete="off"  type="hidden" name="om_id" value="{$smarty.get.om_id}" />
                <input autocomplete="off"  type="hidden" name="om_flag" value="{$smarty.get.flag}" />

                <div class="block">
                        <label class="title">帐号：</label>
                        <input maxlength="32" autocomplete="off" value="{$list.om_id}" name="om_id" type="text" required="true" {if $smarty.get.flag eq 'edit'} readonly{/if} />
                </div>
                <div class="block" {if $smarty.get.flag eq "edit"}style="display:none;"{/if}>
                        <label class="title">密码：</label>
                        <input maxlength="32" autocomplete="off" id="pass" value="{$list.om_pswd}" name="om_pswd" type="password" required="true" password ="true" />
                </div>
                <div class="block" {if $smarty.get.flag eq "edit"}style="display:none;"{/if}>
                        <label class="title">重复密码：</label>
                        <input maxlength="32" autocomplete="off"  value="{$data.d_ip2}" name="om_pswd2" type="password" required="true" equalTo="#pass" />
                </div>
                <div class="block">
                        <label class="title">手机号：</label>
                        <input maxlength="32" autocomplete="off"  value="{$list.om_phone}" name="om_phone" type="text" required="true" mobile="true" />
                        <input autocomplete="off"  type="checkbox" class="none" name="" value="" disabled="true"/>
                </div>
                <div class="block">
                        <label class="title">邮箱：</label>
                        <input maxlength="32" autocomplete="off"  value="{$list.om_mail}" name="om_mail" type="text" required="true"  email="true" />
                </div>
                <div class="block {if $list.om_id =='admin'}none{/if}">
                        <label class="title">区域：</label>
                        <select value='{$list.om_area}' multiple="true" name="om_area[]"  class="autofix autoeditselect " action="?m=area&a=option" selected="true">
                                <option value='#'>全部</option>
                        </select>
                </div>
                <div class="block">
                        <label class="title">描述：</label>
                </div>
                <div class="block">   
                        <textarea maxlength="1024" style="resize:none;width:390px;height:100px;border:1px solid #ccc;font-size:13px;padding:5px;" name="om_desc">{$list.om_desc}</textarea>
                </div>    
                <div class="buttons mrtop40">
                        <a goto="?m=manager&a=index" form="form" class="ajaxpost button normal">保存</a>
                        <a class="goback button">取消</a>
                </div>
        </form>
{/strip}