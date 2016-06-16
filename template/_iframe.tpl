<!DOCTYPE html>
<html class="lang_{$smarty.cookies.lang} can_select ">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="pragma" content="max-age=2592000">
        <meta http-equiv="cache-control" content="max-age=2592000">
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        {'before'|scriptmodule}
        <title>{"企业管理平台"|L}</title>
        <style>
            .none{
                display: none;
            }
            html,body{
                overflow: hidden;
            }
            html,body,iframe{
                border: 0;
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
            }
        </style>
    </head>
    <body>
        <span class="request none">[{$smarty.request|json_encode}]</span>
        <iframe src="?m=enterprise&a=view"></iframe>
        <script>
            window.onbeforeunload = function () {
                var request = eval($("span.request").text());
                var request = request[0];
                var e_id = request.e_id;

                $.ajax({
                    url: "?modules=enterprise&action=sync&e_id=" + e_id,
                    async: true,
                    dataType: "json",
                    success: function (result) {
                        console.log(result.msg);
                    }
                });
            };
        </script>
    </body>
</html>