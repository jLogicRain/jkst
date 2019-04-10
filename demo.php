<?php
include './YXL.cn.php';
/*
地址	https://fav.mnks.cn/demo.php
方式	GET|POST
参数
tiku	值kmy|kms	
*/

$outArray=array();
$outArray['result']		= 'success';
$outArray['resume']		= '操作成功';
$outArray['datetime']	= $DT;

$conn=openConn();
if(mysql_error()){outMsg($c,'102');die;}
$m=strtolower(Trim($_REQUEST['m']));

if($m=='add')
{//单个加入
        $sql = "select * from fav_report";
        //$row=getRow($SQL);
        //$ErrIDCount=$row[0];
        //unset($row);
        $m = sqlQuery($sql);
        $row=$m->fetch_all(MYSQLI_NUM);
        echo "<pre>";print_r($row);die();
		$CTJ_TDay_Count++;
		$Tag=MD5($SQ_UserID.$AppID.$TikuID.$SortID.$ExamID.$DriveType);
		//$SQL="select ID from $SQ_UserTableName where UserID=$SQ_UserID and ExamID='$ExamID'";

		$SQL="insert into $SQ_UserTableName (UserID,AppID,TikuID,SortID,ExamID,DriveType,Tag,DT,DTN,ErrCount) values ($SQ_UserID,$AppID,$TikuID,$SortID,'$ExamID','$DriveType','$Tag','".$DT."',".strtotime($DT).",0)";

		@mysql_query($SQL);

		$SQL="update $SQ_UserTableName set ErrCount=ErrCount+1,DT='".$DT."',DTN=".strtotime($DT)." where Tag='$Tag'";
		@mysql_query($SQL);

		$SQL="select count(0) from $SQ_UserTableName where UserID=$SQ_UserID";
		$row=getRow($SQL);
		$ErrIDCount=$row[0];
		unset($row);

		$DT=DT();	$LUDay=intval(substr(DTNum($DT),0,8));

		$SQL="update CTJ_User set LUDay=$LUDay,LUDT='$DT',LUIP='".myIP()."',ErrIDCount=$ErrIDCount".$aFieldSQL." where SQ_UserID=$SQ_UserID ";
		@mysql_query($SQL);
		outMsg($c,'200');
}


if(is_numeric($CTJ_TDay_Count)){$CTJ_TDay_Count=YXL_HC_APP($TDayKey,$CTJ_TDay_Count);}


//关闭或注销一些对象
@mysql_free_result($result);
@mysql_close($conn);
?>