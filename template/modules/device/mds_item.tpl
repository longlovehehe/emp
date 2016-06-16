

<form class="data">
        <table class="base full">
                <tr class='head'>
                        <th width="50px"><input autocomplete="off"  type="checkbox" id="checkall" />{"全选"|L}</th>
                        <th width="50px">{"ID"|L}</th>
                        <th width="120px">{"外网地址"|L}</th>
                        <th class='none' width="120px">{"内网地址"|L}</th>
                        <th>设备名称</th>
                        <th width="50px">{"区域"|L}</th>
                        <th width="70px">{"用户总数"|L}</th>
                        <th width="70px">{"并发总数"|L}</th>
                        <th width="70px">{"SIP端口"|L}</th>
                        <th width="70px">{"状态"|L}</th>
                        <th width="150px">{"操作"|L}</th>
                </tr>
                {foreach name=list item=item from=$list}
                        <tr title="设备ID: {$item.d_id}，外网地址: {$item.d_ip1}，设备名称: {$item.d_name}，区域: {$item.d_area|mod_area_name}，用户总数: {$item.d_user}，并发总数: {$item.d_call}，状态: {$item.d_status|modDeviceStatus}，SIP端口: {$item.d_sip_port}">
                                <td><input autocomplete="off"  type="checkbox" name="checkbox[]" value="{if $item.status eq 'no'}{$item.d_id}{else}0{/if}" class="cb" {if $item.status eq 'yes'}disabled{/if} /></td>
                                <td>{$item.d_id}</td>
                                <td>{$item.d_ip1}</td>
                                <td class='none'>{$item.d_ip2}</td>
                                <td><span class="ellipsis" style="width: 60px">{$item.d_name}</span></td>
                                <td><span class="ellipsis" style="width: 60px">{$item.d_area|mod_area_name:option}</span></td>
                                <td>{$item.d_user}</td>
                                <td>{$item.d_call}</td>
                                <td>{$item.d_sip_port}</td>
                                <td>{$item.d_status|modDeviceStatus}</td>
                                <td>
                                        {if $item.status eq 'yes'}
                                                <a title='此设备下有企业在用不能编辑' class="link dis">{"编辑"|L}</a>
                                        {else}
                                                <a href="?m=device&a=device_edit&d_id={$item.d_id}" class="link">{"编辑"|L}</a>
                                        {/if}
                                        <a href="?m=device&a=device_list&device_id={$item.d_id}&do=mds&d_ip1={$item.d_ip1}" class="link mrlf5">{"使用详情"|L}</a>
                                </td>
                        </tr>
                {/foreach}
        </table>
        {if $list!=NULL}
                <div class="page none_select">
                        <div class="num">{$numinfo}</div>
                        <div class="turn">
                                <a page="{$prev}" class="prev">{"上一页"|L}</a>
                                <a page="{$next}" class="next">{"下一页"|L}</a>
                        </div>
                </div>
        </form>
{/if}