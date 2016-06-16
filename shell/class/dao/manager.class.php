<?php

class manager extends db
{

    public function manager ( $data )
    {
        parent::__construct ();
        $this->data = $data;
    }

    function getWhere ( $order = false )
    {
        $where = " WHERE 1=1 ";

        if ( $this->data["om_id"] != "" )
        {
            $where .= "AND om_id LIKE E'%".addslashes($this->data['om_id'])."%'";
        }

        if ( $this->data['om_safe_login'] != "" )
        {
            $where .= "AND om_safe_login = '{$this->data['om_safe_login']}'";
        }

        if ( $this->data["start"] != "" || $this->data["end"] != "" )
        {
            $where .= 'AND om_lastlogin_time ' . getDateRange ( $this->data["start"] , $this->data["end"] );
            /*
              $start = $this->data["start"];
              $end = $this->data["end"];
              $start = $start != "" ? $start : "0000-00-00";
              $end = $end != "" ? $end : "9999-99-99";
              $where .= "AND om_lastlogin_time BETWEEN to_date('" . $start . "', 'yyyy-mm-dd') AND to_date('" . $end . "', 'yyyy-mm-dd')"; */
        }

        if ( $order )
        {
            $where .= ' ORDER BY om_id ASC';
        }
        return $where;
    }

    public function save ()
    {

        $edit = false;

        if ( $this->data['om_flag'] != "" )
        {
            $edit = true;
        }
        if ( $this->data["om_area"] == "" )
        {
            throw new Exception ( 'area is null' );
        }
        if ( $edit )
        {
            $sql = 'UPDATE "public"."T_OperationManager" SET om_pswd = :om_pswd,om_type = :om_type,om_area = :om_area,om_desc = :om_desc,om_phone = :om_phone,om_mail = :om_mail WHERE om_id = :om_id';
        }
        else
        {
            $sql = 'INSERT INTO "public"."T_OperationManager" ("om_id","om_pswd", "om_type", "om_desc", "om_phone", "om_mail", "om_area") VALUES (:om_id,:om_pswd, :om_type, :om_desc, :om_phone, :om_mail, :om_area)';
        }
        $sth = $this->pdo->prepare ( $sql );

        $jsonarea = json_encode ( $this->data["om_area"] );
        if ( substr_count ( $jsonarea , '#' ) > 0 )
        {
            $jsonarea = '["#"]';
        }
        $sth->bindValue ( ':om_id' , $this->data["om_id"] );
        $sth->bindValue ( ':om_pswd' , $this->data["om_pswd"] );
        $sth->bindValue ( ':om_type' , $this->data["om_type"] );
        $sth->bindValue ( ':om_desc' , $this->data["om_desc"] );
        $sth->bindValue ( ':om_phone' , $this->data["om_phone"] );
        $sth->bindValue ( ':om_mail' , $this->data["om_mail"] );
        $sth->bindValue ( ':om_area' , $jsonarea );

        try
        {
            $sth->execute ();
        }
        catch ( Exception $ex )
        {
            if ( $ex->getCode () == "23505" )
            {
                if ( preg_match ( '/OperationManager/' , $ex->getMessage () ) )
                {
                    $log = '添加运营管理员失败，原因：帐号重复';
                }
                if ( preg_match ( '/phone/' , $ex->getMessage () ) )
                {
                    $log = '添加运营管理员失败，原因：手机号重复';
                }
                if ( preg_match ( '/mail/' , $ex->getMessage () ) )
                {
                    $log = '添加运营管理员失败，原因：邮箱重复';
                }
                $this->log ( $log , 3 , 1 );
                $msg["msg"] = $log;
            }
            else
            {
                $log = '添加运营管理员失败，原因：' . $ex->getMessage ();
                $this->log ( $log , 3 , 2 );
                $msg["msg"] = $log;
            }
            $msg["status"] = -1;
            return $msg;
        }
        $msg["status"] = 0;

        if ( $edit )
        {
            $log = '修改 运营管理员 成功 帐号： 【%s】  手机【%s】、邮箱【%s】、区域【%s】、描述【%s】';
        }
        else
        {
            $log = '添加 运营管理员 成功 帐号： 【%s】  手机【%s】、邮箱【%s】、区域【%s】、描述【%s】';
        }
        $log = sprintf ( $log
                , $this->data["om_id"]
                , $this->data["om_phone"]
                , $this->data["om_mail"]
                , mod_area_name ( $jsonarea )
                , $this->data["om_desc"]
        );

        $this->log ( $log , 3 , 0 );
        $msg["msg"] = $log;
        return $msg;
    }

    public function del ( $list )
    {
        $sql = "DELETE FROM \"T_OperationManager\" WHERE \"T_OperationManager\".om_id IN ('" . implode ( "','" , $list ) . "') AND \"T_OperationManager\".om_id != 'adnin'";
        $count = $this->pdo->exec ( $sql );
        return $count;
    }

    public function getList ( $limit )
    {
        $sql = 'SELECT * FROM "T_OperationManager"';
        $sql = $sql . $this->getWhere ( true );
        $sql = $sql . $limit;

        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ( PDO::FETCH_ASSOC );

        return $result;
    }

    public function getById ()
    {
        $sql = 'SELECT * FROM "T_OperationManager" WHERE om_id = :om_id';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':om_id' , $this->data['om_id'] , PDO::PARAM_STR );
        $sth->execute ();
        $data = $sth->fetch ( PDO::FETCH_ASSOC );
        return $data;
    }

    public function getTotal ()
    {
        $sql = 'SELECT COUNT(om_id) AS total FROM "public"."T_OperationManager"';
        $sql = $sql . $this->getWhere ();
        $pdoStatement = $this->pdo->query ( $sql );
        $result = $pdoStatement->fetch ();
        return $result["total"];
    }

    //密码重置方法
    public function reset ()
    {
        if ( ! empty ( $this->data['reset_id'] ) )
        {
            $sql = 'UPDATE "T_OperationManager" SET "om_pswd" = \'000000\' WHERE "om_id" = ' . "'{$this->data['reset_id']}'";
        }
        $result = $this->pdo->exec ( $sql );
        if ( $result > 0 )
        {
            $log = '重置管理员密码成功 重置的帐号： 【%s】 ，新密码为 000000';
            $log = sprintf ( $log
                    , $this->data['reset_id']
            );
            $this->log ( $log , 3 , 1 );
            $msg['status'] = 0;
        }
        else
        {
            $log = '重置管理员密码失败 重置的帐号： 【%s】';
            $log = sprintf ( $log
                    , $this->data['reset_id']
            );
            $this->log ( $log , 3 , 2 );
            $msg['status'] = -1;
        }

        $msg['msg'] = $log;
        return $msg;
    }

}
