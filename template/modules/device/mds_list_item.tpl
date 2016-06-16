{strip}
        <table class="base full">
                <tr class='head'>
                        <th width="50px">企业名称</th>
                        <th width="100px">企业分配用户数</th>
                        <th width="100px">企业分配并发数</th>
                </tr>
                {foreach name=list item=item from=$list}
                        <tr>
                                <td>{$item.e_name}</td>
                                <td>{$item.e_mds_users}</td>
                                <td>{$item.e_mds_call}</td>
                        </tr>
                {/foreach}
        </table>
        <div class="page none_select">
                <div class="num">{$page.numinfo}</div>
                <div class="turn">
                        <a page="{$page.prev}" class="prev">上一页</a>
                        <a page="{$page.next}" class="next">下一页</a>
                </div>
        </div>
{/strip}