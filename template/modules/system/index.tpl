
<!-- 基本信息 -->
<div class="userinfo">
    <h2 class="title _3_jpg" ><em class='none'>{"帐号信息"|L}</em></h2>
    <div class="uname">{"姓名"|L}：{$smarty.session.eown.om_id}</div>
    <ul class="list admintype">
        <li><label class='title'>{"管理员级别"|L}：</label>{$smarty.session.eown.om_id|level}</li>
        <li title='{$smarty.session.eown.om_area|mod_area_name}'><label class='title'>{"管辖区域"|L}：</label><span class='ellipsis2' style='width: 240px;'>{$smarty.session.eown.om_area|mod_area_name}</span></li>
        <li><label class='title'>{"管辖企业"|L}：</label>{$en}</li>
        <li><label class='title'>{"管辖设备"|L}：</label>{$device}</li>
    </ul>
    <!--帐号状态-->
    <ul class="list logininfo">
        <li>{"上次登录地址"|L}：{$smarty.session.eown.om_lastlogin_ip}</li>
        <li>{"上次登录时间"|L}：{$smarty.session.eown.om_lastlogin_time}</li>
    </ul>
</div>
<div class="anbbs">
    <h2 class="title" >{"系统公告"|L}{$style1}</h2>

    <p class='info none'>{"提示：公告往下滚动，查看更多"|L}</p>
    <form class="none" id="form" action="?m=system&a=index_item" method="post" >
        <div  class="toolbar ">
            <input autocomplete="off"  name="page" value="-1" type="hidden" />
            <input autocomplete="off"  type="hidden" value="10" name="num" />
            <a form="form" class="submit none"></a>
        </div>
    </form>
    <div class="content "></div>
</div>
