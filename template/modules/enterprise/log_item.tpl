<form class="data">
    <table class="base full">
        <tr class='head'>
            <th width="50px">{"日志级别11"|L}</th>
            <th width="50px">{"日志编号"|L}</th>
            <th width="50px">{"来源模块"|L}</th>
            <th width="50px">{"来源用户"|L}</th>
            <th width="100px">{"创建时间"|L}</th>
            <th>{"日志内容"|L}</th>
        </tr>
        {foreach name=list item=item from=$list}
        <tr title="{'日志级别'|L}{$item.el_level}<br />，{'日志编号'|L}{$item.el_id}，{'来源模块'|L}{$item.el_type|logType}，{'来源用户'|L}{$item.el_user}，{'创建时间'|L}{$item.el_time}，{'日志内容'|L}{$item.el_content}">
            <td>{$item.el_level|logLevel}</td>
            <td>{$item.el_id}</td>
            <td>{$item.el_type|logType}</td>
            <td>{$item.el_user}</td>
            <td>{$item.el_time}</td>
            <td><span class="ellipsis" style="width: 300px">{$item.el_content}</span></td>
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