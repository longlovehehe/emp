<?php

class area extends db
{

    public function __construct ( $data = NULL )
    {
        parent::__construct ();
        $this->data = $data;
    }

    public function getbyjson ()
    {
        if ( $this->data['am_id'] == '["#"]' || $this->data['am_id'] == '#' )
        {
            $arr[] = '全部';
        }
        else
        {
            $area = json_decode ( $this->data['am_id'] );
            $sql = 'SELECT am_name FROM "T_AreaManage"';
            $where = '';

            if ( is_string ( $area ) )
            {
                $tmp[] = $area;
                $area = $tmp;
            }

            if ( ! empty ( $area ) )
            {
                $where = 'WHERE 1!=1 ';
                foreach ( $area as $value )
                {
                    $where .= sprintf ( "OR am_id='%s'" , $value );
                }
            }
            $sql .= $where;
            $sth = $this->pdo->query ( $sql );
            $result = $sth->fetchAll ();

            $arr = array ();
            foreach ( $result as $item )
            {
                $arr[] = $item['am_name'];
            }
            if ( empty ( $arr ) )
            {
                $arr[] = '区域信息丢失！';
            }
        }
        return json_encode ( $arr );
    }

    public function insert ()
    {
        //$am_id = $this->md5r();
        $am_id = time ();
        $sql = 'INSERT INTO "public"."T_AreaManage" ("am_id", "am_name") VALUES (?, ?)';
        $sth = $this->pdo->prepare ( $sql );
        try
        {
            $sth->execute ( array ( $am_id , $this->data['am_name'] ) );
        }
        catch ( Exception $ex )
        {
            if ( $ex->getCode () == 23505 )
            {
                $log = '添加新区域失败  名称重复';
                $this->log ( $log , 4 , 1 );
                return $this->msg ( $log , -1 );
            }
            $log = '添加新区域失败。原因：' . $ex->getMessage ();
            $this->log ( $log , 4 , 2 );
            return $this->msg ( $log , -1 );
        }
        $log = '添加新区域成功  区域ID： 【%s】  名称： 【%s】';
        $log = sprintf ( $log
                , $am_id
                , $this->data['am_name']
        );

        $this->log ( $log , 4 , 0 );
        return $this->msg ( $log );
    }

    public function update ()
    {
        $old_data = $this->getByid ();


        $sql = 'UPDATE "public"."T_AreaManage" SET "am_name"=? WHERE ("am_id"=?)';
        $sth = $this->pdo->prepare ( $sql );
        try
        {
            $sth->execute ( array ( $this->data['am_name'] , $this->data['am_id'] ) );
        }
        catch ( Exception $ex )
        {
            if ( $ex->getCode () == 23505 )
            {
                $log = '修改区域名称失败  原因：名称重复';
                $this->log ( $log , 4 , 1 );
                return $this->msg ( $log , -1 );
            }
            $log = '修改区域名称失败。原因：' . $ex->getMessage ();
            $this->log ( $log , 4 , 2 );
            return $this->msg ( $log , -1 );
        }
        $log = '修改区域名称成功  区域ID【%s】： 名称【%s】->【%s】';
        $log = sprintf ( $log
                , $this->data['am_id']
                , $old_data['am_name']
                , $this->data['am_name']
        );

        $this->log ( $log , 4 , 0 );
        return $this->msg ( $log );
    }

    public function safedelete ()
    {
        $total = 0;
        $sql = <<<SQL
                        SELECT count(*) as total FROM "T_Enterprise" WHERE e_area LIKE E'%{$this->data["id"]}%';
SQL;
        $total += $this->total ( $sql );
        $sql = <<<SQL
                        SELECT count(*) as total FROM "T_OperationManager" WHERE om_area LIKE E'%{$this->data["id"]}%';
SQL;
        $total += $this->total ( $sql );
        $sql = <<<SQL
                        SELECT count(*) as total FROM "T_Announcement" WHERE an_area LIKE E'%{$this->data["id"]}%';
SQL;
        $total += $this->total ( $sql );
        $sql = <<<SQL
                        SELECT count(*) as total FROM "T_Device" WHERE d_area LIKE E'%{$this->data["id"]}%';
SQL;
        $total += $this->total ( $sql );

        if ( $total > 0 )
        {
            return FALSE;
        }
        return TRUE;
    }

    public function delete ()
    {
        if ( $this->safedelete () )
        {
            $sql = 'DELETE FROM "T_AreaManage"WHERE am_id = :am_id';

            $sth = $this->pdo->prepare ( $sql );
            $sth->bindValue ( ':am_id' , $this->data["id"] );
            $result = $sth->execute ();
            if ( $result )
            {
                $log = '删除区域成功';
                $msg["msg"] = $log;
                $this->log ( $log , 4 , 1 );
            }
            else
            {
                $log = '删除区域失败';
                $msg["msg"] = $log;
                $this->log ( $log , 4 , 2 );
            }
        }
        else
        {
            $log = "删除区域失败  原因：有企业或者角色或者公告或者设备在使用该区域";
            $this->log ( $log , 4 , 1 );
            $msg["msg"] = $log;
        }
        return $msg;
    }

    public function save ()
    {
        $edit = false;
        if ( $this->data["am_id"] != "" )
        {
            $edit = true;
        }
        if ( $edit )
        {
            return $this->update ();
        }
        else
        {
            return $this->insert ();
        }
    }

    function getAcl ( $field , $area )
    {
        $where = "";
        $tmp = " {$field} LIKE E'%#%' OR";
        if ( $area == '#' )
        {
            $area = $_SESSION['eown']['om_area'];
        }
        if ( $area == '@' )
        {
            $area = $_SESSION['eown']['om_area'];
        }
        if ( $area == '["#"]' )
        {
            return "";
        }

        $jsonarea = json_decode ( $area );

        if ( ! is_array ( $jsonarea ) )
        {
            $jsonarea = json_decode ( '["' . $area . '"]' );
        }
        foreach ( $jsonarea as $value )
        {
            $mask = " {$field} LIKE E'%:s%' OR";
            $tmp .= str_replace ( ':s' , $value , $mask );
        }
        $tmp = rtrim ( $tmp , 'OR' );
        $where = sprintf ( "AND (%s)" , $tmp );

        return $where;
    }

    function getWhere ( $order = false )
    {
        $where = " WHERE 1=1 ";
        $where .= $this->getAcl ( 'am_id' , $_SESSION['eown']['om_area'] );

        if ( $order )
        {
            $where .= ' ORDER BY am_id desc ';
        }
        return $where;
    }

    public function delList ()
    {
        return $this->delete ();
    }

    public function getList ( $limit = "" )
    {
        $sql = 'SELECT * FROM "T_AreaManage"';
        $sql = $sql . $this->getWhere ( true );
        $sql = $sql . $limit;

        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();
        return $result;
    }

    public function getAllList ()
    {
        $sql = 'SELECT am_id FROM "T_AreaManage"';
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();
        return $result;
    }

    public function getTotal ()
    {
        $sql = 'SELECT COUNT(am_id)AS total FROM"public"."T_AreaManage"';
        $sql = $sql . $this->getWhere ();
        $pdoStatement = $this->pdo->query ( $sql );
        $result = $pdoStatement->fetch ();
        return $result["total"];
    }

    public function getByid ()
    {
        $sql = 'SELECT* FROM "T_AreaManage" WHERE am_id = :am_id';
        $sth = $this->pdo->prepare ( $sql );
        $sth->bindValue ( ':am_id' , $this->data["am_id"] , PDO::PARAM_INT );
        $sth->execute ();
        $data = $sth->fetch ();
        return $data;
    }

    public function isdiff ( $sub , $all )
    {
        if ( $all['0'] == '#' )
        {
            return FALSE;
        }

        $diff = array_udiff ( $sub , $all , function($a , $b)
        {
            if ( $a == $b )
            {
                return FALSE;
            }
            return TRUE;
        } );

        if ( count ( $diff ) > 0 )
        {
            return TRUE;
        }
        return FALSE;
    }

    /*
     * $str = |areaid|1|2|3|

      性能问题，停用
      public function modifierArea($aredid) {
      if ($aredid != "") {
      $aredid = ltrim($aredid, "|");
      $aredid = rtrim($aredid, "|");
      $aredid = str_replace("|", ",", $aredid);
      $sql = 'SELECT"public"."T_AreaManage".am_id,"public"."T_AreaManage".am_name FROM "public"."T_AreaManage"WHERE"public"."T_AreaManage".am_id IN (' . $aredid . ')';
      //echo $sql;
      $stat = $this->pdo->query($sql);
      $result = $stat->fetchAll();
      $area = "";
      foreach($result as $item){
      if($item["am_name"] != ""){
      $area .= $item["am_name"].",";
      }
      }
      $area = rtrim($area,",");

      return $area;
      }
      }
     * */
}
