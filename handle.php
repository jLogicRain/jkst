<?php
/**
 * Created by PhpStorm.
 * User: logic
 * Date: 2019/4/9
 * Time: 13:34
 */
include './YXL.cn.php';
include './common.php';
$m = SQLChk($_REQUEST['m']);
//$SQ_UserID      = SQLChk($_REQUEST['SQ_UserID']);   //社区号id【SQ_UserID】
//$AppID          = SQLChk($_REQUEST['AppID']);       //APPid【AppID】
$TikuID         = SQLChk($_REQUEST['TikuID']);      //题库id【TikuID】
//$SortID         = SQLChk($_REQUEST['SortID']);      //sortid【SortID】
//$ExamID         = SQLChk($_REQUEST['ExamID']);      //试题id【ExamID】BaseID
$DriveType      = SQLChk($_REQUEST['DriveType']);   //车型【DriveType】

//if(!is_numeric($TikuID) || !$DriveType)
//{
//    outError('失败',"request params is error！");
//    exit();
//}
$TikuID = 123;
$DriveType = 'xc';

if($m)
{
    $userSQH = get_REQUEST_UID();
    //固定的测试数据
    $userSQH = '12000';
    $_REQUEST['add'] = $_REQUEST['del'] = "1202-dsfsfs,1203-sdfdsf,1204-sdfdsf,1205-sdfs";

//    if(!is_numeric($userSQH))
//    {
//        outError('失败',"not found,userSQH is empty！");exit();
//    }
    $conn=openConn();

    $tableData = get_mysql_table($userSQH);
    if($tableData['ok'])
    {
        $table = $tableData['msg'];
    } else {
        outError(false,$tableData['msg']);exit;
    }

    switch ($m)
    {

        case 'add':
            //插入数据
            $data = SQLChk($_REQUEST['add']);
            if($data)
            {
                $result = add($table,$userSQH,$AppID,$TikuID,$DriveType,$data);
                if($result['ok'])
                {
                    outOK($result);
                } else {
                    outError(false,$result['msg']);
                }
            } else {
                outError(false,'没有可执行的数据！');
            }

            break;
        case 'del':
            //删除数据
            $data = SQLChk($_REQUEST['del']);
            if($data)
            {
                $result = del($table,$userSQH,$AppID,$TikuID,$DriveType,$data);
                if($result['ok'])
                {
                    outOK($result);
                } else {
                    outError(false,$result['msg']);
                }
            } else {
                outError(false,'没有可执行的数据！');
            }

            break;
        default:
            outError('失败',"not found,action's params is error！");exit();
    }
} else {
    outError('失败',"not found,action's params is empty！");exit();
}

//新增方法
/*
 * @param:操作数据库表：fav_user
 * **/
function add($table,$userSQH = '',$AppID = '',$TikuID = '',$DriveType = '',$dataStr = '')
{
    //解析字符串
    $data = explode(',',$dataStr);
    if($data)
    {
        $user_id = get_user_id($AppID,$userSQH);
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

                if(!$alreadyData)
                {
                    //更新用户的UpCount
                    $LUDay=intval(substr(DTNum($CDT),0,8));
                    //新增试题数据信息
                    $sql = "insert into $table (UserID,AppID,TikuID,SortID,ExamID,DriveType,Tag,DT,DTN) values ('{$userSQH}','{$AppID}','{$TikuID}','{$addData[0]}','{$addData[1]}','{$DriveType}','{$tag}','{$CDT}','{$LUDay}')";
                    $result = sqlQuery($sql);
                    if($result)
                    {
                        $sql  = "update fav_user set UpCount=UpCount+1,BaseIDCount=BaseIDCount+1,LUDT='".$CDT."',LUDay=".$LUDay.",aDT='".$CDT."',aDTDay=".$LUDay." where ID='{$user_id}'";
                        sqlQuery($sql);
                    } else {
                        $save = false;
                    }
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
function del($table,$userSQH = '',$AppID = '',$TikuID = '',$DriveType = '',$dataStr = '')
{
    //默认该社区账号的所有的sortid-baseid数据全部删除
    $where = " `UserID` = '{$userSQH}' AND `AppID` = '{$AppID}' AND `TikuID` = '{$TikuID}' AND `DriveType` = '{$DriveType}' ";

    if($dataStr == 'all')
    {

    } else
    {
        $delDatas = explode('-',$dataStr);
        if($delDatas['1'] == 'all')
        {
            //该章节下的 SortID 全部删除
            $where .= " AND `SortID` = '{$delDatas['0']}' ";
        } else {
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
        }
    }
    if($where)
    {
        //统计删除的总条数
        $sql = "select count(0) as count from `{$table}` where {$where}";
        $query = sqlQuery($sql);
        $delCount = $query->fetch_assoc();

        $sql = "delete from `{$table}` where {$where}";
        $result = sqlQuery($sql);
        if ($result){
            $user_id = get_user_id($AppID,$userSQH);
            //更新用户收藏数量
            $CDT   = DT();
            $LUDay = intval(substr(DTNum($CDT),0,8));
            $del_nums = !empty($delCount['count']) ? $delCount['count'] : 0;
            $sql   = "update fav_user set BaseIDCount=BaseIDCount-$del_nums,LUDT='".$CDT."',LUDay=".$LUDay.",aDT='".$CDT."',aDTDay=".$LUDay." where ID='{$user_id}'";
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