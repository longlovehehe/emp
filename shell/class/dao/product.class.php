<?php

class product extends db
{

    public function product ( $data="" )
    {
        parent::__construct ();
        $this->data = $data;
    }

    function getWhere ( $order = false )
    {
        $where = " WHERE 1=1 ";
        if ( $this->data["p_id"] != "" )
        {
            $where .= "AND p_id LIKE " . "'%" . addslashes(strtoupper ( $this->data["p_id"] )) . "%'";
        }
        if ( $this->data["p_name"] != "" )
        {
            $where .= "AND p_name LIKE E'%" . addslashes($this->data["p_name"]) . "%'";
        }
        if ( $this->data['e_id'] != "" )
        {
            $enterprise = new enterprise ( $_REQUEST );
            $enterprise_item = $enterprise->getByid ();
            $this->data["p_area"] = '[' . $enterprise_item['e_area'] . ']';
        }

        if ( $this->data["p_area"] != "" )
        {
            $area = new area();
            $where .= $area->getAcl ( 'p_area' , $this->data["p_area"] );
        }
        if ( $this->data["start"] != "" && $this->data["end"] != "" )
        {
            $start = intval ( $this->data["start"] );
            $end = intval ( $this->data["end"] );
            $where .= "AND p_price BETWEEN '" . $start . "' AND '" . $end . "'";
        }
        if ( $order )
        {
            $where .= ' ORDER BY p_id';
        }
        return $where;
    }

    public function getList ( $limit = '' )
    {
        $sql = 'SELECT
                p_id,
                p_name,
                p_area,
                p_price,
                p_desc,
                p_items
        FROM
                "T_Product"
         ';
        $sql = $sql . $this->getWhere ( true );
        $sql = $sql . $limit;

        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );

        return $result;
    }

    public function getEList ()
    {
        $sql = <<<SQL
         SELECT
	p_id,
	p_name,
	p_area,
	p_price,
	p_desc,
	p_items
FROM
	"T_Product"
WHERE
	p_id IN (
		SELECT
			u_product_id
		FROM
			"T_User"
		WHERE
			u_e_id = :e_id
	)
SQL;
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':e_id' , $this->data["e_id"] , PDO::PARAM_INT );
        $sth->execute ();
        $result = $sth->fetchAll ( PDO::FETCH_ASSOC );

        return $result;
    }
      public function getPList($limit = '') {
        $sql = 'SELECT
                pi_id,
                pi_name,
                pi_code,
                pi_status
        FROM
                "T_ProductItems"
         ';
        //$sql = $sql . $this->getWhere(true);
        $sql = $sql . $limit;
        $stat = $this->pdo->query($sql);
        $result = $stat->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function del ()
    {
        $edit = false;
        if ( $this->data["an_id"] != "" )
        {
            $edit = true;
        }
        if ( $edit )
        {
            $sql = 'DELETE FROM "T_Product"WHERE"T_Product".an_id = :an_id';
        }
        $sth = $this->pdo->prepare ( $sql );
        if ( $edit )
        {
            $sth->bindValue ( ':an_id' , $this->data["an_id"] , PDO::PARAM_INT );
        }
        $result = $sth->execute ();
        if ( $result )
        {
            return 1;
        }
    }

    public function p_save ()
    {
        $sql1 = 'SELECT * FROM "T_ProductItems"';
        $stat = $this->pdo->query ( $sql1 );
        $results = $stat->fetchAll ();
        foreach ( $results as $item )
        {
            if ( $this->data["pi_code"] != "" )
            {
                if ( preg_match ( "/^([Gg][Nn]_).*$/" , $this->data["pi_code"] ) )
                {
                    if ( $this->data["pi_code"] == $item['pi_code'] )
                    {
                        $msg["msg"] = "功能编号不能重复";
                        return $msg;
                    }
                    else
                    {
                        $p_code = $this->data["pi_code"];
                    }
                }
                else
                {
                    $msg["msg"] = "功能编号以GN_开头";
                    return $msg;
                }
            }
            else
            {
                $msg["msg"] = "功能编号不能为空";
                return $msg;
            }
        }
        //$user = $_SESSION['om_id'];
        //$date = date('Y-m-d H:i:s');
        $pid = time () . sprintf ( "%04d" , rand ( 0 , 9999 ) );
        //$pid = $this->md5r();
        $sql = 'INSERT INTO "public"."T_ProductItems" ("pi_id","pi_code", "pi_name", "pi_status") VALUES (:pi_id,:pi_code,:pi_name ,:pi_status )';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':pi_id' , $pid , PDO::PARAM_STR );
        $sth->bindValue ( ':pi_code' , $this->data["pi_code"] , PDO::PARAM_STR );
        $sth->bindValue ( ':pi_name' , $this->data["pi_name"] , PDO::PARAM_STR );
        $sth->bindValue ( ':pi_status' , $this->data["pi_status"] , PDO::PARAM_STR );
        try
        {
            $sth->execute ();
        }
        catch ( Exception $ex )
        {
            $this->log ( "产品功能库添加失败:" . $ex->getMessage () , 0 , 2 );
            $msg["status"] = -1;
            $msg["msg"] = '产品功能库添加失败';
            return $msg;
        }
        $msg["status"] = 0;
        $this->log ( "产品功能库添加了功能。" , 5 , 0 );
        $msg["msg"] = "添加功能成功";
        return $msg;
    }

    public function p_addData ()
    {
        $arr = $_REQUEST;
        foreach ( $arr as $key => $value )
        {
            if ( preg_match ( "/^([Gg][Nn]_).*$/" , $key ) )
            {
                $str = $key . "," . $value;
                if ( $data == "" )
                {
                    $data = $str;
                }
                else
                {
                    $data = $data . "|" . $str;
                }
            }
        }
        $user = $_SESSION['eown']['om_id'];
        $date = date ( 'Y-m-d H:i:s' );
        $edit = false;
        if ( $this->data["p_id"] != "" )
        {
            $edit = true;
        }
        if ( $edit )
        {
            $sql = 'UPDATE "T_Product"SET p_name = :p_name,p_price = :p_price,p_desc = :p_desc,p_items = :p_items,p_area = :p_area WHERE p_id = :p_id';
        }
        else
        {
            $sql = 'INSERT INTO "public"."T_Product" ("p_id","p_name", "p_price", "p_desc", "p_items","p_area") VALUES (:p_id,:p_name,:p_price ,:p_desc ,:p_items ,:p_area)';
        }

        $sth = $this->pdo->prepare ( $sql );
        $jsonarea = json_encode ( $this->data["p_area"] );
        if ( substr_count ( $jsonarea , '#' ) > 0 )
        {
            $jsonarea = '["#"]';
        }

        if ( $edit )
        {
            $sth->bindValue ( ':p_id' , $this->data["p_id"] , PDO::PARAM_STR );
            $sth->bindValue ( ':p_name' , $this->data["p_name"] , PDO::PARAM_STR );
            $sth->bindValue ( ':p_price' , $this->data["p_price"] , PDO::PARAM_STR );
            $sth->bindValue ( ':p_desc' , $this->data["p_desc"] , PDO::PARAM_STR );
            $sth->bindValue ( ':p_area' , $jsonarea , PDO::PARAM_STR );
            $sth->bindValue ( ':p_items' , $data , PDO::PARAM_STR );
        }
        else
        {
            //$pid = $this->md5r();
            $pid = time () . sprintf ( "%04d" , rand ( 0 , 9999 ) );
            $sth->bindValue ( ':p_id' , $pid , PDO::PARAM_STR );
            $sth->bindValue ( ':p_name' , $this->data["p_name"] , PDO::PARAM_STR );
            $sth->bindValue ( ':p_price' , $this->data["p_price"] , PDO::PARAM_STR );
            $sth->bindValue ( ':p_desc' , $this->data["p_desc"] , PDO::PARAM_STR );
            $sth->bindValue ( ':p_area' , $jsonarea , PDO::PARAM_STR );
            $sth->bindValue ( ':p_items' , $data , PDO::PARAM_STR );
        }
        $info = $this->function_list ();
        foreach ( $info['status'] as $key => $value )
        {//获取所有属性信息
            $id = $value["id"];
            $arr1[$id] = array ();
            foreach ( $value as $k => $v )
            {
                if ( is_array ( $v ) )
                {
                    $arr1[$id][$v[0]] = $v[1];
                }
            }
        }

        $data = explode ( "|" , $data );
        foreach ( $data as $key => $value )
        {//获取选中属性信息
            $cd = explode ( "," , $value );
            $ecd[$cd[0]] = $cd[1];
        }
        foreach ( $arr1 as $key => $value )
        {
            $data1 .=$value[$ecd[$key]] . "|";
        }
        try
        {
            $sth->execute ();
        }
        catch ( Exception $ex )
        {
            if ( $ex->getCode () == 23505 )
            {
                $log = '添加新产品失败， 名称重复';
                $this->log ( $log , 5 , 1 );
                return $this->msg ( $log , -1 );
            }

            $msg["status"] = -1;
            $log = '添加新产品失败， 原因：' . $ex->getMessage ();

            $this->log ( $log , 5 , 2 );
            $msg["msg"] = $log;
            return $msg;
        }
        $msg["status"] = 0;
        if ( $edit )
        {
            $log = '修改了产品  产品ID：【%s】  名称 【%s】 、区域 【%s】、价格 【%s】、功能 【%s】';
            $log = sprintf ( $log
                    , $this->data["p_id"]
                    , $this->data["p_name"]
                    , mod_area_name ( $jsonarea )
                    , $this->data["p_price"]
                    , $data
            );

            $this->log ( $log , 5 , 0 );
            $msg["msg"] = $log;
        }
        else
        {
            $log = '添加新产品成功  产品ID：【%s】区域：【%s】 名称：【%s】价格：【%s】';
            $log = sprintf ( $log
                    , $pid
                    , mod_area_name ( $jsonarea )
                    , $this->data["p_name"]
                    , $this->data["p_price"]
            );

            $this->log ( $log , 5 , 0 );
            $msg["msg"] = $log;
        }
        return $msg;
    }

    public function getTotal ()
    {
        $sql = 'SELECT  COUNT(p_id)AS total FROM"public"."T_Product"';
        $sql = $sql . $this->getWhere ();
        $pdoStatement = $this->pdo->query ( $sql );
        $result = $pdoStatement->fetch ();
        return $result["total"];
    }

    public function getPro ()
    {
        $sql = 'SELECT * FROM "T_ProductItems"';
        $sql = $sql . $limit;
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();
        return $result;
    }

    public function pro_del ()
    {
        $edit = false;
        if ( $this->data["id"] != "" )
        {
            $edit = true;
        }
        else
        {
            $edit = false;
            $msg["msg"] = "请选中要删除的功能！";
            return $msg;
        }
        if ( $edit )
        {
            $sql = 'DELETE FROM "T_ProductItems"WHERE"T_ProductItems".pi_id = :am_id';
        }
        $sth = $this->pdo->prepare ( $sql );
        if ( $edit )
        {
            $sth->bindValue ( ':am_id' , $this->data["id"] , PDO::PARAM_INT );
        }
        $result = $sth->execute ();
        if ( $result )
        {
            $msg["msg"] = "删除成功";
        }
        else
        {
            $msg["msg"] = "删除失败";
        }
        return $msg;
    }

    public function delAll ()
    {
        $sql = 'DELETE FROM "T_ProductItems"';
        $sth = $this->pdo->prepare ( $sql );
        $result = $sth->execute ();
        $msg["status"] = 0;
        if ( $result )
        {
            $this->log ( "清空了全部产品功能" , 5 , 0 );
            $msg["msg"] = "清空成功";
        }
        else
        {
            $msg["msg"] = "清空失败";
        }
        return $msg;
    }

    public function function_list ()
    {
        $res_arr = array ();
        $sql1 = 'SELECT * FROM "T_ProductItems"';
        $stat = $this->pdo->query ( $sql1 );
        $result = $stat->fetchAll ();
        $res_arr = array ();
        foreach ( $result as $i => $item )
        {
            $res = explode ( "|" , $item['pi_status'] );
            foreach ( $res as $key => $val )
            {
                $arr = explode ( "," , $val );
                foreach ( $arr as $k => $v )
                {
                    $res_arr[$item['pi_name']][$key][$k] = $v;
                    $res_arr[$item['pi_name']]["id"] = $item['pi_code'];
                }
            }
            $result["status"] = $res_arr;
        }
        return $result;
    }

    public function p_details ()
    {
        $sql = 'SELECT* FROM "T_Product" WHERE p_id = :p_id';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':p_id' , $this->data["p_id"] , PDO::PARAM_INT );
        $sth->execute ();
        $res_arr1 = array ();
        $data1 = $sth->fetchAll ();
        foreach ( $data1 as $i => $item )
        {
            $res = explode ( "|" , $item['p_items'] );
            foreach ( $res as $key => $val )
            {
                $arr = explode ( "," , $val );
                $res_arr1[] = $arr;
            }
        }

        $res_arr = array ();
        $sql1 = 'SELECT * FROM "T_ProductItems"';
        $stat = $this->pdo->query ( $sql1 );
        $results = $stat->fetchAll ();
        $res_arr = array ();
        foreach ( $results as $i => $item )
        {
            $res = explode ( "|" , $item['pi_status'] );
            foreach ( $res as $key => $val )
            {
                $arr = explode ( "," , $val );
                foreach ( $arr as $k => $v )
                {
                    $res_arr[$item['pi_name']][$key][$k] = $v;
                    $res_arr[$item['pi_name']]["id"] = $item['pi_code'];
                    foreach ( $res_arr1 as $res_ar )
                    {
                        if ( $item['pi_code'] == $res_ar[0] )
                        {
                            $res_arr[$item['pi_name']]["value_o"] = $res_ar[1];
                        }
                    }
                }
            }
            $data1["status"] = $res_arr;
        }
        return $data1;
    }

    public function getbyid ()
    {
        $sql = <<<SQL
SELECT
	*
FROM
	"T_Product"
WHERE
	p_id = :p_id
SQL;
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':p_id' , $this->data['id'] );
        $sth->execute ();
        return $sth->fetch ();
    }

    public function p_del ()
    {
        $sql = 'DELETE FROM "T_Product" WHERE "T_Product".p_id = :p_id';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':p_id' , $this->data["id"] , PDO::PARAM_INT );

        $sth->execute ();
        $this->log ( "删除了产品。产品ID：" . $this->data["id"] , 5 , 1 );
        $msg["status"] = 0;
        $msg["msg"] = "删除成功";

        return $msg;
    }

    public function getused ( $p_id )
    {
        $sql = "SELECT u_product_id FROM \"T_User\" WHERE u_product_id=" . '\'' . $p_id . '\'';
        $sth = $this->pdo->prepare ( $sql );
        $sth->execute ();
        return $sth->fetch ();
    }

    public function getFunctionList ( $where = "" )
    {

        $sql = 'SELECT * from "T_ProductItems" ';

        if ( $where !== "" )
        {
            $sql .= "WHERE " . $where;
        }
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );
        return $result;
    }

}
