{strip}

{*<tr class='head'>*}
    {*<th style="display:inline;padding: 0px ;"><div style="width:50px;"><input autocomplete="off"  type="checkbox" id="checkall" />全选</div></th>*}
{*<th>姓名</th>*}
{*<th><div style="width:120px;">号码</div></th>*}
{*<th><div style="width:120px;">所属群组</div></th>*}
{*<th><div style="width:100px;">部门</div></th>*}

{*</tr>*}

{foreach name=list item=item from=$list}
<tr class="gp_line ">
    <td style="padding: 0px;display:inline;margin:0px;"><div style="width:30px;"><div><div class="total none">{$total}</div></td>
                <td style="padding: 2px 5px;" class="linebr title" title="{$item.u_name}"><div style="width:150px;">{$item.u_name|mbsubstr:5}</div></td>
                <td style="padding: 2px 5px;"><div style="width:100px;">{$item.u_number}</div></td>
                <td  style="padding: 2px 5px;"><div style="width:130px;">
                        <select style="width:120px;" class="only_show" {if $item.u_sub_type eq 3}disabled="disabled"{/if}>
                                <option value="1">{"点击查看"|L}</option>
                           {if $item.pg_name neq ""}
                            <option style="color:#b81900;">*{$item.pg_name}</option>
                        {/if}
                    {foreach $pg_list as $key=>$val}
                        
                    {if $item.u_number eq $key  }
                    {foreach $val as $v}
                    <option>{$v.pg_name}</option>
                    {/foreach}
                    {/if}
                    {/foreach}
                        </select>
                    </div></td>
                <td  style="padding: 0px 5px;" class="rich group" title='{$item.ug_name}'><div style="width:100px;">{if mb_strlen($item.ug_name)<=5}{$item.ug_name}{else}{$item.ug_name|truncate: 5:''}... {/if}</div></td>
                </tr>
                {foreachelse}<tr class="none"></tr>{/foreach}

                {/strip}
