<?php

/**
 * 协调器
 * 维护降低企业管理平台，运营管理平台差异
 * @todo 共用数据分离
 */
class ComDiff
{

    const CUR = "emp";

    static function IsDie ()
    {
        if ( CUR === "emp" )
        {
            die;
        }
    }

    static function GetOwnSession ()
    {
        if ( CUR === "emp" )
        {
            $own = $_SESSION['eown'];
        }
        else
        {
            $own = $_SESSION['own'];
        }
        return $own;
    }

}
