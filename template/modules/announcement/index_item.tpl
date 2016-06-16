
{if $smarty.session.eown.om_id != 'admin'}
        <p>提示讯息：你在这里只能看到你自己发布的公告，以及草稿</p>
{/if}

<form class="data">
        <table class="base full">
                <tr class='head'>
                        <th width="200px">公告标题</th>
                        <th width="50px">可见区域</th>
                        <th width="50px">状态</th>
                        <th width="100px">发布时间</th>
                                {'<th width="50px">发布人</th>'|isadmin}
                        <th width="50px">操作</th>
                </tr>
                {foreach name=list item=item from=$list}
                        <tr title="公告标题: {$item.an_title}，可见区域: {$item.an_area|mod_area_name}，状态: {$item.an_status|an_status}，发布时间: {$item.an_time}">
                                <td>
                                        <input autocomplete="off"  type="hidden" value="{$item.an_area_id}" name="an_area_id">
                                        <span class="ellipsis" style="width: 280px">
                                                <a class="alink" href="?m=announcement&a=an_details&an_id={$item.an_id}">{$item.an_title}</a>
                                        </span>
                                </td>
                                <td>
                                        <span class="ellipsis" style="width: 50px">{$item.an_area|mod_area_name:option}</span>
                                </td>
                                <td>{$item.an_status|an_status}</td>
                                <td>{$item.an_time}</td>
                                <td class='{"none"|notadmin}'>{$item.an_user}</td>
                                <td>
                                        <a class="link" href="?m=announcement&a=an_edit&an_id={$item.an_id}">编辑</a>
                                        &nbsp;
                                        <a id="del" class="link" data="{$item.an_id}">删除</a>
                                </td>
                        </tr>
                {/foreach}
        </table>
        {if $list!=NULL}
                <div class="page none_select">
                        <div class="num">{$numinfo}</div>
                        <div class="turn">
                                <a page="{$prev}" class="prev">上一页</a>
                                <a page="{$next}" class="next">下一页</a>
                        </div>
                </div>
        </form>

{/if}