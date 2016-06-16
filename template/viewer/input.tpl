{foreach name=list item=item from=$list}
      <label class="title1  autocheck"><div class="title1"><input type="checkbox" title="{$item.price}"disabled="true"name="checkbox1[]" value="{$item.code}"/>{"{$item.name}"|L}</div> </label>
{/foreach}