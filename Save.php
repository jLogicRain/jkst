<?php
include './YXL.cn.php';


/*
//	http://fav.mnks.cn/Save.php
方式	GET|POST
参数
tiku	值kmy|kms	
*/

$outArray=array();
$outArray['result']='success';
$outArray['resume']='操作成功';
$outArray['datetime']=$DT;



if(!$SQ_UserID){$outArray['result']='faild';$outArray['resume']='无用户ID！';echo json_encode($outArray);unset($dataArray);unset($outArray);die;}
$SQ_UserTableName=userTab($SQ_UserID);
if(!$SQ_UserTableName){$outArray['result']='faild';$outArray['resume']='用户ID无效！';echo json_encode($outArray);unset($dataArray);unset($outArray);die;}

$db_Team_name='Team_yxl_jsyks_ctj';
$conn=openConn($db_Team_name);
if(mysql_error()){$outArray['result']='faild';$outArray['resume']='服务器维护中...';echo json_encode($outArray);unset($dataArray);unset($outArray);die;}



include './YXL.cn_App_Task.php';


$TikuID=fTikuID($_REQUEST['tiku']);
if($TikuID>0){$TikuID_SQL=" and TikuID=$TikuID ";}








$m=strtolower(Trim($_REQUEST['m']));
$c=strtolower(Trim($_REQUEST['c']));


//$AppID=Trim($_GET['AppID']);
//if(!is_numeric($AppID)){$AppID=11;}
$TikuID=Trim($_REQUEST['TikuID']);
$TikuID=fTikuID($TikuID);
if(!is_numeric($TikuID)){$TikuID=$kTiku;}
//if(!is_numeric($TikuID)){$TikuID=0;}
if(!is_numeric($TikuID)){outMsg($c,'106');die;}
if(intval($TikuID)<=0){outMsg($c,'106');die;}

$DriveType=SQLChk(str_qyh(Trim($_REQUEST['DriveType'])));
if(!$DriveType){$DriveType='xc';}
$DriveType=DT2CX($DriveType);
if(!$DriveType){$DriveType='xc';}


if($m=='add')
{//单个加入

	$SortID=Trim($_REQUEST['SortID']);
	if(!is_numeric($SortID)){$SortID=0;}
	if(!($SortID>0)){outMsg($c,'106');die;}

	$ExamID=Trim($_REQUEST['id']);
	if($ExamID=='undefined'){outMsg($c,'201');die;}
	$ExamID=str_qyh($ExamID);
	$ExamID=SQLChk($ExamID);

	if(!($ExamID&&strlen($ExamID)>3)){outMsg($c,'201');die;}

}
else if($m=='batchadd')
{//BatchAdd 批量加入
	if(!$c){$c='alert';}

	$ExamIDs=Trim($_REQUEST['IDs']);
	if($ExamIDs=='undefined'){$ExamIDs='';}
	$SortIDs=Trim($_REQUEST['SortIDs']);
	if($SortIDs=='undefined'){$SortIDs='';}
	
	//x('ExamIDs<br/>'.$ExamIDs.chr(13).chr(10));
	//x('SortIDs<br/>'.$SortIDs.chr(13).chr(10));

	if(!$ExamIDs){outMsg($c,'208');die;}
	if(!$SortIDs){outMsg($c,'209');die;}

	$ExamIDs=str_qyh($ExamIDs);	$ExamIDs=SQLChk($ExamIDs);	$ExamIDs=str_replace(',,,,',',',$ExamIDs);	$ExamIDs=str_replace(',,,',',',$ExamIDs);	$ExamIDs=str_replace(',,',',',$ExamIDs);	$ExamIDs=str_qsw($ExamIDs);	$ExamIDs=str_qsw($ExamIDs);	$ExamIDs=str_qsw($ExamIDs);
	$SortIDs=str_qyh($SortIDs);	$SortIDs=SQLChk($SortIDs);	$SortIDs=str_replace(',,,,',',',$SortIDs);	$SortIDs=str_replace(',,,',',',$SortIDs);	$SortIDs=str_replace(',,',',',$SortIDs);	$SortIDs=str_qsw($SortIDs);	$SortIDs=str_qsw($SortIDs);	$SortIDs=str_qsw($SortIDs);

	if(!$ExamIDs){outMsg($c,'208');die;}
	if(!$SortIDs){outMsg($c,'209');die;}
	if(!($ExamIDs&&strlen($ExamIDs)>3)){outMsg($c,'207');die;}

}



//outMsg($c,'105');die;	//die('错题集暂停服务');


$SQH=Trim($_REQUEST['SQH']);
if(is_numeric($SQH))
{
	$SQU=Trim($_REQUEST['SQU']);if(!$SQU){$SQH='';}
	$SQC=Trim($_REQUEST['SQC']);if(!$SQC){$SQH='';}
	$SQC_Val=md5($SQH.'YXL.CN'.$SQU.substr(DT(),0,10));
	if($SQC_Val!=$SQC){$SQH='';}
	unset($SQU);unset($SQC);unset($SQC_Val);
	$SQ_UserID=$SQH;unset($SQH);
}


if(!$SQ_UserID){outMsg($c,'101');die;}
$SQ_UserTableName=CTJTab($SQ_UserID);
if(!$SQ_UserTableName){outMsg($c,'103');die;}

$db_Team_name='Team_yxl_jsyks_ctj';
$conn=openConn($db_Team_name);
if(mysql_error()){outMsg($c,'102');die;}

include './Fun_App_CTJ_task.php';

if($m=='add')
{//单个加入
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
else if($m=='batchadd')
{//批量加入

		$ExamIDArray=explode(',',$ExamIDs);
		$SortIDArray=explode(',',$SortIDs);			$SortIDLen=count($SortIDArray);		$SortIDVal=$SortIDArray[0];
		$ic=0;
				//x('ExamIDs<br/>'.$ExamIDs.chr(13).chr(10));
				//x('SortIDs<br/>'.$SortIDs.chr(13).chr(10));
				//x('ExamIDsLen='.count($ExamIDArray).chr(13).chr(10));
				//x('SortIDsLen='.count($ExamIDArray).chr(13).chr(10));
		foreach($ExamIDArray as $ExamID)
		{
			if($ExamID)
			{
				$CTJ_TDay_Count++;
				if($SortIDLen==1)
				{
					$SortID=$SortIDVal;
				}
				else
				{
					$SortID=$SortIDArray[$ic];	if(!is_numeric($SortID)){$SortID=0;}
				}
							//x($ExamID.'=-='.$SortID.chr(13).chr(10));
				$Tag=MD5($SQ_UserID.$AppID.$TikuID.$SortID.$ExamID.$DriveType);

				$SQL="insert into $SQ_UserTableName (UserID,AppID,TikuID,SortID,ExamID,DriveType,Tag,DT,ErrCount) values ($SQ_UserID,$AppID,$TikuID,$SortID,'$ExamID','$DriveType','$Tag','".$DT."',".strtotime($DT).",0)";
				//x($ic.'-'.$SQL.chr(13).chr(10));					
				@mysql_query($SQL);

				$SQL="update $SQ_UserTableName set ErrCount=ErrCount+1,DT='".$DT."',DTN=".strtotime($DT)." where Tag='$Tag'";
				//x($ic.'-'.$SQL.chr(13).chr(10));
				@mysql_query($SQL);		
			}
			$ic++;
		}

		$SQL="select count(0) from $SQ_UserTableName where UserID=$SQ_UserID";
		$row=getRow($SQL);
		$ErrIDCount=$row[0];
		unset($row);


		$DT=DT();	$LUDay=intval(substr(DTNum($DT),0,8));

		$SQL="update CTJ_User set LUDay=$LUDay,LUDT='$DT',LUIP='".myIP()."',ErrIDCount=$ErrIDCount".$aFieldSQL." where SQ_UserID=$SQ_UserID ";
		//x($SQL);
		@mysql_query($SQL);
		outMsg($c,'206');
}



if(is_numeric($CTJ_TDay_Count)){$CTJ_TDay_Count=YXL_HC_APP($TDayKey,$CTJ_TDay_Count);}


//关闭或注销一些对象
@mysql_free_result($result);
@mysql_close($conn);
?>