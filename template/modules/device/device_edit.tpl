{strip}
        <h2 class="title">编辑设备</h2>

        <form id="form" class="base mrbt10" action="?modules=device&action=mds_save">
                <input autocomplete="off"  name="d_id" value="{$data.d_id}" type="hidden" />
                <div class="block none">
                        <div class="radioset" id="radioset" value="{$data.d_type}">
                                <input autocomplete="off"  value="{$data.d_type}" type="radio" id="radio_{$data.d_type}" name="d_type" /><label for="radio_{$data.d_type}">{$data.d_type|upper}</label>
                        </div>
                </div>
                <div class="block">
                        <label class="title">设备名称：</label>
                        <input autocomplete="off"   maxlength="32" value="{$data.d_name}" name="d_name" type="text" required="true" />
                </div>
                <div class="block">
                        <label class="title">设备外网地址：</label>
                        <input autocomplete="off"   maxlength="32" value="{$data.d_ip1}" name="d_ip1" type="text" required="true" ip="true" />
                </div>
                <div class="block">
                        <label class="title">设备外网端口：</label>
                        <input autocomplete="off"   maxlength="32" value="{$data.d_port1}" name="d_port1" type="text" required="true" digits ="true" range="[0,65535]" />
                </div>
                <div class="block">
                        <label class="title">设备内网地址：</label>
                        <input autocomplete="off"   maxlength="32" value="{$data.d_ip2}" name="d_ip2" type="text" ip="true" />
                </div>
                <div class="block">
                        <label class="title">设备内网端口：</label>
                        <input autocomplete="off"   maxlength="32" value="{$data.d_port2}" name="d_port2" type="text"  digits ="true" range="[0,65535]" />
                </div>

                <div class="block">
                        <label class="title">设备所属区域：</label>
                        <select value='{$data.d_area}' name="d_area[]" multiple="true" class="autofix autoeditselect" action="?m=area&a=option" selected="true">
                                {'<option value="#">全部</option>'|isallarea}
                        </select>
                </div>

                <div class="buttons mrtop40">
                        <a goto='?m=device&a=index' form="form" class="ajaxpost button normal">保存</a>
                        <a class="goback button">取消</a>
                </div>
        </form>
{/strip}