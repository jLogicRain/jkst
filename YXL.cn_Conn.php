<?php
$SQLQueryCount=0;	//数据库执行次数
function getSQLini($Team,$iniFile="")
{//读取数据库配置文件
    //echo db_ini_File;
	if(!$iniFile){$iniFile=db_ini_File;}	//echo 'SQL_ini:'.$iniFile;
	if(!file_exists($iniFile)){echo '<h2>发生了非常严重的错误！<p>数据库配置文件不存在或暂时无法读取！</h2>';die();}
	$iniArray=parse_ini_file($iniFile,true);
	return $iniArray[$Team];
}
$conn=null;
function openConn($db_Team_name='',$NewConn='N')
{
	global $conn;
	if((!$conn)||$NewConn=='Y')
	{
		if(!$db_Team_name&&db_ini_Team_name){$db_Team_name=db_ini_Team_name;}
		if($db_Team_name)
		{
			$db_cfg=getSQLini($db_Team_name);
			if(!is_array($db_cfg)){echo '<h2>发生了严重的错误！<p>数据库配置选项卡{'.$db_Team_name.'}不存在！</h2>';die();}
			if($db_cfg['run']!='Y'){echo '<h2>数据库服务器 ['.$db_Team_name.'] 服务暂停！请重新开启服务。</h2>';die();}
			$db_host=$db_cfg['host'];
			$db_user=$db_cfg['user'];
			$db_pass=$db_cfg['pass'];
			$db_name=$db_cfg['data'];
			$db_pcon=$db_cfg['pcon'];
		}
		else
		{
			if(!$db_host){$db_host=db_host;}
			if(!$db_user){$db_user=db_user;}
			if(!$db_pass){$db_pass=db_pass;}
			if(!$db_name){$db_name=db_name;}
			if(!$db_pcon){$db_pcon=db_pcon;}
		}
		
		unset($conn);
		@mysql_close($conn);
		@$conn = null;

		//echo $db_Team_name.'<br/>'.$db_host.'<br/>';echo '***'.str_ireplace('root','admin',substr($db_user,-4)).'***'.'<br/>';echo '***'.substr($db_pass,-4).'***'.'<br/>';echo $db_name.'<br/><hr>';


		if($db_pcon=='Y')
		{//使用长连接	mysqli 没有长连接
			$conn	=	mysqli_connect($db_host,$db_user,$db_pass,$db_name) or die('数据库链接失败: ' . mysqli_error($conn));
		}
		else
		{
			$conn	=	mysqli_connect($db_host,$db_user,$db_pass,$db_name) or die('数据库链接失败: ' . mysqli_error($conn));
		}
	}
	return $conn;
}
function mysql_CE($SQL,$ErrDebug='')
{
	global $SQLQueryCount;$SQLQueryCount++;	
	$mq=mysql_query($SQL);
	if($ErrDebug&&!$mq){echo '出错了：'.mysql_error();}
	return $mq;
}
function closeConn($connT)
{
		if ($connT) {@mysql_close($connT);}
}


/*2015/7/9	yxl.cn mysqli 支持的需要	begin*/
function getRow($f,$conn='')
{
	if($f)
	{
		if(!$conn){global $conn;}
		$resultT = mysqli_query($conn,$f);
		return mysqli_fetch_array($resultT);
		@mysqli_free_result($resultT);
	}
}
function sqlQuery($f,$conn='')
{
	if(!$conn){global $conn;}
	if(!$conn){die('<script>document.writeln("DateBase Not Connect!<br/>line at '.__LINE__.'");</script>');}
	return mysqli_query($conn,$f);
}

function sqlLastId($conn='')
{
    if(!$conn){global $conn;}
    if(!$conn){die('<script>document.writeln("DateBase Not Connect!<br/>line at '.__LINE__.'");</script>');}
    return mysqli_insert_id($conn);
}
//function mysql_query($f,$conn='')
//{
//    echo $f;die();
//	if(!$conn){global $conn;}
//	if(!$conn){die('<script>document.writeln("DateBase Not Connect!<br/>line at '.__LINE__.'");</script>');}
//	return mysqli_query($conn,$f);
//}
//
//function mysql_fetch_array($f)
//{
//	return mysqli_fetch_array($f);
//}

//function mysql_free_result($f)
//{
//	return mysqli_free_result($f);
//}
//
//function mysql_close($f)
//{
//	return mysqli_close($f);
//}
//
//function mysql_error($conn='')
//{
//	if(!$conn){global $conn;}
//	return mysqli_error($conn);
//}
/*2015/7/9	yxl.cn	mysqli 支持的需要	end*/

?>