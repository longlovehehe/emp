<?php

/**
 * 企业管理员控制器
 * @category EMP
 * @package EMP_Enterprise_contorl
 * @require {@see contorl} {@see page} {@see enterprise} {@see admins}
 */
class EnterpriseAdminContorl extends contorl {

	public function __construct() {
		parent::__construct();
		$this->page = new page($_REQUEST);
	}

	function admins() {
		$enterprise = new enterprise($_REQUEST);
		$data = $enterprise->getByid();
		$data = array_merge($data, $_REQUEST);

		$this->smarty->assign('data', $data);
		$this->smarty->assign('ep', $data);
		$this->render('modules/enterprise/admins.tpl', L('企业管理员'));
	}

	function admins_add() {

		$enterprise = new enterprise($_REQUEST);
		$data = $enterprise->getByid();
		$data["do"] = "add";
		$this->smarty->assign('data', $data);
		$this->render('modules/enterprise/admins_add.tpl', '新增企业管理员');
	}

	function admins_edit() {

		$enterprise = new enterprise($_REQUEST);
		$admins = new admins($_REQUEST);
		$data = $admins->getbyid();
		$enterprise_item = $enterprise->getByid();
		$data["e_id"] = $enterprise_item["e_id"];
		$data["e_name"] = $enterprise_item["e_name"];
		$data["do"] = "edit";
		$data["em_id"] = $_REQUEST["em_id"];
		$this->smarty->assign('data', $data);
		$this->render('modules/enterprise/admins_add.tpl', '编辑管理员');
	}

	function admins_item() {
		$admins = new admins($_REQUEST);
		//$data	=	$admins->getbyid();

		$this->page->setTotal($admins->getTotal());
		$list = $admins->getList($this->page->getLimit());
		$numinfo = $this->page->getNumInfo();
		$prev = $this->page->getPrev();
		$next = $this->page->getNext();
		$this->smarty->assign('list', $list);
		$this->smarty->assign('numinfo', $numinfo);
		$this->smarty->assign('prev', $prev);
		$this->smarty->assign('next', $next);
		$this->smarty->assign('data', $_REQUEST);
		$this->htmlrender('modules/enterprise/admins_item.tpl');
		exit();
	}

	function admins_del() {
		$enterprise = new enterprise($_REQUEST);
		$tools = new tools();
		$admins = new admins($_REQUEST);

		$enterprise->changeSync(true, 1);
		$list = $tools->get("list");
		$result["count"] = $admins->delList($list);
		echo $result["count"];
		exit();
	}

	function admins_save() {
		$enterprise = new enterprise($_REQUEST);
		$tools = new tools();
		$admins = new admins($_REQUEST);

		$enterprise->changeSync(true, 1);
		$this->smarty->assign('title', "编辑管理员");
		$tools->show($admins->save());
	}

}
