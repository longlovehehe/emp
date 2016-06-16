<!DOCTYPE html>
{strip}
<!--[if lt IE 7 ]> <html class="ie6 lang_{$smarty.cookies.lang} can_select"> <![endif]-->
<!--[if IE 7 ]><html class="ie7 lang_{$smarty.cookies.lang} can_select"> <![endif]-->
<!--[if IE 8 ]><html class="ie8 lang_{$smarty.cookies.lang} can_select"><![endif]-->
<!--[if IE 9 ]><html class="ie9 lang_{$smarty.cookies.lang} can_select"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html class="lang_{$smarty.cookies.lang} can_select"><!--<![endif]-->
    <head>
        <meta charset="UTF-8">

        <meta http-equiv="pragma" content="max-age=2592000">
        <meta http-equiv="cache-control" content="max-age=2592000">

        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />
        <title>{"{$title}"|L}</title>
        {$style|style}
        {'before'|scriptmodule}
        <link  href="style/layout.css" rel="stylesheet" type="text/css" />
        <link  href="style/ie6.layout.adapter.css" rel="stylesheet" type="text/css" />
        <link  href="style/css.css" rel="stylesheet" type="text/css" />
        <!--[if lte IE 8]>{'libs/html5'|scriptnocompile}<![endif]-->
    </head>
    <body class="{$smarty.get.m}" scroll="yes">
        <div class="nosuper none"></div>
        <span class="request none">[{$smarty.request|json_encode}]</span>
        <div class="lang">
            <a class="lang" data="zh_CN">中文版</a>
            <span class='sep'>|</span>
            <a class="lang" data="en_US">English</a>
            <span class='sep'>|</span>
            <a class="lang" data="zh_TW">繁體中文</a>
        </div>

        <div class="header">
            <header>
                <div class="{if $smarty.cookies.lang eq en_US}logo1{else}logo{/if}"></div>
                <h1 class="title {if $smarty.cookies.lang eq en_US}_GQT_icon_logo_1_en_png{else}_GQT_icon_logo_1_png{/if}">
                    <a class="e_name">{$smarty.session.ep.e_name}</a>
                    <a class="title">{"企业管理平台"|L}</a>
                </h1>
                <div class="login_tips">
                    <div class="account">
                        <a href="javascript:void(0);" class="">{$smarty.session.eown.em_id}</a>
                        {if isset($smarty.session.check) && $smarty.session.check eq 'isoftstone'}
                        {else}
                            <a href="?m=system&a=resetpassword" class="logout">{"修改密码"|L}</a>
                        {/if}
                        <a data="?m=logout" id="logout" class="logout">{"注销"|L}</a>
                        <a href="?m=help&a=index" target="_blank" class="link help none">{"帮助模块"|L}</a>
                    </div>
                </div>
            </header>
        </div>
        <section class="content" >
            <div class="nav">
                <nav>
                    <ul class="menu">
                     <li><a href="?m=enterprise&a=view" class="view resetpassword"><div >{"首页"|L}</div></a></li>
                        <li><a href="?m=enterprise&a=users" class="users users_save">{"用户"|L}</a></li>
                        <li><a href="?m=enterprise&a=groups" class="groups groups_view groups_edit groups_view_edit groups_view_add groups_add">{"群组"|L}</a></a></li>
                        <li><a href="?m=enterprise&a=usergroup" class="usergroup">{"部门"|L}</a></li>
                        {if $url neq ""}
                            <li><a href="javascript:void(0);"  class="report" id="report-jump" action="{$url}">{"数据报表"|L}</a></li>
                        {/if}
                        <li> <a href="?m=enterprise&a=log" class="log">{"日志"|L}</a></li>
                        {if $smarty.session.ep.e_sync > 0}
                        <a class='sync' title="{'点击可将企业信息下发至服务器，使之立即生效'|L}">{"数据同步"|L}</a>
                        {/if}
                </ul>
                </nav>
            </div>

            <div class="minipage autosize" height="140" min="0">
                <div class="pagecontent mini_{$request.modules}_{$request.action} ">
                    {$content}
                </div>
            </div>
            <div id="fade" class="black_overlay"></div>

        </section>
        <footer>
            <p class='hidden'>Copyright (C) http://www.zed-3.com.cn/, All Rights Reserved</p>
            <p class='hidden'>{"捷思锐科技版权所有 京ICP备09032422号"|L}</p>
        </footer>
        {$script|scriptafter}
        <script src="script/com.js"></script>
    </body>
</html>
{/strip}
