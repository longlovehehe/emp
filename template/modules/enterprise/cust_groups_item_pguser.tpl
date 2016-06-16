
{strip}
{foreach name=list item=item from=$list}
<tr class="gp_line" {if $item.u_number eq $info.c_pg_creater_num}style="color:#A43838"{/if}>
    <td style="padding: 0px;display:inline;"><div style="width:30px;"><div><div class="total none">{$total}</div></td>
                <td title="{$item.u_name}" style="padding: 5px 5px;" class="linebr title"><div style="width:200px;">{if mb_strlen($item.u_name)<=12}{$item.u_name}{else}{$item.u_name|truncate: 12:''}... {/if}</div></td>
                <td style="padding: 0px 5px;"><div style="width:150px;">{$item.u_number}</div></td>
                <td style="padding: 0px 5px;" class="none"><div style="width:65px;">{$item.u_level}</div></td>
                <td style="padding: 0px 5px;" class="rich group" title='{$item.ug_name}'><div style="width:145px;">{if mb_strlen($item.ug_name)<=5}{$item.ug_name}{else}{$item.ug_name|truncate: 5:''}... {/if}</div></td>
                </tr>
                {foreachelse}<tr class="none"></tr>{/foreach}
                {/strip}


