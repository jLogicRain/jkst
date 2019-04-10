<?php

$ini_key='ctj_user_exi_'.$SQ_UserID;
if(!YXL_HC_APP($ini_key))
{//初始化用户表检查或创建
	UserTable_Ini($SQ_UserID);
	YXL_HC_APP($ini_key,$DT);
}

/*对表做检查 如果不可用 执行修复语句 REPAIR TABLE  tablename */
$SQL="select ID from $SQ_UserTableName where ID=1 ";
$result = mysql_query($SQL);
if(!$result){$mysql_error='Error:'.mysql_error();if(stripos($mysql_error,'marked as crashed and should be repaired')>0||stripos($mysql_error,'Got error 134 from storage engine')>0){@mysql_query("REPAIR TABLE ".$SQ_UserTableName."");}die();}
/*对表做检查 如果不可用 执行修复语句 REPAIR TABLE  tablename */

$TDay=substr(DT(),0,10);
$TDayKey='CTC_'.$TDay;
$CTJ_TDay_Count=YXL_HC_APP($TDayKey);
if(!is_numeric($CTJ_TDay_Count))
{
	$CTJ_TDay_Count=0;
	$SQL="insert into ctj_report (tDay,tCount,tDT) values ('$TDay',0,'".DT()."')";
	//x($SQL);
	@mysql_query($SQL);
}

if($CTJ_TDay_Count>=1000)
{
	$SQL="update ctj_report set tCount=tCount+$CTJ_TDay_Count,tDT='".DT()."' where tDay='$TDay' ";
	//x($SQL);
	@mysql_query($SQL);

	$CTJ_TDay_Count=0;
	$CTJ_TDay_Count=YXL_HC_APP($TDayKey,$CTJ_TDay_Count);
}



$needDown='';
$needClean = '';
if($isYBJKAPP)
{	
	$SQL="select aYBJK,aYBJKm,aYBJKApp,aYBJKAppIMEI from ctj_user where SQ_UserID=$SQ_UserID limit 0,1 ";
	$row=getRow($SQL);
	if($row)
	{
		$aYBJK=$row[0];$aYBJKm=$row[1];$aYBJKApp=$row[2];$aYBJKAppIMEI=$row[3];
		//if($aYBJK||$aYBJKm)
		//{
		//if($aYBJKApp!=max($aYBJK,$aYBJKm,$aYBJKApp)){
		//	$needDown='Y';
		//}
		//}
		//如果网站比APP新，则提示更新
		if($aYBJK||$aYBJKm)
		{
			if($aYBJKApp!=max($aYBJK,$aYBJKm,$aYBJKApp)){
				$needDown='Y';
			}
		}
		//如果网站新，但上次更新IMEI与本次IMEI不一致，则提示更新,并清库
		if($needDown!='Y' && $aYBJKApp && $aYBJKAppIMEI != get_REQUEST_IMEI())
		{
			$needDown = 'Y';
			$needClean = 'Y';
		}
	}
}

?>