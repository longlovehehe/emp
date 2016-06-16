<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{"系统初始化"|L}</title>
        <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />


        {'reset|init|animate'|style}
        <!--[if lte IE 8]>{'libs/html5'|script}<![endif]-->
    </head>
    <body>
        <section class="page " data="2">
            <h1>{"基本信息填写"|L}</h1>
            <form target="hiddenframe">
                <input name='date' value="{$smarty.request.date}" type="hidden" />
                <input autocomplete="off"  name="m" value="common" type="hidden" />
                <input autocomplete="off"  name="shell" value="1" type="hidden" />
                <label>
                    <span>{"数据库连接方式"|L}：</span>
                    <label>
                        <input autocomplete="off"  name="dbtype" type="radio" value="localhost" checked="checked" />{"本地"|L}
                    </label>
                    <label>
                        <input autocomplete="off"  name="dbtype" type="radio" value="remote" />{"远程"|L}
                    </label>
                </label>

                <label class="dbaddr hide">
                    <span>{"数据库地址"|L}：</span>
                    <input autocomplete="off"  value="" name="dbhost" type="text" ip="true" required="true" disabled="disabled" />
                </label>
                <label class="none">
                    <span>{"端口"|L}：</span>
                    <input autocomplete="off"  value="5432" name="dbport" type="text" required="true" digits="true" />
                </label>
                <label  class="none">
                    <span>{"名称"|L}：</span>
                    <input autocomplete="off"  value="OMPDB" name="dbname" type="text" required="true" />
                </label>
                <label  class="none">
                    <span>{"帐号"|L}：</span>
                    <input autocomplete="off"  value="ompuser" name="dbuser"  type="text" required="true" />
                </label>
                <label  class="none">
                    <span>{"密码"|L}：</span>
                    <input autocomplete="off"  value="omppasswd" name="dbpwd" type="text" required="true" />
                </label>
                <hr   class="none" />
                <label   class="none">
                    <span>{"超级管理员帐号"|L}：</span>
                    <input autocomplete="off"  type="text" value="admin" readonly="true" />
                </label>
                <label   class="none">
                    <span>{"密码"|L}：</span>
                    <input autocomplete="off"  name="usrpwd" type="password" value="" required="true" id="pwd"/>
                </label>
                <label   class="none">
                    <span>{"重复密码"|L}：</span>
                    <input autocomplete="off"  type="password" value="" required="true" equalTo="#pwd" />
                </label>
                <label class="none">
                    <span>{"应用模式"|L}：</span>
                    <select name="debug">
                        <option value="TRUE">{"工厂模式"|L}</option>
                        <option value="FALSE">{"生产模式"|L}</option>
                    </select>
                </label>
            </form>
            <div class="buttons">
                <a id="page2">{"确认，下一步"|L}</a>
            </div>
        </section>

        <section class="page" data="3">
        </section>
        {'before'|scriptmodule}
        {'init'|script}
    </body>
</html>