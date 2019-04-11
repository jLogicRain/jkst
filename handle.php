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
$add            = !empty($_REQUEST['a']) ? SQLChk($_REQUEST['a']) : ''; //新增数据集
$del            = !empty($_REQUEST['d']) ? SQLChk($_REQUEST['d']) : ''; //删除数据集
$empty          = !empty($_REQUEST['e']) ? SQLChk($_REQUEST['e']) : ''; //清空数据集

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

if(!$add && !$del && !$empty)
{
    outError('没有可执行的动作！',402);exit();
}

$tableData = get_mysql_table($userSQH);

if($tableData['ok'])
{
    $table = $tableData['msg'];
} else {
    outError($tableData['msg'],403);exit();
}

$msg = '';
if($add)
{
    //插入数据
    $result = add_data($table,$userSQH,$AppID,$TikuID,$DriveType,$add,$Source);
    if(!$result['ok'])
    {
        $msg .= '新增失败，';
    }
}

if($del)
{
    //删除数据
    $result = del_data($table,$userSQH,$AppID,$TikuID,$DriveType,$del,$Source);
    if(!$result['ok'])
    {
        $msg .= '删除失败，';
    }
}

if($empty)
{
    $result = empty_data($table,$userSQH,$AppID,$TikuID,$DriveType,$empty,$Source);
    if(!$result['ok'])
    {
        $msg .= '清空失败。';
    }
}

if($msg)
{
    outError($msg);
} else {
    outOK();
}

//新增方法
/*
 * @param:操作数据库表：fav_user
 * **/
function add_data($table,$userSQH = '',$AppID = '',$TikuID = '',$DriveType = '',$dataStr = '',$Source = "")
{
    //解析字符串
    $data = explode(',',$dataStr);
    if($data)
    {
        $user_id = get_user_id($AppID,$userSQH,$Source);
        $CDT     = DT();
        $save    = true;
        foreach($data as $k => $v)
        {
            $addData = explode('-',$v);
            if(!empty($addData[0]) && !empty($addData[1]))
            {
                //md5 加密 tag
                $tag = MD5($userSQH.$AppID.$TikuID.$addData[0].$addData[1].$DriveType);
                //查找当数据不存在时进行添加动作
                $sql            = "select `ID` from $table where Tag = '{$tag}'";
                $query          = sqlQuery($sql);
                $alreadyData    = $query->fetch_assoc();

                //更新用户的UpCount
                $LUDay=intval(substr(DTNum($CDT),0,8));
                if(!$alreadyData)
                {
                    //新增试题数据信息
                    $sql = "insert into $table (UserID,AppID,TikuID,SortID,ExamID,DriveType,Tag,DT,DTN) values ('{$userSQH}','{$AppID}','{$TikuID}','{$addData[0]}','{$addData[1]}','{$DriveType}','{$tag}','{$CDT}','{$LUDay}')";
                    $result = sqlQuery($sql);
                    if($result)
                    {
                        $sql  = "update fav_user set UpCount=UpCount+1,BaseIDCount=BaseIDCount+1,LUDT='".$CDT."',LUDay='".$LUDay."',aDT='".$CDT."',aDTDay='".$LUDay."',aSource='".$Source."' where ID='{$user_id}'";
                        sqlQuery($sql);
                    } else {
                        $save = false;
                    }
                } else {
                    $sql = "update $table set DT = '".$CDT."',DTN = '".$LUDay."' where Tag = '{$tag}'";
                    sqlQuery($sql);
                }
            }
        }

        if($save)
        {
            return array('ok' => true,'msg' => '任务执行成功。');
        }

    } else {
        return array('ok' => false,'msg' => '数据丢失，删除任务执行失败！');
    }
}

//删除方法
/*
 * @param:操作数据库表：t....[根据社区账号]【删除条件 AppID,TikuID,SortID,ExamID,DriveType】
 * **/
function del_data($table,$userSQH = '',$AppID = '',$TikuID = '',$DriveType = '',$dataStr = '',$Source = '')
{
    //默认该社区账号的所有的sortid-baseid数据全部删除
    $where = " `UserID` = '{$userSQH}' AND `AppID` = '{$AppID}' AND `TikuID` = '{$TikuID}' AND `DriveType` = '{$DriveType}' ";
    //批量删除特定 sortid-baseid 的全部数据
    $delData  = explode(',',$dataStr);
    $condition= array();
    if($delData)
    {
        foreach($delData as $k => $v)
        {
            $delDatas = explode('-',$v);
            $cond = $where;
            if(!empty($delDatas[0]) && !empty($delDatas[1]))
            {
                $cond .= " AND `SortID` = '{$delDatas[0]}' AND `ExamID` = '{$delDatas[1]}' ";
                $condition[] = '('.$cond.')';
            }
        }
        $where = $condition ? implode(" OR ",$condition) : array();
    } else {
        return array('ok' => false,'msg' => '数据丢失，删除任务执行失败！');
    }

    return send_del($where,$table,$AppID,$userSQH,$Source);

}

//批量删除
function empty_data($table,$userSQH = '',$AppID = '',$TikuID = '',$DriveType = '',$dataStr = '',$Source = '')
{
    $where = " `UserID` = '{$userSQH}' AND `AppID` = '{$AppID}' AND `TikuID` = '{$TikuID}' AND `DriveType` = '{$DriveType}' ";
    if($dataStr == 'all')
    {

    } else
    {
        //该章节下的 SortID 全部删除
        $del_str = implode(',',explode(',',$dataStr));
        $where .= " AND `SortID` IN ($del_str) ";
    }
    return send_del($where,$table,$AppID,$userSQH,$Source);
}

function send_del($where,$table,$AppID,$userSQH,$Source)
{
    if($where)
    {
        //统计删除的总条数
        $sql = "select count(0) as count from `{$table}` where {$where}";
        $query = sqlQuery($sql);
        $delCount = $query->fetch_assoc();

        $sql = "delete from `{$table}` where {$where}";
        $result = sqlQuery($sql);
        if ($result){
            $user_id = get_user_id($AppID,$userSQH,$Source);
            //更新用户收藏数量
            $CDT   = DT();
            $LUDay = intval(substr(DTNum($CDT),0,8));
            $del_nums = !empty($delCount['count']) ? $delCount['count'] : 0;
            $sql   = "update fav_user set BaseIDCount=BaseIDCount-$del_nums,LUDT='".$CDT."',LUDay='".$LUDay."',aDT='".$CDT."',aDTDay='".$LUDay."',aSource='".$Source."' where ID='{$user_id}'";
            sqlQuery($sql);
            return array('ok' => true,'msg' => '删除任务执行成功。');
        }else {
            return array('ok' => false,'msg' => '删除任务执行失败！');
        }
    } else {
        return array('ok' => false,'msg' => '删除任务执行失败！');
    }
}

@mysql_free_result($result);
@mysql_close($conn);