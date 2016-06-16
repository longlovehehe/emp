<?php

/*
require_once ("../shell/class/db.class.php");
 */

class usergroup extends db {

	public $data;

	public function usergroup($data) {
		parent::__construct();
		$this->data = $data;
	}

	public function getTableName() {
		$e_id = $this->data["e_id"];
		return "T_UserGroup_" . $e_id;
	}
                /**
	 * 获得部门ID
	 * @return type
	 */
	private function getid() {
		$e_id = $this->data["e_id"];
		$sql = 'SELECT nextval(\'"T_UserGroup_:e_id_ug_id_seq"\'::regclass)';

		$sql = str_replace(':e_id', $e_id, $sql);
		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();
		return $result["nextval"];
	}

	/**
	 * @return array
	 * 获得所有部门信息
	 */
	public function selectlist() {
		$table_name = $this->getTableName();
		$sql = "SELECT ug_id,ug_name,ug_weight FROM \"{$table_name}\"";
		$sth = $this->pdo->prepare($sql);
		$sth->execute();
		return $sth->fetchAll();
	}

	public function getlist() {
		$ug_parent_id = $this->data["ug_parent_id"];
		$table_name = $this->getTableName();
		$sql = "SELECT ug_id,ug_name,ug_weight,(SELECT \"count\" (ug_id) FROM \"$table_name\" AS tbb WHERE tba.ug_id = tbb.ug_parent_id) AS child,ug_path FROM \"$table_name\" AS tba WHERE ug_parent_id = :ug_parent_id AND ug_parent_id != -1 ORDER BY ug_weight DESC,ug_name ASC";

		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':ug_parent_id', $ug_parent_id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll();
	}

	public function getselectinfo($number) {
		$table_name = $this->getTableName();
		$sql = "SELECT ug_id,ug_name,ug_weight,ug_path FROM \"$table_name\" WHERE ug_id = '{$number}'";
		$sth = $this->pdo->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function create() {
		if (strlen(preg_replace('/[0-9]/', '', $this->data["ug_path"])) >= 12) {
			throw new Exception(L('六级以上部门禁止创建'), -1);
		}

		$ug_id = $this->getid();
		$table_name = $this->getTableName();
		$sql = "INSERT INTO \"$table_name\" (ug_id,ug_name,ug_parent_id,ug_weight,ug_path)VALUES(:ug_id,:ug_name,:ug_parent_id,:ug_weight,:ug_path);";
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':ug_id', $ug_id, PDO::PARAM_INT);
		$sth->bindValue(':ug_name', trim($this->data["ug_name"]), PDO::PARAM_STR);
		$sth->bindValue(':ug_parent_id', $this->data["ug_parent_id"], PDO::PARAM_INT);
		$sth->bindValue(':ug_weight', $this->data["ug_weight"], PDO::PARAM_INT);
		$sth->bindValue(':ug_path', $this->data["ug_path"] . "|$ug_id|", PDO::PARAM_INT);

		$sth->execute();
		$log = DL('创建子部门【%s】权重【%s】');
		$log = sprintf($log
			, trim($this->data["ug_name"])
			, $this->data["ug_weight"]
		);
		$this->log($log, db::USERGROUP, db::INFO);
	}

	public function create_peer() {
		$ug_id = time();
		$table_name = $this->getTableName();
		$sql = "INSERT INTO \"$table_name\" (ug_id,ug_name,ug_parent_id,ug_weight,ug_path)VALUES(:ug_id,:ug_name,:ug_parent_id,:ug_weight,:ug_path);";
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':ug_id', $ug_id, PDO::PARAM_INT);
		$sth->bindValue(':ug_name', trim($this->data["ug_name"]), PDO::PARAM_STR);
		$sth->bindValue(':ug_parent_id', $this->data["ug_parent_id"], PDO::PARAM_INT);
		$sth->bindValue(':ug_weight', $this->data["ug_weight"], PDO::PARAM_INT);
		$sth->bindValue(':ug_path', $this->data["ug_path"] . "|$ug_id|", PDO::PARAM_INT);
		$sth->execute();
		$log = DL('添加部门【%s】成功 企业ID：【%s】 部门ID【%s】');
		$log = sprintf($log
			, trim($this->data["ug_name"])
			, $this->data["e_id"]
			, $ug_id
		);
		$this->log($log, db::USERGROUP, db::INFO);
	}

	public function edit() {
		$table_name = $this->getTableName();
		$sql = "UPDATE \"$table_name\" SET ug_name=:ug_name,ug_weight=:ug_weight WHERE ug_id = :ug_parent_id";
		$sth = $this->pdo->prepare($sql);
		$info = $this->getselectinfo($this->data['ug_parent_id']);
		$sth->bindValue(':ug_parent_id', $this->data["ug_parent_id"], PDO::PARAM_INT);
		$sth->bindValue(':ug_name', trim($this->data["ug_name"]), PDO::PARAM_STR);
		$sth->bindValue(':ug_weight', $this->data["ug_weight"], PDO::PARAM_INT);
		$sth->execute();
		$log = DL('修改子部门【%s】为【%s】权重【%s】');
		$log = sprintf($log
			, $info[0]['ug_name']
			, trim($this->data["ug_name"])
			, $this->data["ug_weight"]
		);
		$this->log($log, db::USERGROUP, db::INFO);
	}

	public function save() {
		/*
		 * do
		 * 创建子节点
		 * 创建平行节点
		 * 节点更名
		 */

		$do = $this->data["do"];

		switch ($do) {
			case "add":
				$this->create();
				return "add";
			case "edit":
				$this->edit();
				return "edit";
		}
	}

	public function del() {
		/*
		 * do
		 * 永久删除子节点 del
		 * 子节点移动到根节点 safedel
		 *
		 */
		$table_name = $this->getTableName();
		$ug_id = $this->data["ug_id"];
		$info = $this->getselectinfo($ug_id);
		if ($this->data["do"] == "safedel") {
			$sql = "DELETE FROM \"$table_name\" WHERE ug_id = :ug_id";
			$sth = $this->pdo->prepare($sql);
			$sth->bindValue(':ug_id', $ug_id, PDO::PARAM_INT);
			$sth->execute();

			$sql = "UPDATE  \"$table_name\" SET ug_parent_id = 0 WHERE ug_parent_id = :ug_id";
			$sth = $this->pdo->prepare($sql);
			$sth->bindValue(':ug_id', $ug_id, PDO::PARAM_INT);
			$sth->execute();
		} else {
			$sql = "DELETE FROM \"$table_name\" WHERE ug_path LIKE :ug_id";
			$sth = $this->pdo->prepare($sql);
			$sth->bindValue(':ug_id', "%|" . $ug_id . "|%", PDO::PARAM_STR);
			$sth->execute();
		}
		$data = array();
		$data['u_ug_id'] = $ug_id;
		$data['u_e_id'] = $this->data["e_id"];
		$users = new users($data);
		$users->delUgId();
		$log = DL('删除子部门【%s】成功');

		$log = sprintf($log
			, $info[0]['ug_name']
		);
		$this->log($log, db::USERGROUP, 1);
	}

}
