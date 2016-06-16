<form class="data">
    <table class="base full">
        <tr class='head'>
            <th width="50px"><input autocomplete="off"  type="checkbox" id="checkall" />{"全选"|L}</th>
            <th width="50px">{"编号"|L}</th>
            <th>{"企业名称"|L}</th>
            <th class="rich none" width="100px">{"区域"|L}</th>
            <th class="rich " width="100px">{"状态"|L}</th>
            <th class="rich none" width="120px">GQT-Server</th>
            <th class="rich none" width="120px">VCR</th>
            <th width="50px">{"操作"|L}</th>
        </tr>
        {foreach name=list item=item from=$list}
        <tr title='企业编号{$item.e_id} 企业名称{$item.e_name} 区域{$item.e_area|mod_area_name} 状态{$item.e_status|modifierStatus} GQT-Server{$item.mds_d_ip1}'>
            <td><input autocomplete="off"  type="checkbox" name="checkbox[]" value="{$item.e_id}" class="cb" /></td>
            <td>{$item.e_id}</td>
            <td><span class='ellipsis' style='width: 430px'>{$item.e_name}</span></td>
            <td class="rich none">{$item.e_area|mod_area_name}</td>
            <td class="rich ">{$item.e_status|modifierStatus}</td>
            <td class="rich none">{$item.mds_d_ip1}</td>
            <td class="rich none">{$item.vcr_d_ip1}</td>
            <td><a href="?m=enterprise&a=view&e_id={$item.e_id}" class="link">{"管理"|L}</a></td>
        </tr>
        {/foreach}
    </table>
    <div class="page none_select">
        <div class="num">{$numinfo}</div>
        <div class="turn">
            <a page="{$prev}" class="prev">{"上一页"|L}</a>
            <a page="{$next}" class="next">{"下一页"|L}</a>
        </div>
    </div>
</form>
