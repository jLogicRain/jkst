<?php

header("Access-Control-Allow-Origin: *");		//表示允许别人跨域访问

$tYear=Date('Y');

$thisDomain=thisDomain();
$DT=DT();
$IP=myIP();


$UA=$_SERVER['HTTP_USER_AGENT'];
$isYBJKAPP='';$isJKBLAPP='';$isJKSTAPP='';
if(stripos('YXL'.$UA,'com.runbey.ybjk/')>0||stripos('YXL'.$UA,'com.runbey.rbjk/')>0){$isYBJKAPP='Y';}
elseif(stripos('YXL'.$UA,'com.runbey.jkbl/')>0){$isJKBLAPP='Y';}
elseif(stripos('YXL'.$UA,'com.runbey.jkst/')>0){$isJKSTAPP='Y';}


/*
	AppID		11-jsyks.com	12-ybjk.com		13-jkbl.com			19-jiakaoshuati.com
*/

$Source = "error";

if($isYBJKAPP){$AppID=12;		$Source='YBJK';}
elseif($isJKBLAPP){$AppID=13;	$Source='JKBL';}
elseif($isJKSTAPP){$AppID=19;	$Source='JKST';}
if(!is_numeric($AppID)){$AppID=$_REQUEST['appid'];}

if(!is_numeric($AppID)){die(__LINE__.'Line');}

/*
$myFile=myFile();$myFile=str_ireplace('.php','',$myFile);
if(stripos('yxl/inde_/mnks/sxlx/sjlx/qlx/lx/ks/test/pc_a/ctj/xml_getExamCnt/ctj_up/save_diqu_dt/sel_diqu_dt/',$myFile)>0)
{
	//x('维护中...');
	//die;
}

if($myFile!='index.php')
{
	//x('正在维护中...');
	//die;
}
*/







/* ====================== 相关缓存及处理函数开始 ====================== */
$HCPath='R:/YXL_HC/';
function YXL_HC_APP($key,$Val='R',$exp=0)
{//YXL_HC mc失败 自动切换到文件缓存 YXL_HC文件夹
	global $redis;
	global $mc;	
	$exp=(!is_numeric($exp) && strtotime($exp)>0)?$exp:abs(intval($exp));//罡子
	if($mc)
	{//x('mc缓存');
		if($exp==999){$exp=0;}
		$Cnt=YXL_HC($mc,$key,$Val,$exp);
	}
	elseif($redis)
	{//x('redis缓存');
		$Cnt=YXL_HC_Redis($key,$Val,$exp);
	}
	else
	{//x('YXL_HC文件缓存');
		global $HCPath;
		if($exp==999){$exp=0;}
		if(!($HCPath)){$HCPath=$_SERVER["DOCUMENT_ROOT"].'/YXL_HC';}
		$Cnt=YXL_HC_File($HCPath,$key,$Val,$exp);
	}
	if(strlen($Cnt)>0){}else{$Cnt='';}
	return $Cnt;
}
/* ====================== 相关缓存及处理函数结束 ====================== */



function fTikuID($TikuID=0)
{
	$TikuID=strtolower($TikuID);
	$TikuID=str_replace('kmy','2013',$TikuID);	$TikuID=str_replace('km1','2013',$TikuID);
	$TikuID=str_replace('kms','6013',$TikuID);	$TikuID=str_replace('km4','6013',$TikuID);
	$TikuID=str_replace('hy','6071',$TikuID);	$TikuID=str_replace('ky','6072',$TikuID);	$TikuID=str_replace('wxp','6073',$TikuID);	$TikuID=str_replace('czc','6074',$TikuID);	$TikuID=str_replace('jly','6075',$TikuID);	$TikuID=str_replace('wyc','6076',$TikuID);
	if(!is_numeric($TikuID)){$TikuID=0;}
	return $TikuID;
}


function userTab($f)
{//通过用户ID 告知存储的表名称
	if(!is_numeric($f)){return '';}
	return 'T'.substr('000'.$f,-3);
}



if(!$_appinfo){$_appinfo=$_SERVER['HTTP_RUNBEY_APPINFO'];}
if(!$_appinfo){$_appinfo=$_REQUEST['_appinfo'];}
if($_appinfo){$_appinfo=str_replace(' ','+',$_appinfo);$_appinfo=base64_decode($_appinfo);$_appinfo=json_decode($_appinfo,true);}
if(!is_array($_appinfo)){$_appinfo=array();}



/* 生成字段校验证码
	来源 authPassPort	\YXL.cn_Fun_Auth.php
 */
function authCreateSECKey($source){
	$key = "YXL.YBJK.1017";
	$source = $source ? $source : MD5(time());
	$target = substr($source,-5)  .$key. substr($source,0,-5);
	$target = MD5($key . MD5(MD5($target) . $key));
	return $target;
}


function get_REQUEST_UID()
{//从Cook返回UID 判断加密EU	
	$userSQH	=	$_REQUEST['userSQH'];$userSQHKEY	=	$_REQUEST['userSQHKEY'];
	if(!is_numeric($userSQH)){$userSQH=$_SERVER['HTTP_RUNBEY_APPINFO_SQH'];$userSQHKEY=$_SERVER['HTTP_RUNBEY_APPINFO_SQHKEY'];}
	if(!is_numeric($userSQH)){global $_appinfo;$userSQH=$_appinfo['userSQH'];$userSQHKEY=$_appinfo['userSQHKEY'];}
	if(!($userSQH&&is_numeric($userSQH))){return '';}	
	if($userSQHKEY<>authCreateSECKey($userSQH)){return '';}
	return $userSQH;
}

function get_REQUEST_IMEI(){
	if(!$_appinfo){$_appinfo=$_SERVER['HTTP_RUNBEY_APPINFO'];}
	if(!$_appinfo){$_appinfo=$_REQUEST['_appinfo'];}
	if($_appinfo)
	{
		//wLog($_appinfo);
		if(substr($_appinfo,0,1)=='{'&&substr($_appinfo,-1)=='}')
		{}
		else
		{	
			$_appinfo=str_replace(' ','+',$_appinfo);
			$_appinfo=base64_decode($_appinfo);
		}
		$_appinfo=json_decode($_appinfo,true);
	}
	if(is_array($_appinfo))
	{
		$imei=$_appinfo['imei'];
	}
	else{
		$imei=$_REQUEST['imei'];
	}
	unset($_appinfo);
	$imei = str_qyh(trim($imei));
	$imei = SQLChk($imei);
	return $imei;	
}


$SQ_UserID=get_REQUEST_UID();

$comeURL=comeURL();


/*
if(rand(1,20)<=2)
{
	if(stripos('YXL'.myFile(),'sync_v')||stripos('YXL'.myFile(),'save.php'))
	{
		$DEBUG='Y';
		wLog('comeURL='.$comeURL.'		source='.$source.'		cSource='.$cSource.'		aFieldSQL='.$aFieldSQL.'		myURL='.myURL().'		>>	'.myFile().'	line='.__LINE__."\n							".$_SERVER['HTTP_USER_AGENT']);
	}
}
*/

function UserTable_Ini($SQ_UserID)
{//用户表初始化		检查用户是否已经记录在FAV_User表中	如果没有就创建
	global $Source;
	global $AppID;
	$IP=myIP();
	$DT=DT();	$LUDay=Date("Ymd");
	
	$SQLXX	=	"insert into FAV_User (AppID,SQ_UserID,CDT,CIP,LUDay,LUDT,LUIP,UpCount,LDDT,LDIP,DownCount,BaseIDCount,cSource,aSource,aDT) values 
	($AppID,$SQ_UserID,'$DT','$IP',$LUDay,'$DT','$IP',0,'".$DT."','$IP',0,0,'$Source','$Source','$DT')";
	//x($SQLXX);
	@mysql_query($SQLXX);
	unset($SQLXX);
}

?>