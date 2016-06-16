{strip}
        <div class="toolbar mactoolbar">
                <a href="?m=enterprise&a=index" class="button ">企业管理</a>
                <a href="?m=enterprise&a=allusers" class="button active">用户搜索</a>
                <a href1="?m=device&a=vcrs" class="button none">车辆管理</a>
        </div>
        <h2 class="title">{$title}</h2>
        <div class="toolbar">
                <form action="?m=enterprise&action=all_user_item" id="form" method="get">
                        <input autocomplete="off"  name="page" value="0" type="hidden" />
                        <h3 class="title none">基本属性</h3>
                        <div class="line">
                                <label>用户号码：</label>
                                <input autocomplete="off"  value="{$smarty.get.u_number}" class="autosend" name="u_number" type="text" />
                        </div>
                        <div class="line none">
                                <label>搜索方式：</label>
                                <select value='1' class='autoedit ' name='search_type'>
                                        <option value='0'>方式一[联合搜索]</option>
                                        <option value='1'>方式二[贪婪搜索]</option>
                                </select>
                        </div>

                        <a form="form" class="button submit ">查询</a>

                        <h3 class="title none">详细属性</h3>
                        <div class="detailed none">
                                <div class="line">
                                        <label>头像：</label>
                                        <select name="u_pic">
                                                <option value="">全部</option>
                                                <option value="1">有头像</option>
                                                <option value="0">无头像</option>
                                        </select>
                                </div>
                                <div class="line">
                                        <label>性别：</label>
                                        <select name="u_sex">
                                                <option value="">全部</option>
                                                <option value="1">男</option>
                                                <option value="0">女</option>
                                        </select>
                                </div>

                                <div class="block">
                                        <label>终端类型：</label>
                                        <input autocomplete="off"  class="autosend" name="u_terminal_type" type="text" />
                                </div>
                                <div class="block">
                                        <label>机型：</label>
                                        <input autocomplete="off"  class="autosend" name="u_terminal_model" type="text" />
                                </div>
                                <div class="block">
                                        <label>IMSI：</label>
                                        <input autocomplete="off"  class="autosend" name="u_imsi" type="text" />
                                </div>
                                <div class="block">
                                        <label>IMEI：</label>
                                        <input autocomplete="off"  class="autosend" name="u_imei" type="text" />
                                </div>
                                <div class="block">
                                        <label>ICCID：</label>
                                        <input autocomplete="off"  class="autosend" name="u_iccid" type="text" />
                                </div>
                                <div class="block">
                                        <label>MAC：</label>
                                        <input autocomplete="off"  class="autosend" name="u_mac" type="text" />
                                </div>
                                <div class="block">
                                        <label>蓝牙标识号：</label>
                                        <input autocomplete="off"  class="autosend" name="u_zm" type="text" />
                                </div>
                        </div>
                </form>
        </div>

        <div class="content"></div>
{/strip}