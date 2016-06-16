<?php

class EnterpriseLogContorl extends contorl {

	public function __construct() {
		parent::__construct();
	}

	public function log() {
		$this->render('modules/enterprise/log.tpl', L('企业日志'));
	}

	public function log_item() {
		$smarty = $this->smarty;
		$page = new page($_REQUEST);
		$log = new log($_REQUEST);
		$page->setTotal($log->getTotal());
		$list = $log->getList($page->getLimit());
		$numinfo = $page->getNumInfo();
		$prev = $page->getPrev();
		$next = $page->getNext();
		$smarty->assign('list', $list);
		$smarty->assign('numinfo', $numinfo);
		$smarty->assign('prev', $prev);
		$smarty->assign('next', $next);
		$smarty->display('modules/enterprise/log_item.tpl');
	}

}
