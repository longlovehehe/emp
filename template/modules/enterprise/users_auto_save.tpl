{strip}
        <h2 class="title">批量新增企业用户</h2>
        <form id="form" class="base mrbt10" target="ifr">
                <input autocomplete="off"  value="enterprise" name="modules" type="hidden" />
                <input autocomplete="off"  value="users_auto_save_shell" name="action" type="hidden" />
                <input autocomplete="off"  value="{$data.e_id}" name="e_id" type="hidden" />
                <input autocomplete="off"  value="0" name="step" type="hidden" />
                <div class="block">
                        <div class="radioset" id="radioset" value="{$item.u_sub_type}">
                                <input autocomplete="off"  value="1" type="radio" id="radio_user" name="u_sub_type"  checked="checked" /><label for="radio_user">手机用户</label>
                                <input autocomplete="off"  value="2" type="radio" id="radio_shelluser" name="u_sub_type" /><label for="radio_shelluser">调度台用户</label>
                                <input autocomplete="off"  value="3" type="radio" id="radio_gvsuser" name="u_sub_type" /><label for="radio_gvsuser">GVS用户</label>
                        </div>
                </div>

                <h3 class="title">基本属性</h3>
                <hr />
                <div class="block">
                        <label class="title">起始帐号：</label>
                        <input autocomplete="off"   maxlength="32" name="u_auto_pre" type="text" required="true" digits="true" range="[20000,99999]" />
                </div>
                <div class="block">
                        <label class="title">数量：</label>
                        <input autocomplete="off"   maxlength="32" name="u_auto_number" type="text" required="true" digits="true" range="[1,799999]" />
                </div>
                <div class="block">
                        <label class="title">密码：</label>
                        <div class="line">
                                <label><input autocomplete="off"  value="1" name="u_auto_pwd" type="radio" />与帐号相同</label>
                        </div>
                        <div class="line">
                                <label><input autocomplete="off"  value="0" name="u_auto_pwd" type="radio" checked="checked" />随机生成</label>
                        </div>
                </div>

                <div class="block sw user shelluser">
                        <label class="title">默认群组：</label>
                        <select value="{$item.u_default_pg}" name="u_default_pg" class="autofix autoedit" action="?m=enterprise&a=groups_option&safe=true&e_id={$data.e_id}">
                                <option value="">未指定</option>
                        </select>
                </div>
                <div class="block sw user">
                        <label class="title">订购产品：</label>
                        <select value="{$item.u_product_id}" name="u_product_id" class="autofix autoedit" action="?m=product&a=option&e_id={$data.e_id}">
                                <option value="">未指定</option>
                        </select>
                </div>
                <div class="block">
                        <label class="title">部门：</label>
                        <select value="{$item.u_ug_id}" name="u_ug_id" class="autofix autoedit" action="?modules=api&action=get_groups_list&e_id={$data.e_id}">
                                <option value="">未指定</option>
                        </select>
                </div>

                <div class="buttons mrtop40">
                        <a id="create" class="button normal">生成</a>
                        <a class="goback button" action="?m=enterprise&a=users&e_id={$data.e_id}">取消</a>
                </div>
        </form>
        <div class="makeing info_text hide">
                <h2 class="title ">正在生成中，目前已处理 <span id="u_step_text"></span> 个，还差 <span id="u_step_number_text"></span> 个</h2>
                <progress max="{$data.max}" value="{$data.value}" class="progress"></progress>
        </div>

        <iframe id="iframe" name="ifr" class="display_box hide"></iframe>
{/strip}
