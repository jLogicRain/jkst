<?php
/**
 * Created by PhpStorm.
 * User: logic
 * Date: 2019/4/9
 * Time: 13:34
 */
include './YXL.cn.php';
include './common.php';

$TikuID         = SQLChk($_REQUEST['t']);      //题库id【TikuID】
$DriveType      = SQLChk($_REQUEST['c']);       //车型【DriveType】
$userSQH        = get_REQUEST_UID();

$TikuID = fTikuID($TikuID);
//本地测试数据
if($_SERVER['SERVER_NAME'] == 'yb.jkst.cn')
{
    $userSQH    = '126000';
    $TikuID     = 2345;
    $DriveType  = 'xc';
}

if(!is_numeric($TikuID) || !$DriveType)
{
    outError("request params is error！",403);
    exit();
}

$conn=openConn();
$tableData = get_mysql_table($userSQH);
if($tableData['ok'])
{
    $table = $tableData['msg'];
} else {
    outError($tableData['msg'],401);exit();
}
$sql    = "select `SortID`,`ExamID` from $table where `UserID`= '{$userSQH}' AND `AppID` = {$AppID} AND `TikuID` = '{$TikuID}' AND `DriveType` = '{$DriveType}'";
$query  = sqlQuery($sql);
$data   = $query->fetch_all();

//每获取一次查询更新一次用户动态：
$user_id = get_user_id($AppID,$userSQH);
if($user_id)
{
    $CDT   = DT();
    $LUDay = intval(substr(DTNum($CDT),0,8));
    $IP    = myIP();
    $sql   = "update fav_user set DownCount=DownCount+1,LUDT='".$CDT."',LUDay='".$LUDay."',aDT='".$CDT."',aDTDay='".$LUDay."',LDDT='".$CDT."',LDIP='".$IP."',aSource='".$Source."' where ID='{$user_id}'";
    sqlQuery($sql);
}

$newData = $jsonData = array();

if($data)
{
    foreach($data as $k => $v)
    {
        $newData[$v[0]][] = $v[1];
    }

    if($newData)
    {
        foreach ($newData as $k => $v)
        {
            $jsonData[] = array('sortID' => $k,'baseId' => $v);
        }
    }
}

outOK($jsonData);

@mysql_free_result($result);
@mysql_close($conn);
