<?php

class enterprise extends db {

	public $page;

	public function enterprise($data) {
		parent::__construct();
		$this->data = $data;
		$this->page = new page();
	}

	public function updateStatus($data) {
		if ($data[0] == '' || $data[1] == '') {
			throw new Exception('enterprise_id is null or enterprise_status is null', -1);
		}

		$sql = 'UPDATE "T_Enterprise" SET e_status=? WHERE e_id=?';
		$sth = $this->pdo->prepare($sql);
		try
		{
			$sth->execute($data);
		} catch (Exception $ex) {
			// throw new Exception($ex->getMessage(), -1);
			$this->log(DL("同步数据失败"), 1, 1);
		}
	}

	public function getDeviceList() {
		if ($this->data["do"] == "mds") {
			$pagesql = "SELECT count(e_mds_id) as total FROM \"T_Enterprise\" WHERE \"e_mds_id\"=" . $this->data["device_id"];
		} else {
			$pagesql = "SELECT count(e_vcr_id) as total FROM \"T_Enterprise\" WHERE \"e_vcr_id\"=" . $this->data["device_id"];
		}

		$page = $this->page->fastGetPage($pagesql, $this->pdo, $this->data);
		$result["page"] = $page;
		switch ($this->data["do"]) {
			case "mds":
				$sql = "SELECT e_name,e_mds_users,e_mds_call FROM \"T_Enterprise\" WHERE e_mds_id=:e_mds_id";
				break;
			case "vcr":
				$sql = "SELECT e_name,e_vcr_audiorec,e_vcr_videorec,e_vcr_space FROM \"T_Enterprise\" WHERE e_vcr_id=:e_vcr_id";
				break;
		}

		$sql = $sql . $page["limit"];

		$sth = $this->pdo->prepare($sql);
		switch ($this->data["do"]) {
			case "mds":
				$sth->bindValue(':e_mds_id', $this->data["device_id"], PDO::PARAM_INT);
				break;
			case "vcr":
				$sth->bindValue(':e_vcr_id', $this->data["device_id"], PDO::PARAM_INT);
				break;
		}

		$sth->execute();
		$result["fetchall"] = $sth->fetchAll();
		return $result;
	}

	function save() {
		$this->data["e_create_time"] = date("Y-m-d H:i:s", time());

		//检查并发数
		if ($this->data["e_id"] != "") {
			$sql = <<<SQL
                        SELECT count(*) as total FROM "T_User" WHERE u_e_id ={$this->data["e_id"]}
SQL;
			$total = $this->total($sql);

			if ($total > $this->data["e_mds_users"]) {
				$msg["status"] = -1;
				$log = DL("保存失败，企业的用户数多于编辑的用户数 现在用户数 %s 保存的用户数 %s");
				$log = sprintf($log
					, $total
					, $this->data["e_mds_users"]
				);
				$this->log($log, 1, 2);
				$msg["msg"] = L('保存失败，企业的用户数多于编辑的用户数');
				return $msg;
			}
		}

		// 勾选了录制功能的企业
		if ($this->data["e_has_vcr"] == "") {
			return $this->saveMDS();
		} else {
			return $this->saveVCR();
		}
	}

	function saveMDS() {
		$edit = false;
		$tmpe_id = $this->getSEQ();

		if ($this->data["e_id"] != "") {
			$edit = true;
		}

		if ($edit) {
			$sql = 'UPDATE "T_Enterprise"SET e_status=:e_status,e_name =:e_name,e_mds_users =:e_mds_users,e_mds_call =:e_mds_call,e_has_vcr = :e_has_vcr,e_vcr_audiorec=:e_vcr_audiorec,e_vcr_videorec=:e_vcr_videorec,e_vcr_space=:e_vcr_space,e_storage_function=:e_storage_function,e_vcr_id=:e_vcr_id,e_pwd=:e_pwd  WHERE e_id =:e_id';
		} else {
			$sql = 'INSERT INTO "public"."T_Enterprise" ("e_id", "e_name", "e_area", "e_create_time", "e_mds_users", "e_mds_call","e_has_vcr","e_mds_id","e_vcr_audiorec","e_vcr_videorec","e_vcr_space","e_storage_function","e_vcr_id","e_pwd","e_status") VALUES (:e_id, :e_name, :e_area, :e_create_time, :e_mds_users, :e_mds_call,:e_has_vcr,:e_mds_id,:e_vcr_audiorec,:e_vcr_videorec,:e_vcr_space,:e_storage_function,:e_vcr_id,:e_pwd,:e_status)';
		}

		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_name', $this->data["e_name"], PDO::PARAM_STR);
		$sth->bindValue(':e_pwd', $this->data["e_pwd"], PDO::PARAM_STR);

		$sth->bindValue(':e_mds_users', $this->data["e_mds_users"], PDO::PARAM_INT);
		$sth->bindValue(':e_mds_call', $this->data["e_mds_call"], PDO::PARAM_INT);
		$sth->bindValue(':e_has_vcr', 0, PDO::PARAM_INT);

		$sth->bindValue(':e_vcr_audiorec', 0, PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_videorec', 0, PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_space', 0, PDO::PARAM_INT);
		$sth->bindValue(':e_storage_function', 0, PDO::PARAM_INT);
		$sth->bindValue(':e_status', 2, PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_id', NULL, PDO::PARAM_INT);

		if ($edit) {
			$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
		} else {
			$sth->bindValue(':e_id', $tmpe_id, PDO::PARAM_INT);
			$sth->bindValue(':e_area', json_encode($this->data["e_area"]));
			$sth->bindValue(':e_mds_id', $this->data["e_mds_id"], PDO::PARAM_INT);
			$sth->bindValue(':e_create_time', $this->data["e_create_time"], PDO::PARAM_STR);
		}
		try
		{
			if (!$edit) {
				$this->data["e_id"] = $tmpe_id;
				$this->initDB();
			}
			$sth->execute();
		} catch (Exception $ex) {
			$event['id'] = $this->md5r();
			$event['msg'] = $ex->getMessage();
			$msg["status"] = -1;
			$log = '企业 %s 失败，企业ID：%s 事件ID：%s';
			$log = sprintf($log
				, $this->data['e_name']
				, $this->data['e_id']
				, $event['id']
			);
			if ($edit) {
				$log = "修改" . $log;
			} else {
				$log = "创建" . $log;
			}

			$this->log($log, 0, 1, $event);
			$msg["msg"] = $log;
			return $msg;
		}

		$log = '企业 【%s】 成功，企业ID：【%s】，名称【%s】，区域【%s】，密码【%s】，所属GQT-Server【%s（%s）】，企业用户数【%s】，企业并发数【%s】';
		$device = new device(array('d_id' => $this->data['e_mds_id']));
		$device_item = $device->getByid();
		$log = sprintf($log
			, $this->data['e_name']
			, $this->data['e_id']
			, $this->data['e_name']
			, mod_area_name(json_encode($this->data['e_area']))
			, $this->data['e_pwd']
			, $this->data['e_mds_id']
			, $device_item['d_ip1']
			, $this->data['e_mds_users']
			, $this->data['e_mds_call']
		);
		if ($edit) {
			$log = "修改 " . $log;
		} else {
			$log = "创建 " . $log;
		}
		$this->log($log, 1, 0);
		$msg["status"] = 0;
		$msg["msg"] = $log;
		$msg["e_id"] = $this->data["e_id"];
		return $msg;
	}

	function saveVCR() {
		$edit = false;
		$tmpe_id = $this->getSEQ();
		if ($this->data["e_id"] != "") {
			$edit = true;
		}

		if ($edit) {
			$sql = 'UPDATE "T_Enterprise"SET e_name =:e_name,e_area =:e_area,e_mds_users =:e_mds_users,e_mds_call =:e_mds_call,e_vcr_audiorec=:e_vcr_audiorec,e_vcr_videorec=:e_vcr_videorec,e_vcr_space=:e_vcr_space,e_storage_function=:e_storage_function,e_has_vcr = :e_has_vcr,e_pwd=:e_pwd,e_status=:e_status ,e_vcr_id=:e_vcr_id WHERE e_id =:e_id';
		} else {
			$sql = 'INSERT INTO "public"."T_Enterprise" ("e_id", "e_name", "e_area", "e_create_time", "e_mds_id", "e_mds_users", "e_mds_call", "e_vcr_id", "e_vcr_audiorec", "e_vcr_videorec", "e_vcr_space", "e_storage_function","e_has_vcr","e_pwd","e_status") VALUES (:e_id, :e_name, :e_area, :e_create_time, :e_mds_id, :e_mds_users, :e_mds_call,:e_vcr_id, :e_vcr_audiorec, :e_vcr_videorec, :e_vcr_space, :e_storage_function,:e_has_vcr,:e_pwd,:e_status)';
		}

		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_name', $this->data["e_name"], PDO::PARAM_STR);
		$sth->bindValue(':e_area', $this->data["e_area"], PDO::PARAM_INT);
		$sth->bindValue(':e_pwd', $this->data["e_pwd"], PDO::PARAM_STR);

		$sth->bindValue(':e_mds_users', $this->data["e_mds_users"], PDO::PARAM_INT);
		$sth->bindValue(':e_mds_call', $this->data["e_mds_call"], PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_audiorec', $this->data["e_vcr_audiorec"], PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_videorec', $this->data["e_vcr_videorec"], PDO::PARAM_INT);
		$sth->bindValue(':e_vcr_space', $this->data["e_vcr_space"], PDO::PARAM_INT);
		$sth->bindValue(':e_storage_function', $this->data["e_storage_function"], PDO::PARAM_INT);
		$sth->bindValue(':e_has_vcr', 1, PDO::PARAM_INT);
		$sth->bindValue(':e_status', 2, PDO::PARAM_INT);

		if ($edit) {
			$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
			$sth->bindValue(':e_vcr_id', $this->data["e_vcr_id"], PDO::PARAM_INT);
		} else {
			$sth->bindValue(':e_id', $tmpe_id, PDO::PARAM_INT);
			$sth->bindValue(':e_create_time', $this->data["e_create_time"], PDO::PARAM_STR);
			$sth->bindValue(':e_mds_id', $this->data["e_mds_id"], PDO::PARAM_INT);
			$sth->bindValue(':e_vcr_id', $this->data["e_vcr_id"], PDO::PARAM_INT);
		}
		try
		{
			$sth->execute();
			if (!$edit) {
				$this->data["e_id"] = $tmpe_id;
				$this->initDB();
			}
		} catch (Exception $e) {
			$msg["status"] = -1;
			$msg["msg"] = $e->getMessage();
			return $msg;
		}
		$msg["status"] = 0;
		if ($edit) {

			$msg["msg"] = "企业修改成功[具有VCR功能]";
		} else {

			$msg["msg"] = "企业添加成功[具有VCR功能]";
		}
		$msg["e_id"] = $this->data["e_id"];
		return $msg;
	}

	function getAcl() {

	}

	function getWhere($order = false) {
		$where = " WHERE 1=1 ";
		if (trim((int) $this->data["e_id"]) > 0) {
			$where .= "AND TEXT(e_id) LIKE E'%" . (int) $this->data["e_id"] . "%'";
		}

		if ($this->data["e_name"] != "") {
			$where .= "AND e_name LIKE E'%" .addslashes( $this->data["e_name"]) . "%'";
		}

		if ($this->data["e_status"] != "") {
			$where .= "AND e_status = " . $this->data["e_status"];
		}

		if ($this->data["e_mds_id"] != "") {
			$where .= "AND e_mds_id = " . $this->data["e_mds_id"];
		}

		if ($this->data["e_vcr_id"] != "") {
			$where .= "AND e_vcr_id = " . $this->data["e_vcr_id"];
		}
		if ($this->data["do"] == "console") {
			$where .= "AND e_id != " . $this->data["ec_id"];
		}

		if ($this->data["e_area"] == "") {
			$this->data["e_area"] = "#";
		}
		$area = new area($_REQUEST);
		$where .= $area->getAcl('e_area', $this->data["e_area"]);

		if ($order) {
			$where .= ' ORDER BY e_id';
		}

		return $where;
	}

// 获取企业列表
	public function getList($limit = '') {
		$sql = <<<ECHO
SELECT
	e_id,
	e_bss_number,
	e_status,
	e_name,
	e_create_time,
	e_mds_id,
	e_mds_users,
	e_mds_call,
	e_vcr_id,
	e_vcr_audiorec,
	e_vcr_videorec,
	e_vcr_space,
	e_storage_function,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_mds_id)
				AND "T_Device".d_type = 'mds'
			)
	) AS mds_d_ip1,
	(
		SELECT
			"T_Device".d_ip1
		FROM
			"T_Device"
		WHERE
			(
				("T_Device".d_id = e_vcr_id)
				AND "T_Device".d_type = 'vcr'
			)
	) AS vcr_d_ip1,
	e_area,
	e_has_vcr,
	e_sync,
	e_pwd,
	am_name
FROM
	(
		"T_Enterprise"
		LEFT JOIN "T_AreaManage" ON (
			(
				"T_AreaManage".am_id = "T_Enterprise".e_area
			)
		)
	)
ECHO;
		$sql = $sql . $this->getWhere(true);
		$sql = $sql . $limit;

		$stat = $this->pdo->query($sql);
		$result = $stat->fetchAll();
		return $result;
	}

	public function refreshList() {
		$list = implode(", ", $this->data["checkbox"]);
		$sql = 'SELECT e_id FROM "T_Enterprise" WHERE e_id IN (:list) AND (e_status = 3 OR e_status = 2)';
		$sql = str_replace(':list', $list, $sql);

		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$resultlist = array();
		foreach ($result as $value) {
			$resultlist[] = $value['e_id'];
		}

		$resultliststr = implode(',', $resultlist);

		if ($resultliststr == "") {
			throw new Exception("没有一项状态为处理失败或处理中的项", -1);
		}

		$sql = 'UPDATE "T_Enterprise" SET e_status = 2 WHERE e_id IN (:e_id)';
		$sql = str_replace(':e_id', $resultliststr, $sql);
		$this->pdo->query($sql);

		foreach ($resultlist as $list) {
			$log = '刷新了企业%s ID：%s的状态';
			$log = sprintf($log
				, ''
				, $list
			);
			$this->log($log, 1, 0);
		}

		return $resultlist;
	}

	public function getSEQ() {
		$sql = 'SELECT nextval(\'"T_Enterprise_e_id_seq"\'::regclass)';
		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();
		return $result["nextval"];
	}

// 批量删除企业
	public function delList() {
		$list = implode(", ", $this->data["checkbox"]);
// 安全删除
		$sql = "SELECT e_id FROM \"T_Enterprise\" WHERE e_id IN($list) AND \"T_Enterprise\".e_status != 1";

		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll();
		$list = "";
		foreach ($result as $value) {
			$list .= $value["e_id"] . ",";
		}
		$list = rtrim($list, ", ");

		if ($list != "") {
			$sql = 'DELETE FROM "T_Enterprise"WHERE"T_Enterprise".e_id IN (' . $list . ') AND "T_Enterprise".e_status != 1';
			$count = $this->pdo->exec($sql);

			$listarr = explode(",", $list);
			foreach ($listarr as $value) {
				$log = '删除企业%s成功，企业ID：%s';
				$log = sprintf($log
					, ''
					, $value
				);

				$this->log($log, 1, 0);
				$this->delDB($value);
			}
		}

		return $count;
	}

// 返回当前企业总数
	public function getTotal() {
		$sql = 'SELECT COUNT("public"."T_Enterprise".e_id)AS total FROM"public"."T_Enterprise"';
		$sql = $sql . $this->getWhere();
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	public function getByid($deviceflag = false) {
		if ($this->data["e_id"] == "") {
			throw new Exception("Incorrect enterprise Numbers", -1);
		}

		$sql = 'SELECT
                    "T_Enterprise".e_id,
                    "T_Enterprise".e_bss_number,
                    "T_Enterprise".e_status,
                    "T_Enterprise".e_name,
                    "T_Enterprise".e_create_time,
                    "T_Enterprise".e_mds_id,
                    "T_Enterprise".e_mds_users,
                    "T_Enterprise".e_mds_phone,
                    "T_Enterprise".e_mds_dispatch,
                    "T_Enterprise".e_mds_gvs,
                    "T_Enterprise".e_mds_call,
                    "T_Enterprise".e_vcr_id,
                    "T_Enterprise".e_vcr_audiorec,
                    "T_Enterprise".e_vcr_videorec,
                    "T_Enterprise".e_vcr_space,
                    "T_Enterprise".e_storage_function,
                    "T_Enterprise".e_addr,
                    "T_Enterprise".e_contact_fox,
                    "T_Enterprise".e_contact_phone,
                    "T_Enterprise".e_contact_name,
                    "T_Enterprise".e_contact_surname,
                    "T_Enterprise".e_industry,
                    "T_Enterprise".e_contact_mail,
                    (
                            SELECT
                                    "T_Device".d_ip1
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_mds_id
                                            )
                                            AND
                                            "T_Device".d_type = \'mds\'
                                    )
                    ) AS mds_d_ip1,
                    (
                            SELECT
                                    "T_Device".d_name
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_mds_id
                                            )
                                            AND
                                            "T_Device".d_type = \'mds\'
                                    )
                    ) AS mds_d_name,
                    (
                            SELECT
                                    "T_Device".d_ip1
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_vcr_id
                                            )
                                            AND
                                            "T_Device".d_type = \'rs\'
                                    )
                    ) AS vcr_d_ip1,
					(
                            SELECT
                                    "T_Device".d_name
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_vcr_id
                                            )
                                            AND
                                            "T_Device".d_type = \'rs\'
                                    )
                    ) AS rs_name,
					(
                            SELECT
                                    "T_Device".d_name
                            FROM
                                    "T_Device"
                            WHERE
                                    (
                                            (
                                                    "T_Device".d_id = "T_Enterprise".e_ss_id
                                            )
                                            AND
                                            "T_Device".d_type = \'ss\'
                                    )
                    ) AS ss_name,
                    "T_AreaManage".am_name,
                    "T_Enterprise".e_area,
                    "T_Enterprise".e_has_vcr,
                    "T_Enterprise".e_sync,
                    "T_Enterprise".e_pwd,
                    "T_Enterprise".e_ag_path
            FROM
                    (
                            "T_Enterprise"
                            LEFT JOIN "T_AreaManage" ON (
                                    (
                                            "T_AreaManage".am_id = "T_Enterprise".e_area
                                    )
                            )
                    )
            WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch();

// merge device
		if ($deviceflag) {
// 合并device MDS
			$devicedata["d_id"] = $data["e_mds_id"];
			$device = new device($devicedata);
			$item = $device->getByid();

			$data["d_user"] = $item["d_user"];
			$data["d_call"] = $item["d_call"];

// 合并device VCR
			$devicedata["d_id"] = $data["e_vcr_id"];
			$device->set($devicedata);
			$item = $device->getByid();
			$data["d_space_free"] = $item["d_space_free"];
			$data["d_audiorec"] = $item["d_audiorec"];
			$data["d_videorec"] = $item["d_videorec"];
		}

		return $data;
	}

	public function get() {
		return $this->data;
	}

	public function set($data) {
		$this->data = $data;
	}

	public function moveMDS() {
		$sql = 'UPDATE"T_Enterprise" SET e_status=:e_status,e_mds_id=:e_mds_id,e_area=:e_area WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_status', 0, PDO::PARAM_INT);
		$sth->bindValue(':e_mds_id', $this->data["new_mds_id"], PDO::PARAM_INT);
		$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
		$sth->bindValue(':e_area', json_encode($this->data["e_area"]));
		$sth->execute();
		$msg["status"] = 0;
		$msg["msg"] = "迁移GQT-Server成功";
		return $msg;
	}

	public function moveVCR() {
		$new_vcr_id = $this->data["new_vcr_id"];
		$e_id = $this->data["e_id"];
		$sql = 'UPDATE "T_Enterprise" SET e_vcr_id = :e_vcr_id WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_vcr_id', $new_vcr_id, PDO::PARAM_INT);
		$sth->bindValue(':e_id', $e_id, PDO::PARAM_INT);
		try
		{
			$sth->execute();
		} catch (Exception $ex) {
			$msg["status"] = -1;
			$msg["msg"] = print_r($ex, true);
			return $msg;
		}
		$msg["status"] = 0;
		$msg["msg"] = "迁移VCR成功";
		return $msg;
	}

	public function changeStatus($status) {
		$sql = 'UPDATE"T_Enterprise" SET e_status=:e_status WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_status', $status, PDO::PARAM_INT);
		$sth->bindValue(':e_id', $this->data["e_id"], PDO::PARAM_INT);
		try
		{
			$sth->execute();
		} catch (Exception $ex) {
			$msg["status"] = -1;
			$msg["msg"] = print_r($ex, true);
			return $msg;
		}
		$msg["status"] = 0;
		$msg["msg"] = L("操作成功");
		return $msg;
	}

	public function changeSync($status, $sync) {
		$e_id = $this->data["e_id"];
		if ($e_id == "") {
			$e_id = $this->data["em_ent_id"];
		}

		//获取当前状态值
		$sql = 'SELECT e_sync FROM "T_Enterprise" WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':e_id', $e_id, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		// 设置当前状态值
		$sql = 'UPDATE"T_Enterprise" SET e_sync=:e_sync WHERE e_id = :e_id';
		$sth = $this->pdo->prepare($sql);
		if ($status) {
			$sth->bindValue(':e_sync', $result["e_sync"] | $sync, PDO::PARAM_INT);
		} else {
			$sth->bindValue(':e_sync', 0, PDO::PARAM_INT);
		}
		$sth->bindValue(':e_id', $e_id, PDO::PARAM_INT);
		try
		{
			$sth->execute();
		} catch (Exception $ex) {
			$msg["status"] = -1;
			$msg["msg"] = L('企业同步失败');
			return $msg;
		}
		$msg["status"] = 0;
		$msg["msg"] = L("同步完成");
		$msg['e_sync'] = $result["e_sync"];
		return $msg;
	}

	public function delDB($e_id = "") {
		$dsql = "DROP TABLE IF EXISTS \"public\".\"T_UserGroup_$e_id\";"
		. "DROP TABLE IF EXISTS \"public\".\"T_PttGroup_$e_id\";"
		. "DROP TABLE IF EXISTS \"public\".\"T_EventLog_$e_id\";"
		. "DROP TABLE IF EXISTS \"public\".\"T_PttMember_$e_id\";";
		$this->pdo->exec($dsql);
		$data['u_e_id'] = $e_id;
		$user = new users($data);
		$user->deleteAll();
	}

	public function initDB($e_id = "") {
		if ($e_id == "") {
			$e_id = $this->data["e_id"];
		}
		if ($e_id == "") {
			throw new Exception("Incorrect enterprise Numbers", -1);
		}
		/*
		$dc_usql = '
		DROP TABLE
		IF EXISTS "public"."T_User_:e_id";

		CREATE TABLE "public"."T_User_:e_id" (
		"u_number" varchar(64) NOT NULL,
		"u_passwd" varchar(64),
		"u_status" int4,
		"u_type" varchar(8),
		"u_sub_type" int4,
		"u_level" int4,
		"u_allow_login" int4,
		"u_gvs_user" int4,
		"u_only_show_my_grp" int4,
		"u_product_id" varchar(128),
		"u_audio_rec" int4,
		"u_video_rec" int4,
		"u_alarm_inform_svp_num" varchar(128),
		"u_mms_default_rec_num" varchar(128),
		"u_auto_config" int4,
		"u_audio_mode" int4,
		"u_gis_mode" int4,
		"u_auto_run" int4,
		"u_checkup_grade" int4,
		"u_encrypt" int4,
		"u_name" varchar(128),
		"u_pic" varchar(128),
		"u_sex" varchar(8),
		"u_position" varchar(64),
		"u_ug_id" int4,
		"u_default_pg" varchar(128),
		"u_pg_number" varchar,
		"u_mobile_phone" varchar(32),
		"u_terminal_type" varchar(32),
		"u_terminal_model" varchar(32),
		"u_zm" varchar(64),
		"u_imsi" varchar(64),
		"u_imei" varchar(64),
		"u_iccid" varchar(64),
		"u_mac" varchar(64),
		"u_udid" varchar(64),
		CONSTRAINT "T_User_:e_id_pkey" PRIMARY KEY ("u_number")
		) WITH (OIDS = FALSE);';
		 */
		$dc_ugsql = '
            DROP TABLE
            IF EXISTS "public"."T_UserGroup_:e_id";

            CREATE TABLE "public"."T_UserGroup_:e_id" (
                    "ug_id" int4 NOT NULL,
                    "ug_name" VARCHAR (128),
                    "ug_parent_id" int4,
                    "ug_weight" int4,
                    "ug_path" VARCHAR,
                    CONSTRAINT "T_UserGroup_:e_id_pkey" PRIMARY KEY ("ug_id")
            ) WITH (OIDS = FALSE);';

		$dc_pgsql = '
            DROP TABLE
            IF EXISTS "public"."T_PttGroup_:e_id";

            CREATE TABLE "public"."T_PttGroup_:e_id" (
                    "pg_number" VARCHAR (64),
                    "pg_name" VARCHAR (64),
                    "pg_level" int4,
                    "pg_grp_idle" int4,
                    "pg_speak_idle" int4,
                    "pg_speak_total" int4,
                    "pg_record_mode" int4,
                    "pg_queue_len" int4,
                    "pg_chk_stat_int" int4,
                    "pg_buf_size" int4,
                    "pg_hangup" int4,
                    CONSTRAINT "T_PttGroup_:e_id_pkey" PRIMARY KEY ("pg_number")
            ) WITH (OIDS = FALSE);
            CREATE UNIQUE INDEX "pg_name_pkey_:e_id" ON "public"."T_PttGroup_:e_id" USING btree (pg_name);
';

		$dc_elsql = '
            DROP TABLE
            IF EXISTS "public"."T_EventLog_:e_id";

            CREATE TABLE "public"."T_EventLog_:e_id" (
            "el_id" serial NOT NULL,
            "el_type" varchar(16),
            "el_level" int4,
            "el_time" timestamp(6),
            "el_content" varchar(1024),
            "el_user" varchar(64)
            )
            WITH (OIDS=FALSE)
            ;';
		$dc_ptmsql = '
            DROP TABLE
            IF EXISTS "public"."T_PttMember_:e_id";

            CREATE TABLE "public"."T_PttMember_:e_id" (
            "pm_number" varchar(64) NOT NULL,
            "pm_level" int4 DEFAULT 255,
            "pm_pgnumber" varchar(64),
            "pm_hangup" int4,
            CONSTRAINT "T_PttMember_:e_id_pkey" PRIMARY KEY ("pm_number", "pm_pgnumber")
            )
            WITH (OIDS=FALSE)
            ;';
		$dc_ugsql = str_replace(":e_id", $e_id, $dc_ugsql);
		$dc_pgsql = str_replace(":e_id", $e_id, $dc_pgsql);
		$dc_elsql = str_replace(":e_id", $e_id, $dc_elsql);
		$dc_ptmsql = str_replace(":e_id", $e_id, $dc_ptmsql);

		try
		{
//开启一个事务
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->beginTransaction();
			//$this->pdo->exec($dc_usql);
			$this->pdo->exec($dc_ugsql);

			$this->pdo->exec($dc_pgsql);
			$this->pdo->exec($dc_elsql);
			$this->pdo->exec($dc_ptmsql);
			$this->pdo->commit();
		} catch (Exception $ex) {
			$this->pdo->rollBack();
			throw new Exception("Create failure, data rollback" . $ex->getMessage(), -2);
		}

		$msg["status"] = 0;
		$msg["msg"] = L("初始化成功");
		return $msg;
	}

	function getUserNum() {
		//得到目标企业目前用户数量;
		if ($this->data["e_id"] != "") {
			$sql = <<<SQL
                        SELECT count(*) as total  FROM "T_User" WHERE u_e_id ={$this->data["e_id"]}
SQL;
			$total = $this->total($sql);
		}
		return $total;
	}

	function getenNum() {
		//得到目标企业目前用户数量;
		if ($this->data["e_id"] != "") {
			$sql = <<<SQL
                        SELECT e_mds_users as total  FROM "T_Enterprise" WHERE e_id ={$this->data["e_id"]}
SQL;
			$total = $this->total($sql);
		}
		return $total;
	}
	
}
