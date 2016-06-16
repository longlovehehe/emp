<?php

/**
 * 企业导入导出控制器
 * @category EMP
 * @package EMP_Enterprise_contorl
 * @require {@see contorl}
 */
class EnterpriseExportContorl extends contorl
{

    public function __construct ()
    {
        parent::__construct ();
    }

    function export ()
    {
        $this->smarty->assign ( 'data' , $_REQUEST );
        $this->render ( 'modules/enterprise/export.tpl' , '企业用户导出' );
    }

    function users_export ()
    {
        $this->render ( 'modules/enterprise/users_export.tpl' , '企业用户导出' );
    }

    function groups_export ()
    {
        $this->render ( 'modules/enterprise/groups_export.tpl' , '企业用户组导出' );
    }

}
