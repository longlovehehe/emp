<?php

class system extends db {

	public function system($data = NULL) {
		parent::__construct();
		$this->data = $data;
	}

	public function login() {
		if (isset($_SESSION['em_id'])) {
			echo $_SESSION['em_id'];
			return 1;
		} else {
			return 0;
		}
	}

	public function checkOtherLogin($own) {
		$sql = 'SELECT em_lastlogin_ip,em_session_id FROM "T_EnterpriseManager" WHERE em_id = :em_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':em_id', $own["em_id"], PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetch();
		$info['db_em_lastlogin_ip'] = $result['em_lastlogin_ip'];
		$info['em_lastlogin_ip'] = $own['em_lastlogin_ip'];
		if ($info['db_em_lastlogin_ip'] == $info['em_lastlogin_ip'] && $result['em_session_id'] == $own['em_session_id']) {
			$info['status'] = FALSE;
		} else {
			$this->log(sprintf(DL('该账户已在其他地方登录 本地IP： 【%s】 异地IP： 【%s】'), $info['em_lastlogin_ip'], $info['db_em_lastlogin_ip']), 7, 1);
			$info['status'] = TRUE;
		}
		return $info;
	}
        
/**
 * 验证密码是否被修改
 * @return boolean
 */
    public function check() {
        $sql = 'SELECT * FROM "T_EnterpriseManager" WHERE em_id = :username';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':username' , $_SESSION['eown']['em_id'] , PDO::PARAM_STR );
        $sth->execute ();
        $result = $sth->fetch ( PDO::FETCH_ASSOC );
        $info['status'] = FALSE;
        if($result['em_pswd']!= $_SESSION['eown']['em_pswd']){
            $info['msg'] = L('您的密码已被修改,请重新登陆');
            $info['status'] = TRUE;
        }
        return $info;
    }

//验证登陆
public function checkLogin() {
        $sql = 'SELECT * FROM "T_EnterpriseManager" WHERE em_id = :username AND em_ent_id=:em_ent_id';
        $sth = $this->pdo->prepare($sql);
        $sth->bindValue(':username', $this->data["username"], PDO::PARAM_STR);
        $sth->bindValue(':em_ent_id', $this->data["username"], PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        
        $sql1 = 'SELECT * FROM "T_Enterprise" WHERE e_name =:username';
        $sth1 = $this->pdo->prepare($sql1);
        $sth1->bindValue(':username', $this->data["username"], PDO::PARAM_STR);
        $sth1->execute();

        $result1 = $sth1->fetch(PDO::FETCH_ASSOC);
        if ($result) {
                if ($this->data["password"] != $result['em_pswd']) {
                        //$this->log($this->data["username"] . '密码错误', 7, 2);
                        return -2;
                } else {
                        $_SESSION['em_id'] = $result["username"];
                        $_SESSION['em_lastlogin_ip'] = $result['em_lastlogin_ip'];
                        $result['em_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];
                        $_SESSION['eown'] = $result;
                        $_SESSION['eown']['em_session_id'] = session_id();
                        $result['em_session_id']=session_id();
                        $_SESSION['eown']['em_lastlogin_time']=date("Y-m-d H:i:s",  time());

                        $ep = new enterprise(array('e_id' => $result['em_ent_id']));
                        $data = $ep->getByid();
                        $_SESSION['ep'] = $data;
                        $_SESSION['eown']['em_area'] = $data['e_area'];
                        $data['em_lastlogin_time'] = date('Y-m-d H:i:s');
                        $data['em_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];

                        $sql_upd = 'UPDATE "T_EnterpriseManager"SET em_lastlogin_ip = :user_ip,em_lastlogin_time = :lastlogintime,em_session_id = :session_id WHERE em_id = :username';
                        $sth = $this->pdo->prepare($sql_upd);
                        $sth->bindValue(':username', $this->data["username"], PDO::PARAM_STR);
                        $sth->bindValue(':user_ip', $data['em_lastlogin_ip'], PDO::PARAM_STR);
                        $sth->bindValue(':lastlogintime', $data['em_lastlogin_time'], PDO::PARAM_STR);
                        $sth->bindValue(':session_id', $result['em_session_id'], PDO::PARAM_STR);
                        $data = $sth->execute();
                        $this->log(DL('登录成功') . '。 IP：' . $_SERVER["REMOTE_ADDR"], 7);
                        return 0;
                }
        } else if($result1) {
            $e_id=$result1['e_id'];
            $sql2 = 'SELECT * FROM "T_EnterpriseManager" WHERE em_id = :username ';
            $sth2 = $this->pdo->prepare($sql2);
            $sth2->bindValue(':username', $e_id, PDO::PARAM_STR);
            $sth2->execute();
            $result2 = $sth2->fetch(PDO::FETCH_ASSOC);
            
            if($result2){
                 if ($this->data["password"] != $result2['em_pswd']) {
                        //$this->log($this->data["username"] . '密码错误', 7, 2);
                        return -2;
                } else {
                        $_SESSION['em_id'] = $result2["em_id"];
                        $_SESSION['em_lastlogin_ip'] = $result['em_lastlogin_ip'];
                        $result2['em_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];
                        $_SESSION['eown'] = $result2;
                        $_SESSION['eown']['em_session_id'] = session_id();
                        $result2['em_session_id']=session_id();
                        $_SESSION['eown']['em_lastlogin_time']=date("Y-m-d H:i:s",  time());

                        $ep = new enterprise(array('e_id' => $result2['em_ent_id']));
                        $data = $ep->getByid();
                        $_SESSION['ep'] = $data;
                        $_SESSION['eown']['om_area'] = $data['e_area'];
                        $data['em_lastlogin_time'] = date('Y-m-d H:i:s');
                        $data['em_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];

                        $sql_upd = 'UPDATE "T_EnterpriseManager"SET em_lastlogin_ip = :user_ip,em_lastlogin_time = :lastlogintime,em_session_id = :session_id WHERE em_id = :username';
                        $sth = $this->pdo->prepare($sql_upd);
                        $sth->bindValue(':username', $e_id, PDO::PARAM_STR);
                        $sth->bindValue(':user_ip', $data['em_lastlogin_ip'], PDO::PARAM_STR);
                        $sth->bindValue(':lastlogintime', $data['em_lastlogin_time'], PDO::PARAM_STR);
                        $sth->bindValue(':session_id', $result2['em_session_id'], PDO::PARAM_STR);
                        $data = $sth->execute();
                        $this->log(DL('登录成功') . '。 IP：' . $_SERVER["REMOTE_ADDR"], 7);
                        return 0;
                }
            }
        }else{
                return -1;
        }

}
	public function checkLogin_change() {
		$sql = 'SELECT * FROM "T_EnterpriseManager" WHERE em_id = :username ';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $this->data["username"], PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			if ($this->data["password"] != $result['em_pswd']) {
				//$this->log($this->data["username"] . '密码错误', 7, 2);
				return -2;
			} else {
				$_SESSION['em_id'] = $result["username"];
				$_SESSION['em_lastlogin_ip'] = $result['em_lastlogin_ip'];
				$result['em_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];
				$_SESSION['eown'] = $result;

				$ep = new enterprise(array('e_id' => $result['em_ent_id']));
				$data = $ep->getByid();
				$_SESSION['ep'] = $data;
				$_SESSION['eown']['om_area'] = $data['e_area'];
				$data['em_lastlogin_time'] = date('Y-m-d H:i:s');
				$data['em_lastlogin_ip'] = $_SERVER["REMOTE_ADDR"];

				$sql_upd = 'UPDATE "T_EnterpriseManager"SET em_lastlogin_ip = :user_ip,em_lastlogin_time = :lastlogintime WHERE em_id = :username';
				$sth = $this->pdo->prepare($sql_upd);
				$sth->bindValue(':username', $this->data["username"], PDO::PARAM_STR);
				$sth->bindValue(':user_ip', $data['em_lastlogin_ip'], PDO::PARAM_STR);
				$sth->bindValue(':lastlogintime', $data['em_lastlogin_time'], PDO::PARAM_STR);
				$data = $sth->execute();
				return 0;
			}
		} else {
			return -1;
		}
	}

//配置管理员信息
	public function superAdmin() {
		if ($this->data["pwd"] != $this->data["rpwd"]) {
			return "pwd";
		} else {
			$sql = 'INSERT INTO "public"."T_EnterpriseManager" ("em_id", "em_pswd") VALUES (:user, :pwd)';
			$sth = $this->pdo->prepare($sql);
			$sth->bindValue(':user', $this->data["user"], PDO::PARAM_INT);
			$sth->bindValue(':pwd', $this->data["pwd"], PDO::PARAM_STR);
			$data = $sth->execute();
			if ($data) {
				return $this->data["user"];
			}
		}
	}

	//修改密码
	public function chgPwd() {
		if ($_SESSION['eown']['em_pswd'] != $this->data['old_pwd']) {
			$msg['status'] = -1;
			$msg['msg'] = L('原密码不正确');
			$this->log(DL('原密码不正确'), 7, 1);
			// $msg['msg'] = '密码修改失败';
		} else if ($this->data['new_pwd'] != $this->data['new_rpwd']) {
			$msg['status'] = -1;
			$msg['msg'] = L('新密码两次输入不一致');
			$this->log(DL('新密码两次输入不一致'), 7, 1);
		} else {
			$em_id = $_SESSION['eown']['em_id'];
			$sql = 'UPDATE "T_EnterpriseManager"SET em_pswd = :em_pwd WHERE em_id = :username';
			$sth = $this->pdo->prepare($sql);
			$sth->bindValue(':em_pwd', $this->data['new_pwd']);
			$sth->bindValue(':username', $em_id);
			$sth->execute();
			$msg['status'] = 1;
			$msg['msg'] = L('密码修改成功');
			$this->log(DL('密码修改成功'), 7, 1);
		}

		return $msg;
	}

	function getWhere($order = false) {
		$where = " WHERE 1=1 ";
		$where .= "AND am_id  = " . "'" . $var . "'";
		if ($order) {
			$where .= ' ORDER BY am_id desc ';
		}

		return $where;
	}

	//获取信息
	public function getList() {
		//@ 该函数即将过时 2014-09-16 10:47:19
		return NULL;
		$em_id = $_SESSION['eown']['em_id'];
		$sql = 'SELECT* FROM "T_EnterpriseManager" WHERE em_id = :username ';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $em_id, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetch();
		return $result;
	}

	private function getByArea($str) {
		// @即将过时 2014-09-16
		if (!empty($str)) {
			$sql = 'SELECT "am_name" FROM "T_AreaManage" WHERE am_id in(' . $str . ')';
			$sth = $this->pdo->query($sql);
			$data = $sth->fetchAll(PDO::FETCH_ASSOC);
			$area = '';
			foreach ($data as $item) {
				$area .= $item['am_name'] . " ";
			}
			return $area;
		}
	}

	//公告列表
	public function getAnWhere($order = false) {
		$area = new area($_REQUEST);
		$where = " WHERE an_status = 1";
		$where .= $area->getAcl('an_area', $_SESSION['eown']['em_area']);
		if ($order) {
			$where .= "ORDER BY an_time DESC";
		}
		return $where;
	}

	public function getAnList($limit) {
		$sql = 'SELECT * FROM "T_Announcement"';
		$sql .= $this->getAnWhere(TRUE);
		$sql .= $limit;
		$sth = $this->pdo->query($sql);
		$result = $sth->fetchAll();

		return $result;
	}

	public function getAnTotal() {
		$sql = 'SELECT COUNT(an_id) AS total FROM "T_Announcement"';
		$sql .= $this->getAnWhere();

		$sth = $this->pdo->query($sql);
		$result = $sth->fetch();

		return $result["total"];
	}

//统计条数
	public function getTotal() {
		$em_id = $_SESSION['eown']['em_id'];
		$sql = 'SELECT* FROM "T_EnterpriseManager" WHERE em_id = :username';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $em_id, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetchAll();
		//重组变量
		foreach ($result as &$item) {
			$areaid = str_replace('|', ',', trim($item['om_area'], '|'));
			$item['om_area'] = $item['om_area'] == 0 ? 全部 : $areaid;
		}
		if ($result[0]['om_area'] == "全部") {
			$sql = 'SELECT COUNT(an_id)AS total FROM"public"."T_Announcement" WHERE  an_status = 1';
		} else {
			$sql = 'SELECT COUNT(an_id)AS total FROM"public"."T_Announcement"WHERE an_area_id in(' . $areaid . ') AND an_status =1';
		}
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

//查询有多少设备。
	public function getDevice() {
		$em_id = $_SESSION['eown']['em_id'];
		$sql = 'SELECT* FROM "T_EnterpriseManager" WHERE em_id = :username ';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $em_id, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetchAll();
		//重组变量
		foreach ($result as &$item) {
			$areaid = str_replace('|', ',', trim($item['om_area'], '|'));
			$item['om_area'] = $item['om_area'] == 0 ? 全部 : $areaid;
		}
		if ($result[0]['om_area'] == "全部") {
			$sql = 'SELECT COUNT(d_id)AS total FROM"public"."T_Device"';
		} else {
			$sql = 'SELECT COUNT(d_id)AS total FROM "T_Device" WHERE d_area in(' . $areaid . ')';
		}
		//重组变量
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	//查询有多少企业。
	public function getEn() {
		$em_id = $_SESSION['eown']['em_id'];
		$sql = 'SELECT* FROM "T_EnterpriseManager" WHERE em_id = :username ';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':username', $em_id, PDO::PARAM_STR);
		$sth->execute();
		$result = $sth->fetchAll();
		//重组变量
		foreach ($result as &$item) {
			$areaid = str_replace('|', ',', trim($item['om_area'], '|'));
			$item['om_area'] = $item['om_area'] == 0 ? 全部 : $areaid;
		}
		if ($result[0]['om_area'] == "全部") {
			$sql = 'SELECT COUNT(e_id)AS total FROM"public"."T_Enterprise"';
		} else {
			$sql = 'SELECT COUNT(e_id)AS total FROM "T_Enterprise" WHERE e_area in(' . $areaid . ')';
		}
		//重组变量
		$pdoStatement = $this->pdo->query($sql);
		$result = $pdoStatement->fetch();
		return $result["total"];
	}

	//公告详情页
	public function pro_details() {
		$sql = 'SELECT* FROM "T_Announcement" WHERE an_id = :an_id';
		$sth = $this->pdo->prepare($sql);
		$sth->bindValue(':an_id', $this->data["an_id"], PDO::PARAM_INT);
		$sth->execute();
		$data = $sth->fetch();

		return $data;
	}

}
