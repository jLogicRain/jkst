<?php
/**
 * Created by PhpStorm.
 * User: logic
 * Date: 2019/4/9
 * Time: 13:34
 */
include './YXL.cn.php';
include './common.php';

$TikuID         = SQLChk($_REQUEST['TikuID']);      //题库id【TikuID】
$DriveType      = SQLChk($_REQUEST['DriveType']);   //车型【DriveType】
$userSQH        = get_REQUEST_UID();

//if(!is_numeric($TikuID) || !$DriveType)
//{
//    outError('失败',"request params is error！");
//    exit();
//}

$TikuID = 123;
$DriveType = 'xc';
$userSQH = '12000';

$conn=openConn();
$tableData = get_mysql_table($userSQH);
if($tableData['ok'])
{
    $table = $tableData['msg'];
} else {
    outError(false,$tableData['msg']);exit;
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
    $sql   = "update fav_user set DownCount=DownCount+1,LUDT='".$CDT."',LUDay='".$LUDay."',aDT='".$CDT."',aDTDay='".$LUDay."',LDDT='".$CDT."',LDIP='".$IP."' where ID='{$user_id}'";
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
            $jsonData[] = array('SortID' => $k,'BassId' => $v);
        }
    }
}

if($jsonData)
{
    outOK($jsonData);
}else {
    outError('失败',"No data were found！");exit();
}

@mysql_free_result($result);
@mysql_close($conn);
