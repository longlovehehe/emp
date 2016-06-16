<?php

class device extends db
{

    public function device ( $data )
    {
        parent::__construct ();
        $this->data = $data;
    }

    public function updateStatus ( $data )
    {
        if ( $data[1] == '' )
        {
            throw new Exception ( 'device_id is null or device_status is null' , -1 );
        }

        $sql = 'UPDATE "T_Device" SET d_status=? WHERE d_id=?';
        $sth = $this->pdo->prepare ( $sql );
        try
        {
            $sth->execute ( $data );
        }
        catch ( Exception $ex )
        {
            throw new Exception ( $ex->getMessage () , -1 );
        }
    }

    public function refreshList ()
    {
        $list = implode ( "," , $this->data["checkbox"] );
        $sql = 'SELECT d_id FROM "T_Device" WHERE d_id IN(:list) AND (d_status = 0 OR d_status = 2)';
        $sql = str_replace ( ':list' , $list , $sql );
        $sth = $this->pdo->query ( $sql );
        $result = $sth->fetchAll ( PDO::FETCH_ASSOC );
        $resultlist = array ();
        foreach ( $result as $value )
        {
            $resultlist[] = $value['d_id'];
        }

        $resultliststr = implode ( ',' , $resultlist );

        if ( $resultliststr == "" )
        {
            throw new Exception ( "没有一项状态为处理失败或处理中的项" , -1 );
        }

        $sql = 'UPDATE "T_Device" SET d_status = 0 WHERE d_id IN (:d_id)';
        $sql = str_replace ( ':d_id' , $resultliststr , $sql );
        $this->pdo->query ( $sql );
        foreach ( $resultlist as $value )
        {
            $log = '刷新了GQT-Server设备状态 ID：【%s】';
            $log = sprintf ( $log
                    , $value );
            $this->log ( $log , 2 , 0 );
        }

        return $resultlist;
    }

    function getMDSWhere ( $order = FALSE )
    {
        /*
          if (trim((int) $this->data["d_id"]) > 0) {
          $where .= "AND TEXT(d_id) LIKE E'%" . (int) $this->data["d_id"]."%'";
          }
         */
        if ( $this->data["d_id"] != "" )
        {
            $where .= "AND TEXT(d_id) LIKE E'%" . addslashes($this->data["d_id"]) . "%'";
        }

        if ( $this->data["d_name"] != "" )
        {
            $where .= "AND d_name LIKE E'%" . addslashes($this->data["d_name"]) . "%'";
        }

        if ( $this->data['d_area'] != '' )
        {
            $area = new area();
            $where .= $area->getAcl ( 'd_area' , $this->data["d_area"] );
        }

        if ( $this->data["d_ip1"] != "" )
        {
            $where .= "AND d_ip1 LIKE E'%" . addslashes($this->data["d_ip1"]) . "%'";
        }
        if ( $this->data["d_status"] != "" )
        {
            if ( $this->data["d_status"] == "0" || $this->data["d_status"] == "1" )
            {
                $where .= "AND d_status = '" . $this->data["d_status"] . "'";
            }
            else
            {
                $where .= "AND d_status NOT IN(0,1)";
            }
        }
        if ( $order )
        {
            $where .= ' ORDER BY d_id';
        }


        return $where;
    }

    public function getMDSListOption ()
    {
        $sql = <<<ECHO
SELECT
	d_name,
	d_id,
	d_ip1,
	d_user,
	d_call,
	d_user - sum_user AS diff_user,
	d_call - sum_call AS diff_call
FROM
	"T_Device"
LEFT JOIN (
	SELECT
		e_mds_id,
		SUM (e_mds_users) AS sum_user,
		SUM (e_mds_call) AS sum_call
	FROM
		"T_Enterprise"
	GROUP BY
		e_mds_id
) AS device ON e_mds_id = d_id
WHERE
	d_type = 'mds'
AND d_status = 1
ECHO;
        $sql .= $this->getMDSWhere ();

        $sth = $this->pdo->query ( $sql );
        $result = $sth->fetchAll ();

        foreach ( $result as $key => $value )
        {
            $result[$key]["diff_user"] = ($value["diff_user"] === NULL ) ? $value["d_user"] : $value["diff_user"];
            $result[$key]["diff_call"] = ($value["diff_call"] === NULL ) ? $value["d_call"] : $value["diff_call"];
        }
        return $result;
    }

    public function getMDSList ( $limit = '' )
    {
        $sql = <<<ECHO
SELECT
	d_id,
	d_ip1,
	d_port1,
	d_ip2,
	d_port2,
	d_name,
	d_area,
	d_type,
	d_user,
	d_call,
	d_space,
	d_space_free,
	d_audiorec,
	d_videorec,
	d_max_rec_files,
	d_area,
	d_status,
                     d_sip_port
FROM
	"T_Device"
WHERE
	d_type = 'mds'
ECHO;
        $sql .= $this->getMDSWhere ( TRUE );
        $sql .= $limit;

        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();

        foreach ( $result as &$val )
        {
            $val['status'] = $this->getStatus ( $val['d_id'] , "e_mds_id" );
        }

        return $result;
    }

    public function del ()
    {
        $data = $this->getByid ();

        $sql = 'DELETE FROM "T_Device"WHERE"T_Device".d_id =:d_id';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':d_id' , $this->data["d_id"] , PDO::PARAM_INT );

        $sth->execute ();
        $log = '删除GQT-Server设备成功 ID：【%s】IP外【%s】，Port外【%s】，IP内【%s】，port内【%s】，区域【%s】';
        $log = sprintf ( $log
                , $data["d_id"]
                , $data["d_ip1"]
                , $data["d_port1"]
                , $data["d_ip2"]
                , $data["d_port2"]
                , mod_area_name ( $data['d_area'] )
        );
        $this->log ( $log , 2 , 1 );
    }

    public function delMDSList ()
    {
        $count = 0;
        if ( ! empty ( $this->data['checkbox'] ) )
        {
            foreach ( $this->data['checkbox'] as $value )
            {
                $count ++;
                $data['d_id'] = $value;
                $this->set ( $data );
                $this->del ();
            }
        }
        return $count;
    }

    public function getMDSTotal ()
    {
        $sql = 'SELECT COUNT(d_id)AS total FROM"public"."T_Device" WHERE "T_Device".d_type = \'mds\'';
        $sql = $sql . $this->getMDSWhere ();
        $pdoStatement = $this->pdo->query ( $sql );
        $result = $pdoStatement->fetch ();
        return $result["total"];
    }

    //->VCR
    function getVCRWhere ()
    {
        // $where = " WHERE 1=1 ";
        if ( $this->data["d_id"] != "" )
        {
            $where .= "AND d_id = " . $this->data["d_id"];
        }

        if ( $this->data["d_name"] != "" )
        {
            $where .= "AND d_name LIKE E'%" . $this->data["d_name"] . "%'";
        }

        if ( $this->data["d_area"] != "" && $this->data["d_area"] != "-1" )
        {
            $where .= "AND d_area = " . $this->data["d_area"];
        }

        if ( $this->data["d_ip"] != "" )
        {
            $where .= "AND d_ip1 LIKE E'%" . $this->data["d_ip"] . "%'";
            $where .= "OR d_ip2 LIKE E'%" . $this->data["d_ip"] . "%'";
        }

        if ( $this->data["d_status"] != "" )
        {
            $where .= "AND d_status = " . $this->data["d_status"];
        }

        return $where;
    }

    public function getVCRList ( $limit )
    {
        $sql = 'SELECT
                        "T_Device".d_id,
                        "T_Device".d_ip1,
                        "T_Device".d_port1,
                        "T_Device".d_ip2,
                        "T_Device".d_port2,
                        "T_Device".d_name,
                        "T_Device".d_area,
                        "T_Device".d_type,
                        "T_Device".d_user,
                        "T_Device".d_call,
                        "T_Device".d_space,
                        "T_Device".d_space_free,
                        "T_Device".d_audiorec,
                        "T_Device".d_videorec,
                        "T_Device".d_max_rec_files,
                        "T_AreaManage".am_name,
                        "T_Device".d_status
                FROM
                        (
                                "T_Device"
                                LEFT JOIN "T_AreaManage" ON (
                                        (
                                                "T_AreaManage".am_id = "T_Device".d_area
                                        )
                                )
                        )
                WHERE
                        	"T_Device".d_type = \'vcr\'';

        $areaid = $this->getArea ();
        if ( $areaid )
        {
            $sql = $sql . $this->getMDSWhere () . "and d_area in($areaid)";
        }
        else
        {
            $sql = $sql . $this->getMDSWhere ();
        }

        $sql = $sql . $limit;

        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();

        foreach ( $result as &$val )
        {
            $val['status'] = $this->getStatus ( $val['d_id'] , "e_vcr_id" );
        }

        return $result;
    }

    public function delVCRList ( $list )
    {
        $list = rtrim ( $list , ", " );

        $sql = 'DELETE FROM "T_Device"WHERE"T_Device".d_id IN (' . $list . ') AND "T_Device".d_type = \'vcr\'';
        $count = $this->pdo->exec ( $sql );
        return $count;
    }

    public function getVCRTotal ()
    {
        $sql = 'SELECT COUNT(d_id)AS total FROM"public"."T_Device" WHERE(("T_Device".d_type) :: TEXT = \'vcr\' :: TEXT)';
        $sql = $sql . $this->getVCRWhere ();
        $pdoStatement = $this->pdo->query ( $sql );
        $result = $pdoStatement->fetch ();
        return $result["total"];
    }

    function makeSEQ ()
    {
        $seqname = "t_device_:d_id_seq";
        $seqname = str_replace ( ":d_type" , $this->data["d_type"] , $seqname );
        $seqname = str_replace ( ":d_id" , $this->data["d_id"] , $seqname );

        $sql = "CREATE SEQUENCE :seqname START 100000 MAXVALUE 999999;";
        $sql = str_replace ( ":seqname" , $seqname , $sql );
        try
        {
            $this->pdo->exec ( $sql );
        }
        catch ( Exception $e )
        {
            throw new Exception ( "seq create fail" . $e->getMessage () , -1 );
        }
    }

    //->VCR-S
    function getVCRSWhere ()
    {
        //$where = " WHERE 1=1 ";
        if ( $this->data["d_id"] != "" )
        {
            $where .= "AND d_id = " . $this->data["d_id"];
        }

        if ( $this->data["d_name"] != "" )
        {
            $where .= "AND d_name LIKE E'%" . $this->data["d_name"] . "%'";
        }

        if ( $this->data["d_area"] != "" )
        {
            $where .= "AND d_area = " . $this->data["d_area"];
        }

        if ( $this->data["d_ip"] != "" )
        {
            $where .= "AND d_ip1 LIKE E'%" . $this->data["d_ip"] . "%'";
            $where .= "OR d_ip2 LIKE E'%" . $this->data["d_ip"] . "%'";
        }

        if ( $this->data["d_status"] != "" )
        {
            $where .= "AND d_status = " . $this->data["d_status"];
        }

        return $where;
    }

    public function getVCRSList ( $limit )
    {
        $sql = 'SELECT
                    "T_Device".d_id,
                    "T_Device".d_ip1,
                    "T_Device".d_port1,
                    "T_Device".d_ip2,
                    "T_Device".d_port2,
                    "T_Device".d_name,
                    "T_Device".d_area,
                    "T_Device".d_type,
                    "T_Device".d_user,
                    "T_Device".d_call,
                    "T_Device".d_space,
                    "T_Device".d_space_free,
                    "T_Device".d_audiorec,
                    "T_Device".d_videorec,
                    "T_Device".d_max_rec_files,
                    "T_AreaManage".am_name,
                    "T_Device".d_status
            FROM
                    (
                            "T_Device"
                            LEFT JOIN "T_AreaManage" ON (
                                    (
                                            "T_AreaManage".am_id = "T_Device".d_area
                                    )
                            )
                    )
            WHERE
                  "T_Device".d_type = \'vcrs\'';

        $areaid = $this->getArea ();
        if ( $areaid )
        {
            $sql = $sql . $this->getVCRSWhere () . "and d_area in($areaid)";
        }
        else
        {
            $sql = $sql . $this->getVCRSWhere ();
        }


        $sql = $sql . $limit;

        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();

        return $result;
    }

    public function delVCRSList ( $list )
    {
        $list = rtrim ( $list , ", " );
        $sql = 'DELETE FROM "T_Device"WHERE"T_Device".d_id IN (' . $list . ') AND "T_Device".d_type = \'vcrs\'';
        $count = $this->pdo->exec ( $sql );
        return $count;
    }

    public function getVCRSTotal ()
    {
        $sql = 'SELECT COUNT(d_id)AS total FROM"public"."T_Device" WHERE(("T_Device".d_type) :: TEXT = \'vcrs\' :: TEXT)';
        $sql = $sql . $this->getMDSWhere ();
        $pdoStatement = $this->pdo->query ( $sql );
        $result = $pdoStatement->fetch ();
        return $result["total"];
    }

    public function getSEQ ()
    {
        $sql = 'SELECT nextval(\'"T_Device_d_id_seq"\'::regclass)';
        $sth = $this->pdo->query ( $sql );
        $result = $sth->fetch ();
        return $result["nextval"];
    }

    public function save ()
    {
        $edit = false;
        $jsonarea = '';
        if ( $this->data["d_id"] != "" )
        {
            $edit = true;
        }
        if ( $edit )
        {
            $sql = 'UPDATE "T_Device"SET d_ip1 = :d_ip1,d_port1 = :d_port1,d_ip2 = :d_ip2,d_port2 = :d_port2,d_name = :d_name,d_area = :d_area,d_type = :d_type WHERE d_id = :d_id';
        }
        else
        {
            $sql = 'INSERT INTO "public"."T_Device" (d_id,"d_ip1", "d_port1", "d_ip2", "d_port2","d_name", "d_area", "d_type") VALUES (:d_id,:d_ip1,:d_port1 ,:d_ip2 ,:d_port2 ,:d_name,:d_area,:d_type)';
            $this->data["d_id"] = $this->getSEQ ();
        }

        $d_area = json_encode ( $this->data["d_area"] );
        if ( substr_count ( $jsonarea , '#' ) > 0 )
        {
            $d_area = '["#"]';
        }

        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':d_ip1' , $this->data["d_ip1"] , PDO::PARAM_STR );
        $sth->bindValue ( ':d_port1' , $this->data["d_port1"] , PDO::PARAM_STR );
        $sth->bindValue ( ':d_ip2' , $this->data["d_ip2"] , PDO::PARAM_STR );
        $sth->bindValue ( ':d_port2' , $this->data["d_port2"] , PDO::PARAM_STR );
        $sth->bindValue ( ':d_name' , $this->data["d_name"] , PDO::PARAM_STR );
        $sth->bindValue ( ':d_area' , $d_area , PDO::PARAM_INT );
        $sth->bindValue ( ':d_type' , $this->data["d_type"] , PDO::PARAM_STR );
        $sth->bindValue ( ':d_id' , $this->data["d_id"] , PDO::PARAM_INT );

        try
        {
            $sth->execute ();
        }
        catch ( Exception $ex )
        {
            throw new Exception ( $ex->getMessage () , -1 );
        }
        $msg["status"] = 0;
        if ( $edit )
        {
            $log = '修改了GQT-Server设备名称 ID：【%s】IP外【%s】，Port外【%s】，IP内【%s】，port内【%s】，区域【%s】';
        }
        else
        {
            $log = '添加了GQT-Server设备成功 ID：【%s】IP外【%s】，Port外【%s】，IP内【%s】，port内【%s】，区域【%s】';
        }
        $log = sprintf ( $log
                , $this->data["d_id"]
                , $this->data["d_ip1"]
                , $this->data["d_port1"]
                , $this->data["d_ip2"]
                , $this->data["d_port2"]
                , mod_area_name ( $d_area )
        );
        $this->log ( $log , 2 , 0 );
        $msg["msg"] = $log;
        return $msg;
    }

    public function getByid ()
    {
        $sql = 'SELECT* FROM "T_Device" WHERE d_id = :d_id';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':d_id' , $this->data["d_id"] , PDO::PARAM_INT );
        $sth->execute ();
        $data = $sth->fetch ();
        return $data;
    }

    public function GetJsonByMDSId ()
    {
        $sql = 'SELECT* FROM "T_Device" WHERE d_id = :d_id';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':d_id' , $this->data["d_id"] , PDO::PARAM_INT );
        $sth->execute ();
        $data = $sth->fetch ();

        $sql = <<<ECHO
SELECT
	d_name,
	d_id,
	d_ip1,
	d_user,
	d_call,
	d_user - sum_user AS diff_user,
	d_call - sum_call AS diff_call
FROM
	"T_Device"
LEFT JOIN (
	SELECT
		e_mds_id,
		SUM (e_mds_users) AS sum_user,
		SUM (e_mds_call) AS sum_call
	FROM
		"T_Enterprise"
	GROUP BY
		e_mds_id
) AS t2 ON e_mds_id = d_id
WHERE
	d_type = 'mds'
AND d_status = 1
AND d_id = :d_id
ECHO;
        $sql .= $this->getMDSWhere ();
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':d_id' , $this->data["d_id"] , PDO::PARAM_INT );
        $sth->execute ();
        $result = $sth->fetch ( PDO::FETCH_ASSOC );
        $result["diff_user"] = ($result["diff_user"] === NULL ) ? $result["d_user"] : $result["diff_user"];
        $result["diff_call"] = ($result["diff_call"] === NULL ) ? $result["d_call"] : $result["diff_call"];
        return $result;
    }

    public function get ()
    {
        return $this->data;
    }

    public function set ( $data )
    {
        $this->data = $data;
    }

    //获取设备id用于判断是否已使用
    private function getStatus ( $id , $type )
    {
        $sql = "SELECT \"e_mds_id\" FROM \"T_Enterprise\" WHERE $type = $id ";
        $sth = $this->pdo->query ( $sql );
        $list = $sth->fetchAll ();
        if ( count ( $list ) != 0 )
        {
            return "yes";
        }
        else
        {
            return "no";
        }
    }

    //根据登陆的管理员获取管理地区id
    private function getArea ()
    {
        if ( ! empty ( $_SESSION['eown']['om_area'] ) || $_SESSION['eown']['om_area'] != 0 )
        {
            $areaid = str_replace ( "|" , "," , trim ( $_SESSION['eown']['om_area'] , "|" ) );
            return $areaid;
        }
        else
        {
            return false;
        }
    }

}
