{strip}
{foreach name=list item=item from=$list}
<tr class="gp_line">
    <td style="padding: 0px;display:inline;"><div style="width:30px;"><input style="margin-left: 2px;" onclick="getnum();"  type="checkbox" name="checkbox[]" value="{$item.pm_number}" class="cb" /><div><div class="total none">{$total}</div></td>
                <td title="{$item.u_name}" style="padding: 0px 5px;" class="linebr title"><div style="width:75px;">{if mb_strlen($item.u_name)<=12}{$item.u_name}{else}{$item.u_name|truncate: 12:''}... {/if}</div></td>
                <td style="padding: 0px 5px;"><div style="width:35px;">{if substr($item.u_number,6,1) ==7}{4|modwordtype}{else}{$item.u_sub_type|modwordtype}{/if}</div></td>
                <td style="padding: 0px 5px;"><div style="width:100px;">{$item.pm_number}</div></td>
                <td style="padding: 0px 5px;"><div style="width:65px;">{$item.pm_level}</div></td>
                <td style="padding: 0px 2px;"><div style="width:100px;">{if $item.u_default_pg == $item.pm_pgnumber}{"是"|L}{else}{/if}</div></td>
                <td style="padding: 0px 5px;" class="rich group" title='{$item.ug_name}'><div style="width:100px;">{if mb_strlen($item.ug_name)<=5}{$item.ug_name}{else}{$item.ug_name|truncate: 5:''}... {/if}</div></td>
                </tr>
                {foreachelse}<tr class="none"></tr>{/foreach}
                {/strip}