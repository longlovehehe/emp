
<div class="toolbar">
    <a href="?modules=system&action=resetpassword" class="button">{"密码修改"|L}</a>
</div>

<div class="toptoolbar">
    <a href="?modules=system&action=person&do=edit" class="button">{"修改个人信息"|L}</a>
</div>

<!-- 基本信息 -->
<ul class="list">
    <li>
        <span>{"姓名"|L}：</span>
        <span>{$own.om_id}</span>
    </li>
    <li>
        <span>{"管理员级别"|L}：</span>
        <span>{$own.om_type}</span>
    </li>
    <li>
        <span>{"管理区域"|L}：</span>
        <span>{$own.om_area}</span>
    </li>
    <li>
        <span>{"管辖企业"|L}：</span>
        <span></span>
    </li>
    <li>
        <span>{"管辖设备"|L}：</span>
        <span></span>
    </li>
</ul>
<ul class="list">
    <li>
        <span>{"上次登录地址"|L}：</span>
        <span>{$own.om_lastlogin_ip}</span>
    </li>
    <li>
        <span>{"上次登录时间"|L}：</span>
        <span>{$own.om_lastlogin_time}</span>
    </li>
</ul>
