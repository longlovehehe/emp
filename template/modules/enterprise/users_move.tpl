{strip}
    <div class="toolbar">
        <a href="?modules=enterprise&action=view&e_id={$data.e_id}" class="button ">企业信息</a>
        <a href="?modules=enterprise&action=admins&e_id={$data.e_id}" class="button ">企业管理员</a>
        <a href="?modules=enterprise&action=users&e_id={$data.e_id}" class="button active">企业用户</a>
        <a href="?modules=enterprise&action=groups&e_id={$data.e_id}" class="button">企业群组</a>
        <a href="?modules=enterprise&action=usergroup&e_id={$data.e_id}" class="button">企业通讯录</a>
        <a href="?modules=enterprise&action=export&e_id={$data.e_id}" class="button">导入导出</a>
    </div>
    <h2 class="title">企业用户转移列表</h2>
    <div class="toolbar">
        <a class="goback button">返回</a>
    </div>

    <div class="content">
        
        <h3 class="title">企业成员</h3>
        <select name="list" multiple="true" size="25" ></select>  
        <a class="button">加载更多</a>
        
        <a>选中项转移</a>
        
        <h3>企业名称</h3>
    </div>
{/strip}