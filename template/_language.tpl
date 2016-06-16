<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{"选择语言"|L}</title>
        <link rel="icon" href="favicon.ico" mce_href="favicon.ico" type="image/x-icon" />


        {'language'|style}
        <!--[if lte IE 8]>{'libs/html5'|script}<![endif]-->
    </head>
    <body>

        <div class = 'buttons'>
            <h1>选择语言</h1>
            <a class="language cn_ZH" name="cn_ZH" href='?m=config&lang=cn_ZH' target="_parent">中文</a>
            <h1>Chose Language</h1>
            <a class="language en_US" name="en_US" href='?m=config&lang=en_US' target="_parent">English</a>
            <h1>選擇語言</h1>
            <a class="language zh_TW" name="zh_TW" href='?m=config&lang=zh_TW' target="_parent">繁體中文</a>
        </div>
        <section class="page" data="3">
        </section>
        <script src="script/libs.before.js"></script>
        {'before'|scriptmodule}
        {'language'|script}
    </body>
</html>