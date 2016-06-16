<table class="base full">
    <tr class='head'>
        <th class='' width="50px">产品代码</th>
        <th>产品名称</th>
        <th width="100px">运营区域</th>
        <th width="50px">产品价格（单位元）</th>
        <th class="none" width="50px">产品描述</th>
        <th width="50px">管理</th>
    </tr>
    {foreach name=list item=item from=$list}
    <tr title="产品代码{$item.p_id}，产品名称{$item.p_name}，运营区域{$item.p_area|mod_area_name}，产品价格 {$item.p_price}元，产品描述{$item.p_desc}">
        <td class=''><input autocomplete="off"  type="hidden" value="{$item.p_id}" name="p_id">{$item.p_id}</td>
        <td><span class="ellipsis" style="width: 310px">{$item.p_name}</span></td>
        <td><span class="ellipsis" style="width: 90px">{$item.p_area|mod_area_name}</span></td>
        <td>{$item.p_price}</td>
        <td class="none">{$item.p_desc}</td>
        <td>
            {if $item.res==1}
            <a class="link" href="?m=product&a=p_edit&p_id={$item.p_id}">编辑</a>
            {if $item.is_used==0 }
            <a id="del" class="mrlf5 link" data="{$item.p_id}" >删除</a>
            {else}
            <a  title='此产品有用户在用,无法删除' class="link mrlf5 dis" >删除</a>
            {/if}
            {else}
            <a title='本产品区域有不被包含的区域,无法编辑' class="link dis" >编辑</a>
            <a title='本产品区域有不被包含的区域,无法删除'  class="link mrlf5 dis " >删除</a>
            {/if}
        </td>
    </tr>
    {/foreach}

</table>
<div class="page none_select">
    <div class="num">{$numinfo}</div>
    <div class="turn">
        <a page="{$prev}" class="prev">上一页</a>
        <a page="{$next}" class="next">下一页</a>
    </div>
</div>