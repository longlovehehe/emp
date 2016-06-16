<?php

class EnterpriseUserGroupContorl extends contorl {

	public $enterprise;
	public $usergroup;
	public $users;

	public function __construct() {
		parent::__construct();
		$this->enterprise = new enterprise($_REQUEST);
		$this->usergroup = new usergroup($_REQUEST);
		$this->users = new users($_REQUEST);
		$this->page = new page($_REQUEST);
	}

	function usergroup() {
		$num = $this->users->getTotal();
		$this->enterprise_item = $this->enterprise->getByid();
		$data['e_id'] = $this->enterprise_item['e_id'];
		$data['e_name'] = $this->enterprise_item['e_name'];
		$this->smarty->assign('data', $data);
		$this->smarty->assign('num', $num);
		$this->smarty->assign('ep', $this->enterprise_item);
		$this->render('modules/enterprise/usergroup.tpl', L('企业部门'), array(), array('tree'));
	}

	/**
	 * 企业部门导出
	 * 导出选中部门的用户的全部成员表
	 * @todo 将header的内容提取到公共com的header里去
	 */
	function usergroup_item_export() {
		$data = array();
		$data['u_ug_id'] = $_REQUEST['u_ug_id'];
		$data['e_id'] = $_SESSION['ep']['e_id'];
		$users = new users($data);
		$list = $users->getList();

		$header = array();
		$header[] = L("号码");
		$header[] = L("名称");

		$data_groups = array();
		$pg_groups = array();
		$pg_array = array();
		$groups_list1 = array();
		$data_groups['e_id'] = $_SESSION['ep']['e_id'];
		foreach ($list as $key => $value) {
			$ptdata = array();
			$ptdata['e_id'] = $_REQUEST['e_id'];
			$ptdata['pm_number'] = $value['u_number'];
			$pttm = new pttmember($ptdata);
			$pttmlist = $pttm->getList();
			foreach ($pttmlist as $k => $val) {
				$pg_groups[] = $val['pm_pgnumber'];
			}
		}

		$pg_array = array_unique($pg_groups);

		$groups = new groups($data);
		$groups_list = $groups->getList();

		foreach ($groups_list as $k2 => $v2) {
			foreach ($pg_array as $k1 => $v1) {
				if ($v1 == $v2['pg_number']) {
					$groups_list1[] = $v2;
				}
			}
		}
		$groups_list1 = array_slice($groups_list1, 0, 254);
		$headerlist = array();
		foreach ($groups_list1 as $key => $value) {
			$headerlist[$value['pg_number']] = array("id" => $key, "name" => $value['pg_name']);
		}

		$excel = new PHPExcel();
		/** 设置表头 */
		$excel->getActiveSheet()->setCellValue('A1', L('号码'));
		$excel->getActiveSheet()->setCellValue('B1', L('名称'));
		foreach ($groups_list1 as $key => $value) {
			$col = PHPExcel_Cell::stringFromColumnIndex($key + 2);
			$excel->getActiveSheet()->setCellValue($col . 1, L("群组") . " " . ($key + 1));
		}

		/** 用户数据填充 */
		$n = 2;
		foreach ($list as $key => $value) {
			$ptdata = array();
			$ptdata['e_id'] = $_SESSION['ep']['e_id'];
			$ptdata['pm_number'] = $value['u_number'];
			$pttm = new pttmember($ptdata);

			$pttmlist = $pttm->getList();
			$excel->getActiveSheet()->setCellValueExplicit('A' . $n, $value['u_number'], PHPExcel_Cell_DataType::TYPE_STRING);
			$excel->getActiveSheet()->setCellValueExplicit('B' . $n, $value['u_name'], PHPExcel_Cell_DataType::TYPE_STRING);
			$i = 0;
			foreach ($pttmlist as $key1 => $value1) {
				$col = PHPExcel_Cell::stringFromColumnIndex(($headerlist[$value1['pm_pgnumber']]['id'] + 2));
				$excel->getActiveSheet()->setCellValue($col . $n, $headerlist[$value1['pm_pgnumber']]['name']);
				$i++;
			}
			$n++;
		}
		$output = new PHPExcel_Writer_Excel5($excel);

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check = 0, pre-check = 0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="' . 'files.xls"');
		header("Content-Transfer-Encoding:binary");
		$output->save('php://output');
	}

	function usergroup_item() {
		$result = $this->usergroup->getlist();
		$this->smarty->assign('list', $result);
		$this->htmlrender('modules/enterprise/tree.tpl');
		exit();
	}

	function usergroup_save() {
		$this->enterprise->changeSync(true, 8);
		try
		{
			$msg['result'] = $this->usergroup->save();
			$msg['status'] = 0;
		} catch (Exception $ex) {
			if ($ex->getCode() == 23505) {
				$msg['msg'] = L('部门名称已存在');
			} else {
				$msg['msg'] = $ex->getMessage();
			}
			$msg['status'] = -1;
		}
		$this->tools->show($msg);
	}

	function usergroup_del() {
		$this->enterprise->changeSync(true, 8);
		echo json_encode($this->usergroup->del());
		exit();
	}

}
