{foreach name=list item=item from=$list}
<option value="{$item.am_id}">{$item.am_name}</option>
{/foreach}