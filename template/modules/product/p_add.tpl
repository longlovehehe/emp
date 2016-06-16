{strip}
        <h2 class="title">{$title}</h2>
        <form id="form" class="base mrbt10" action="?modules=product&action=p_addData" method="post">
                <input autocomplete="off"  name="page" value="0" type="hidden" />
                <input autocomplete="off"  type="hidden" id="rad" class="radd">
                <div class="block">
                        <label class="title">产品名称：</label>
                        <input autocomplete="off"  maxlength="32" name="p_name" type="text" required="true" />
                </div>
                <div class="block">
                        <label class="title">运营区域：</label>
                        <select name="p_area[]" multiple="TRUE" class="autofix" action="?m=area&a=option" selected="true" >
                                {'<option value="#">全部</option>'|isallarea}
                        </select>
                </div>
                <div class="block">
                        <label class="title">产品价格：</label>
                        <input autocomplete="off"  maxlength="32" name="p_price" type="text" required="true" digits="true" range="[0,2147483647]"  />
                </div>
                <div class="block">
                        <label class="title">产品描述：</label>
                        <input autocomplete="off"  name="p_desc" type="text" required="true" maxlength="1000" p_desc="TRUE" />
                </div>
                <div class="block">
                        <label class="title">功能列表：</label>
                </div>
                {foreach key=key name=res_1 item =item_1 from=$result['status']}
                        <div class="block">
                                <label class="title">{$key}:</label>   
                                <input autocomplete="off"  type="hidden" name='rad_id' value="{$item_1['id']}" /> 
                                {foreach name=res_2 item =item_2 from=$item_1 key=key_1}
                                        {if $key_1  == "0"}
                                                <input autocomplete="off"  type="radio" name="{$item_1['id']}" value="{$item_2[0]}" class='rad'/>{$item_2[1]}
                                        {/if}
                                        {if $key_1  != "id"}
                                                <input autocomplete="off"  type="radio" name="{$item_1['id']}" value="{$item_2[0]}" class='rad'/>{$item_2[1]}
                                        {/if}
                                {/foreach}<br>           
                        </div>
                {/foreach}
                
                <p class="info_text">区域部分单击单选，按住ctrl同时多选，按住shift连续选择多项</p>
                <div class="buttons mrtop40">
                        <a goto="?m=product&a=index" form="form" class="ajaxpost_s button normal" >保存</a>
                        <a class="goback button">取消</a>
                </div>
        </form>
{/strip}
<script  {"type='ready'"}>
        $(document).ready(function() {
                $("a.ajaxpost_s").click(function() {
                        var arrayObj = new Array();
                        var temp = document.getElementsByName("rad_id");
                        for (var i = 0; i < temp.length; i++)
                        {
                                var arr = $("input[name='rad_id']")[i].value;
                                arrayObj[arr] = $("input[name=arr]").attr("checked", true);

                        }
                        if ($("#form").valid()) {
                                var form = $("a.ajaxpost_s").attr("form");
                                var url = $("#" + form).attr("action");
                                var goto = $("a.ajaxpost_s").attr("goto");
                                $.ajax({
                                        url: url,
                                        method: "POST",
                                        dataType: "json",
                                        data: $("#form").serialize() + "&arrayObj=" + arrayObj[arr],
                                        success: function(result) {
                                                 if (result.status == 0) {
                                                        notice(result.msg, goto);
                                                } else {
                                                        notice(result.msg);
                                                }
                                        }
                                });
                        }
                });
                (function() {
                        $("div.block .rad[value=0]").trigger("click");
                })();
        });
</script>
