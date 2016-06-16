

<table class="base full">
        <tr class='head'>
                <th width="50px" class="">编号</th>
                <th>区域名称</th>
                <th width="100px">管理</th>

        </tr>
        {foreach name=list item=item from=$list}
                <tr>
                        <td class="">{$item.am_id}</td>
                        <td>{$item.am_name}</td>
                        <td><a href="?m=area&a=area_edit&am_id={$item.am_id}" class="link">编辑</a>
                                <a id="del" class="mrlf5 link {if $item.status eq 'yes'}msg{/if}" data="{$item.am_id}" >删除</a>
                        </td>
                </tr>
        {/foreach}
</table>
{if $list!=NULL}
        <div class="page none_select">
                <div class="num">{$numinfo}</div>
                <div class="turn">
                        <a page="{$prev}" class="prev">上一页</a>
                        <a page="{$next}" class="next">下一页</a>
                </div>
        </div>
{/if}