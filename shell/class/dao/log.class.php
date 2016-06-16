<?php

class log extends db
{

    public function log ( $data )
    {
        parent::__construct ();
        $this->data = $data;
        $this->table ( 'T_EventLog_' . $_SESSION['eown']['em_ent_id'] );
    }

    function change_to_quotes ( $str )
    {
        return sprintf ( "'%s'" , $str );
    }

    function getWhere ( $order = false )
    {
        $where = " WHERE 1=1 ";

        if ( $this->data["el_level"] != "" )
        {
            $where .= "AND el_level = " . $this->data["el_level"];
        }
        if ( $this->data["el_user"] != "" )
        {
            $where .= "AND el_user LIKE " . "E'%" . addslashes(str_replace ( " " , "" , $this->data["el_user"] )) . "%'";
        }
        if ( $this->data["el_content"] != "" )
        {
            $where .= "AND el_content LIKE " . "E'%" . addslashes(str_replace ( " " , "" , $this->data["el_content"] )) . "%'";
        }

        if ( $this->data["el_type"] != "" )
        {
            $arrId = $this->data["el_type"];
            $list = implode ( "," , $arrId );
            $list = str_replace ( "," , "', '" , "'" . $list );
            $list = rtrim ( $list , ",'" );
            $list.="'";
            $where .= "AND el_type IN (" . $list . ")";
        }
        if ( $this->data["start"] != "" || $this->data["end"] != "" )
        {
            $where .= 'AND el_time ' . getDateRange ( $this->data["start"] , $this->data["end"] );
        }
        if ( $order )
        {
            $where .= ' ORDER BY el_id desc ';
        }
        return $where;
    }

    public function getList ( $limit )
    {
        $table = $this->table;
        $sql = 'SELECT * FROM "' . $table . '"';
        $sql = $sql . $this->getWhere ( true );
        $sql = $sql . $limit;
        $stat = $this->pdo->query ( $sql );
        $result = $stat->fetchAll ();
        return $result;
    }

    public function delList ( $list )
    {
        $list = str_replace ( "," , "','" , "'" . $list );
        $list = rtrim ( $list , ",'" );
        $list .= "'";
        $sql = 'DELETE FROM "T_EventLog"WHERE"T_EventLog".el_id IN (' . $list . ')';
        //echo $sql;
        $count = $this->pdo->exec ( $sql );
        return $count;
    }

    public function getTotal ()
    {
        $table = $this->table;
        $sql = 'SELECT COUNT(el_id)AS total FROM"public"."' . $table . '"';
        $sql = $sql . $this->getWhere ();
        $pdoStatement = $this->pdo->query ( $sql );
        $result = $pdoStatement->fetch ();
        return $result["total"];
    }

}
