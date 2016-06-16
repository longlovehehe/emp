{strip}
        <h2 class="title">新增设备</h2>

        <form id="form" class="base mrbt10" action="?modules=device&action=mds_save">
                <div class="block">
                        <div class="radioset none" id="radioset" value="{$data.d_type}">
                                <input autocomplete="off"  value="mds" type="radio" id="radio_mds" name="d_type"  checked="checked" /><label for="radio_mds">GQT-Server</label>
                                <input autocomplete="off"  value="vcr" type="radio" id="radio_vcr" name="d_type" /><label for="radio_vcr">VCR</label>
                                <input autocomplete="off"  value="vcrs" type="radio" id="radio_vcrs" name="d_type" /><label for="radio_vcrs">VCR-S</label>
                        </div>
                </div>
                <div class="block">
                        <label class="title">设备名称：</label>
                        <input autocomplete="off"   maxlength="32" name="d_name" type="text" required="true" />
                </div>
                <div class="block">
                        <label class="title">设备外网地址：</label>
                        <input autocomplete="off"   maxlength="32" name="d_ip1" type="text" required="true" ip="true" />
                </div>
                <div class="block">
                        <label class="title">设备外网端口：</label>
                        <input autocomplete="off"   maxlength="32" value="2001" name="d_port1" type="text" required="true" digits ="true" range="[0,65535]" />
                </div>
                <div class="block">
                        <label class="title">设备内网地址：</label>
                        <input autocomplete="off"   maxlength="32" name="d_ip2" type="text" ip="true" />
                </div>
                <div class="block">
                        <label class="title">设备内网端口：</label>
                        <input autocomplete="off"   maxlength="32" value="2001" name="d_port2" type="text" digits ="true" range="[0,65535]" />
                </div>

                <div class="block">
                        <label class="title">设备所属区域：</label>
                        <select name="d_area[]" class="autofix" multiple="TRUE" action="?m=area&a=option" selected="true">
                                {'<option value="#">全部</option>'|isallarea}
                        </select>
                </div>

                <p class="info_text">区域部分单击单选，按住ctrl同时多选，按住shift连续选择多项</p>
                <div class="buttons mrtop40">
                        <a goto="?m=device&a=index" form="form" class="ajaxpost button normal">保存</a>
                        <a class="goback button">取消</a>
                </div>
        </form>
{/strip}