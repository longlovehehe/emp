
<p class='info none'>需要输入完整的用户号码</p>
<p class='info none'>总共耗时 {$s} 毫秒</p>

{if $smarty.request.search_type == 0}
        <div class="page none_select">
                <div class="num">{$numinfo}</div>
                <div class="turn">
                        <a page="{$prev}" class="prev">上一页</a>
                        <a page="{$next}" class="next">下一页</a>
                </div>
        </div>
{else}
        <p class='info none'>贪婪搜索：将找寻每个企业用户，直到有企业符合条件，返回该企业前50条记录，便停止继续搜索</p>
{/if}
<form class="data">
        <table class="base full">
                <tr class='head'>
                        <th width="100px">用户号码</th>
                        <th>姓名</th>
                        <th class="rich" width="100px">所属企业</th>
                        <th class="rich" width="100px">企业名称</th>
                        <th class="rich" width="100px">用户类型</th>
                        <th class="rich" width="100px">用户详情</th>
                </tr>

                {foreach name=list item=item from=$list}
                        <tr>
                                <td>{$item.u_number}</td>
                                <td>{$item.u_name}</td>
                                <td>{$item.ep.e_id}</td>
                                <td>{$item.ep.e_name}</td>
                                <td class="rich">{$item.u_sub_type|modtype}</td>
                                <td><a href='?m=enterprise&a=users&e_id={$item.ep.e_id}&u_number={$item.u_number}' class='link blue'>用户详情</a></td>
                        </tr>
                {/foreach}
        </table>
</form>
