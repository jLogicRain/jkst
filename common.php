<?php
/**
 * Created by PhpStorm.
 * User: logic
 * Date: 2019/4/9
 * Time: 13:34
 */

$conn=openConn();

function get_user_id($AppID = '',$SQ_UserID = '')
{
    $key = 'jsyks'.$AppID.$SQ_UserID;
    $user_id = YXL_HC_APP($key);
    if(empty($user_id))
    {
        $sql    = "select `ID` from fav_user where `AppID` = '{$AppID}' and `SQ_UserID` = {$SQ_UserID}";
        $query  = sqlQuery($sql);
        $data   = $query->fetch_assoc();
        if($data)
        {
            $user_id = $data['ID'];
            YXL_HC_APP($key,$data['ID'],3600*120);
        } else {
            $CDT    = DT();
            $IP     = myIP();
            $sql    = "insert into fav_user (AppID,SQ_UserID,CDT,CIP) values ('{$AppID}','{$SQ_UserID}','{$CDT}','{$IP}')";
            sqlQuery($sql);
            $user_id= sqlLastId();
            YXL_HC_APP($key,$user_id,3600*120);
        }
    }
    return $user_id;
}

function get_mysql_table($userSQH = '')
{
    //截取社区号后三位确定要操作的数据库表
    $table = 't'.substr($userSQH,-3);
    if(mysqli_num_rows(sqlQuery("SHOW TABLES LIKE '". $table."'"))==1) {
        return array('ok' => true,'msg' => $table);
    } else {
        return array('ok' => false,'msg' => 'table does not exist！');
    }
}

@mysql_free_result($result);
@mysql_close($conn);