{if $smarty.request.d_area == '@'}
        <option value="" d_user="0" d_call="0">请先选择一个区域</option>        
{else}
        {foreach name=list item=item from=$list}
                <option value="{$item.d_id}" d_user="{$item.diff_user|modusercall}" d_call="{$item.diff_call}">设备名称：{$item.d_name}【{$item.d_ip1}】 可用用户数：{$item.diff_user|modusercall} | 可用并发数：{$item.diff_call}</option>
        {foreachelse}
                <option value="" d_user="0" d_call="0">该区域下没有可使用设备</option>        
        {/foreach}
{/if}


