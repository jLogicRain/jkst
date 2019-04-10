<?php
/*
	自定义的函数
	Author:yxl.cn
	Create:20110207
	UpDate:20110625
*/

function x($strTmp="")
{	//调试用 用于快速写出 后加<br> 等价于echo $strTmp.'<br />';
	echo $strTmp."<br />";
}

function xx($strTmp="")
{	//调试用 用于快速写出 不加<br> 等价于echo $strTmp.'<br />';
	echo $strTmp;
}

function RndStr($strLen=0)
{	//输入$strLen长度的随机字符
	if ($strLen<=0) {$strLen=rand(1,10);};
	$strA=array("0", "1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","j","k","m","n","p","q","r","s","t","u","w","x","y","z");
	$strT="";
	for ($strL=1; $strL<=$strLen;$strL++)
	{
	 $strT=$strT.$strA[rand(0,31)];
	}
	return $strT;
}

function RndHex($strLen=0)
{	//输入$strLen长度的随机字符(0-10,a,b,c,d,e,f) 默认8位 最长100位
	if ($strLen<=0||$strLen>100) {$strLen=8;}
	$strA=array("0", "1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");
	$strT="";
	for ($strL=1; $strL<=$strLen;$strL++)
	{
		$strT=$strT.$strA[rand(0,15)];
	}
	return $strT;
}

function RndNum($NumLen=0)
{	//$NumLen位随机数字
	if ($NumLen<=0) {return rand();};
	$_min="1".str_repeat("0", $NumLen-1);
	$_max=str_repeat("9", $NumLen);
	return rand($_min,$_max);
}

function gb2u8($strTmp="")
{	//gb2312转成utf-8
	$a=iconv("gb2312","utf-8//IGNORE",$strTmp);
	if ($a=="") {return $strTmp;} else {return $a;}
}

function u82gb($strTmp="")
{	//utf-8转成gb2312
	$a=iconv("utf-8","gb2312//IGNORE",$strTmp);
	if ($a=="") {return $strTmp;} else {return $a;}
}

function __GET($f)
{
	return SQLChk($_GET[$f]);
}
function __POST($f)
{
	return SQLChk($_POST[$f]);
}
function __Request($f)
{
	return SQLChk($_REQUEST[$f]);
}
function SQLChk($f)
{	//过滤非法字符
	if(!$f){return $f;}
	$str=$f;
	$str=trim($str);
	$str=str_ireplace("\\","",$str);
	$str=str_ireplace("'","\'",$str);
	$str=str_ireplace('"','\"',$str);
	$str=str_ireplace(";","\;",$str);
	$str=str_ireplace("--","\--",$str);	
	$str=str_ireplace("/*","",$str);
	$str=str_ireplace("*/","",$str);
	$str=str_ireplace("<?","&lt;?",$str);
	$str=str_ireplace("?>","?&gt;",$str);
	$str=str_ireplace(" and ","",$str);
	$str=str_ireplace(" from ","",$str);
	$str=str_ireplace("select ","",$str);
	$str=str_ireplace("insert ","",$str);
	$str=str_ireplace("update ","",$str);
	$str=str_ireplace("delete ","",$str);
	$str=str_ireplace("like ","",$str);
	$str=str_ireplace("execute","",$str);
	$str=str_ireplace("exec","",$str);
	$str=str_ireplace("drop ","",$str);
	$str=str_ireplace("asc(","",$str);
	$str=str_ireplace("mid(","",$str);
	$str=str_ireplace("chr(","",$str);
	$str=str_ireplace("char(","",$str);
	$str=str_ireplace("union ","",$str);
	$str=str_ireplace(" or ","",$str);
	$str=str_ireplace("ALTER ","",$str);
	$str=str_ireplace("CREATE ","",$str);
	$str=str_ireplace("cast ","",$str);
	$str=str_ireplace("exists","",$str);
	$str=str_ireplace(" table ","",$str);
	$str=str_ireplace("count(","",$str);
	$str=str_ireplace("load_file","",$str);
	$str=str_ireplace("outfile","",$str);
	$str=str_ireplace(" where ","",$str);
	$str=str_ireplace("eval(","",$str);
	$str=str_ireplace("query(","",$str);
	
	$str=str_ireplace("%20","",$str);
	$str=str_ireplace(Chr(0),"",$str);
	return $str;
}
function str_qyh($f)
{	//去掉引号 一般用于alt title标签等
	if(!$f){return $f;}
	$str=$f;
	$str=str_ireplace("'","",$str);$str=str_ireplace('"','',$str);
	return $str;
}
function str_qlx($f,$f2=',')
{	//去掉连续符号$f2 使其只剩下一个$f2
	if(!$f){return $f;}
	if(!$f2){return $f;}
	$str=$f;
	while(strpos('{YXL.CN_STR_QLX}'.$str,$f2.''.$f2)>0)
	{
		$str=str_replace($f2.''.$f2,$f2,$str);
	}
	return $str;
}
function str_qcf($f,$f2=',')
{	//去掉$f中重复的数值 $f2是分隔符
	if(!$f){return $f;}
	if(!$f2){return $f;}
	$f=str_qlx($f,$f2);
	$f=$f2.$f.$f2;
	$fArr=explode($f2,$f);
	$f1=''.$f2.$f2;
	foreach($fArr as $v)
	{
		if($v){if(!(strpos($f1,$f2.$v.$f2)>0)){$f1.=$v.$f2;}}
	}
	unset($fArr);
	$f1=str_qsw($f1,$f2);
	return $f1;
}
function str_qsw($f,$f2=',')
{	//去掉$f两边的$f2 一般用于去除,等
	if(!$f){return $f;}
	$str='{YXL.CN_STR_QSW}'.$f2.$f.$f2.'{YXL.CN_STR_QSW}';
	while(strpos($str,'STR_QSW}'.$f2)>0)
	{
		$str=str_replace('{YXL.CN_STR_QSW}'.$f2,'{YXL.CN_STR_QSW}',$str);
	}
	while(strpos($str,$f2.'{YXL.CN_STR_QSW}')>0)
	{
		$str=str_replace($f2.'{YXL.CN_STR_QSW}','{YXL.CN_STR_QSW}',$str);
	}
	$str=str_replace('{YXL.CN_STR_QSW}','',$str);
	//if(strval(substr($str,0,1))==strval($f2)){$str=substr($str,1);}
	//if(strval(substr($str,-1,1))==strval($f2)){$str=substr($str,0,-1);}
	return $str;
}
function getPage($Page='')
{//获取page值
	if(!$Page){$Page=$_REQUEST['page'];}
	if(!$Page){$Page=$_REQUEST['Page'];}
	if(!$Page||!is_numeric($Page)){return 1;}
	if($Page!=''){$Page=ceil(abs($Page));}
	return $Page;
}
function getLimit($rsCount,$Page,$PageSize=10)
{//根据参数 获取Limit字符串
	$rsBegin=$PageSize*($Page-1);
	if($rsBegin>$rsCount){$rsBegin=floor($rsCount/$PageSize)*$PageSize;}
	return " limit $rsBegin,$PageSize ";
}
function getPageNav($rsCount,$Page,$PageSize,$PageNav)
{
	if($rsCount<=0){return '';}
	if(!$PageNav){return '';}
	$PageLi=$PageNav;
	$PageCount=ceil($rsCount/$PageSize);
	//x($PageCount);
	if($Page>$PageCount){$Page=$PageCount;}
	if($Page<=0){$Page=1;}
	$str='';
	if($Page>6)
	{
		$PageLiTmp=$PageLi;
		$PageLiTmp=str_ireplace('{Page}',1,$PageLiTmp);
		$PageLiTmp=str_ireplace('{PageName}','首页',$PageLiTmp);
		$str.=$PageLiTmp;
	}
	if($Page>1)
	{
		$PageLiTmp=$PageLi;
		$PageLiTmp=str_ireplace('{Page}',$Page-1,$PageLiTmp);
		$PageLiTmp=str_ireplace('{PageName}','上页',$PageLiTmp);
		$str.=$PageLiTmp;
	}
	for ($p=max($Page-5,1);$p<=min($Page+5,$PageCount);$p++)
	{
		$PageLiTmp=$PageLi;
		$PageLiTmp=str_ireplace('{Page}',$p,$PageLiTmp);
		$PageLiTmp=str_ireplace('{PageName}',$p,$PageLiTmp);
		if($Page==$p){$PageLiTmp='<b>'.$PageLiTmp.'</b>';}
		$str.=$PageLiTmp;
	}
	if($Page<$PageCount)
	{
		$PageLiTmp=$PageLi;
		$PageLiTmp=str_ireplace('{Page}',$Page+1,$PageLiTmp);
		$PageLiTmp=str_ireplace('{PageName}','下页',$PageLiTmp);
		$str.=$PageLiTmp;
	}
	if($Page<($PageCount-5))
	{
		$PageLiTmp=$PageLi;
		$PageLiTmp=str_ireplace('{Page}',$PageCount,$PageLiTmp);
		$PageLiTmp=str_ireplace('{PageName}','尾页',$PageLiTmp);
		$str.=$PageLiTmp;
	}
	return $str;
}
/**********************中文字符处理相关函数开始**********************/
function strLength($f)
{//字符的真实个数 abc袁晓力 是6个字符 (php自带函数strLen对 袁晓力 结果是9 因为uft-8一个汉字三个字节 对gb2312的 一个汉字strlen返回的2字节一个汉字两个字节)
	if(!$f){return 0;}
	$str=$f;
    preg_match_all("/./u", $str, $ar);
    return count($ar[0]);
}
function byteNum($f)
{//字符的真实字节 占位 一个汉字算2个 只适用于utf-8 如果是gb2312的文字 先转换为utf-8使用
	if(!$f){return 0;}
	$str=$f;
	$len=strlen($str);
	$i=0; $j=0;
	while($i<$len)   
	{
		if(preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/",$str[$i]))   
		{
			$i+=2;
		}
		else
		{
			$i+=1;			
		}
		$j++;
	}
	return $j;
}
function SelStr($f,$start=0) 
{//跟substr函数一样使用
/*
  utf-8编码下截取中文字符串,参数可以参照substr函数
  @param $str 要进行截取的字符串
  @param $start 要进行截取的开始位置，负数为反向截取
  @param $ln 要进行截取的长度
*/
	if(!$f){return $f;}
	$str=$f;

    $null = "";
    preg_match_all("/./u", $str, $ar);
    if(func_num_args() >= 3) {
        $ln = func_get_arg(2);
        return join($null, array_slice($ar[0],$start,$ln));
    }
    else {
        return join($null, array_slice($ar[0],$start));
    }
}
function ViewHTML($f)
{//显示HTML格式 样式 换行等 chr(13) 是一个回车 Chr(10) 是个换行符 chr(32) 是一个空格符
	if(!$f){return $f;}
	$str=$f;
	$str=str_ireplace(chr(10),'',$str);
	$str=str_ireplace(chr(13),'<br/>',$str);
	$str=str_ireplace(chr(32),'&nbsp;',$str);
	return $str;
}
/**********************中文字符处理相关函数结束**********************/

function is123($f)
{//是否是数字
	if(!$f){return 0;}
	$str=$f;
	if(preg_match('/^[0-9]+$/u',$str)) {return 1;} else {return 0;}
}
function isABC($f)
{//是否是字母
	if(!$f){return 0;}
	$str=$f;
	if(preg_match('/^[a-zA-Z]+$/u',$str)) {return 1;} else {return 0;}
}
function isABC123($f)
{//是否是字母数字
	if(!$f){return 0;}
	$str=$f;
	if(preg_match('/^[0-9a-zA-Z]+$/u',$str)) {return 1;} else {return 0;}
}
function isABC123_($f)
{//是否是字母数字下划线
	if(!$f){return 0;}
	$str=$f;
	if(preg_match('/^[0-9a-zA-Z_]+$/u',$str)) {return 1;} else {return 0;}
}
function isEmail($f)
{//是否是Email
	if(!$f){return 0;}
	$str=$f;
	if(preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/u',$str)) {return 1;} else {return 0;}
}
function isHanzi($f)
{//是否是汉字
	if(!$f){return 0;}
	$str=$f;
	if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$str)) {return 1;} else {return 0;}
}
function isChongfuStr($f)
{//重复字符
	if(!$f){return 0;}
	$str=$f;
	$str = strtolower($str);
	$flag = '';
	$strlength=strlen($str);
	for($ii=0;$ii<$strlength;$ii++)
	{
		if(substr($str,$ii,1) != $flag && $flag!='')
			return 0;
		else
			$flag = substr($str,$ii,1);
	}
	return 1;
}
function isLianxuStr($f)
{//连续字符
	if(!$f){return 0;}
	$str=$f;
	$str = strtolower($str);
	$flag = 0;
	$strlength=strlen($str);
	for($ii=0;$ii<$strlength;$ii++)
	{
		if(ord(substr($str,$ii,1)) != $flag+1 && $flag!=0)
			return 0;
		else
			$flag = ord(substr($str,$ii,1));
	}
	return 1;
}

function fNum($f)
{	//将全角数字转换为半角数字
	if(!$f){return $f;}
	$strTm=$f;
	$strTm=str_replace("０","0",$strTm);
	$strTm=str_replace("１","1",$strTm);
	$strTm=str_replace("２","2",$strTm);
	$strTm=str_replace("３","3",$strTm);
	$strTm=str_replace("４","4",$strTm);
	$strTm=str_replace("５","5",$strTm);
	$strTm=str_replace("６","6",$strTm);
	$strTm=str_replace("７","7",$strTm);
	$strTm=str_replace("８","8",$strTm);
	$strTm=str_replace("９","9",$strTm);
	$strTm=str_replace("－","-",$strTm);
	$strTm=str_replace("—","-",$strTm);
	$strTm=str_replace("　"," ",$strTm);
	return $strTm;
}

function Num2Str($f)
{	//将数字转为汉字
	if(!$f){return $f;}
	$strTm=$f;
	$strTm=str_replace("0","零",$strTm);
	$strTm=str_replace("1","一",$strTm);
	$strTm=str_replace("2","二",$strTm);
	$strTm=str_replace("3","三",$strTm);
	$strTm=str_replace("4","四",$strTm);
	$strTm=str_replace("5","五",$strTm);
	$strTm=str_replace("6","六",$strTm);
	$strTm=str_replace("7","七",$strTm);
	$strTm=str_replace("8","八",$strTm);
	$strTm=str_replace("9","九",$strTm);
	return $strTm;
}

function fTime($timeTmp='')
{	//输出标准的Unix时间戳
	if (!$timeTmp) {$timeTmp=time();}
	if (!is_numeric($timeTmp)) {$timeTmp=strtotime($timeTmp);}
	return $timeTmp;
}

function DT($timeTmp='')
{	//输出标准的日期时间
	$timeTmp=fTime($timeTmp);
	return Date('Y-m-d H:i:s',$timeTmp);
}

function DTNum($timeTmp='')
{	//输出标准的日期时间
	$timeTmp=fTime($timeTmp);
	return str_replace(' ','',str_replace(':','',str_replace('-','',Date('Y-m-d H:i:s',$timeTmp))));
}

function DateDiff($part, $begin='', $end='')
{//计算时间差 w-周
	if(!$begin)$begin=time();
	if(!$end)$end=time();
	if(!is_numeric($begin))$begin=strtotime($begin);
	if(!is_numeric($end))$end=strtotime($end);
	$diff = $end - $begin;
	$retval='';
	switch($part)
	{
		case "y": $retval = bcdiv($diff, (60 * 60 * 24 * 365)); break;
		case "m": $retval = bcdiv($diff, (60 * 60 * 24 * 30)); break;
		case "w": $retval = bcdiv($diff, (60 * 60 * 24 * 7)); break;
		case "d": $retval = bcdiv($diff, (60 * 60 * 24)); break;
		case "h": $retval = bcdiv($diff, (60 * 60)); break;
		case "n": $retval = bcdiv($diff, 60); break;
		case "s": $retval = $diff; break;
	}
	return $retval;
}

function DateAdd($part, $n='0', $date='')
{//加上或减去某时间后的时间
	if(!$date)$date=time();
	if(is_numeric($date))$date=date("Y-m-d H:i:s",$date);
	$val='';
	switch($part)
	{
	case "y": $val = date("Y-m-d H:i:s", strtotime($date ." +$n year")); break;
	case "m": $val = date("Y-m-d H:i:s", strtotime($date ." +$n month")); break;
	case "w": $val = date("Y-m-d H:i:s", strtotime($date ." +$n week")); break;
	case "d": $val = date("Y-m-d H:i:s", strtotime($date ." +$n day")); break;
	case "h": $val = date("Y-m-d H:i:s", strtotime($date ." +$n hour")); break;
	case "n": $val = date("Y-m-d H:i:s", strtotime($date ." +$n minute")); break;
	case "s": $val = date("Y-m-d H:i:s", strtotime($date ." +$n second")); break;
	}
	return $val;
}

function CDate($timeTmp='')
{	//输出中文日期
	$timeTmp=fTime($timeTmp);
	return Date('Y',$timeTmp).'年'.Date('m',$timeTmp).'月'.Date('d',$timeTmp).'日';
}

function CWeek($timeTmp='')
{	//输出中文星期
	$timeTmp=fTime($timeTmp);
	$WeekName=Date('w',$timeTmp);
	//x($timeTmp);
	switch ($WeekName)
	{
		case 0:return "日";break;
		case 1:return "一";break;
		case 2:return "二";break;
		case 3:return "三";break;
		case 4:return "四";break;
		case 5:return "五";break;
		case 6:return "六";break;
	}
}

function ExecTime()
{	//程序执行时间差
	global $View_ExecTime;
	if (strtoupper($View_ExecTime)!='Y') return '';
	global $Timer_Start;
	$TimerStart="$Timer_Start[sec].$Timer_Start[usec]";
	$Timer_End=gettimeofday();
	$TimerEnd="$Timer_End[sec].$Timer_End[usec]";
	$ExecTimeVal=number_format(($TimerEnd-$TimerStart)*1000,3,".","");
	if ($ExecTimeVal>1000)
	{
		$ExecTimeVal='<font color=#FF0000><b>'.$ExecTimeVal.'</b></font>';
	}
	elseif ($ExecTimeVal>500)
	{
		$ExecTimeVal='<b>'.$ExecTimeVal.'</b>';
	}
	global $SQLQueryCount;
	if ($SQLQueryCount=='') {$SQLQueryCount=0;}
	if ($SQLQueryCount>=10) {$SQLQueryCount='<font color=#FF0000><b>'.$SQLQueryCount.'</b></font>';}
	return '页面执行用时 '.$ExecTimeVal.' 毫秒, '.$SQLQueryCount.' 次数据请求.';
}

function myIP()
{	//获取真实的IP地址
   if($_SERVER['REMOTE_ADDR']){return $_SERVER['REMOTE_ADDR'];}
   if (getenv('HTTP_CLIENT_IP')) {
     $ip = getenv('HTTP_CLIENT_IP'); 
   }
   elseif (getenv('HTTP_X_FORWARDED_FOR')) { //获取客户端用代理服务器访问时的真实ip 地址
     $ip = getenv('HTTP_X_FORWARDED_FOR');
   }
   elseif (getenv('HTTP_X_FORWARDED')) { 
     $ip = getenv('HTTP_X_FORWARDED');
   }
   elseif (getenv('HTTP_FORWARDED_FOR')) {
     $ip = getenv('HTTP_FORWARDED_FOR'); 
   }
   elseif (getenv('HTTP_FORWARDED')) {
     $ip = getenv('HTTP_FORWARDED');
   }
   else { 
     $ip = $_SERVER['REMOTE_ADDR'];
   }
   return $ip;
}

function ComeURL()
{	//页面来源
	return	$_SERVER["HTTP_REFERER"];
}

function myDomain()
{	//当前网站实际域名 从网址中获取
	return	strtolower($_SERVER["SERVER_NAME"]);
}

function myHost()
{	//当前网站实际域名 从网址中获取
	$HTTPS='';	if(strtolower($_SERVER["HTTPS"])=='on'){$HTTPS='s';}
	return	'http'.$HTTPS.'://'.strtolower($_SERVER["SERVER_NAME"]);
}

function myURL()
{	//当前文件所在全路径及参数
	$HTTPS='';	if(strtolower($_SERVER["HTTPS"])=='on'){$HTTPS='s';}
	return	'http'.$HTTPS.'://'.strtolower($_SERVER["SERVER_NAME"]).$_SERVER["REQUEST_URI"];
}

function mySelf()
{	//当前文件所在路径及文件名
	return	strtolower($_SERVER["PHP_SELF"]);
}

function myPath()
{	//当前文件所在路径
	return	substr(mySelf(),0,strripos(mySelf(),"/")+1);
}

function myFile()
{	//当前文件的文件名
	return	str_ireplace(myPath(),"",mySelf());
}

function thisDomain()
{	//当前使用的域名 无www的
	return get_domain(myDomain());
}

function thisHostName()
{	//当前使用的域名前缀 即二级域名值
	return str_ireplace(".".thisDomain(),"",myDomain());
}

function get_domain($url)
{//用正则提取域名(无www)
	if(!$url){return '';}
	$pattern = "/[\w-]+\.(com|net|org|gov|cc|biz|info|cn|cc)(\.(cn|hk))*/";
	preg_match($pattern, $url, $matches);
	if(count($matches) > 0)
	{
		return $matches[0];
	}
	else
	{
		$rs = parse_url($url);
		$main_url = $rs["host"];

		if(!strcmp(long2ip(sprintf("%u",ip2long($main_url))),$main_url))
		{
			return $main_url;
		}
		else
		{
			$arr = explode(".",$main_url);
			$count=count($arr);
			$endArr = array("com","net","org");	//com.cn  net.cn 等情况
				if (in_array($arr[$count-2],$endArr))
				{
					$domain = $arr[$count-3].".".$arr[$count-2].".".$arr[$count-1];
				}
				else
				{
					$domain =  $arr[$count-2].".".$arr[$count-1];
				}
			return $domain;
		}
	}
}

/*编解码系列开始*/
function base64_encode_en($f)
{	//对base64编码后 加干扰码
	if(!$f){return '';}
	$strTmp=$f;
	$disCodeLen=rand(2, 10);		//干扰码的长度
	$disCode=RndStr($disCodeLen);	//干扰码
	return substr($strTmp,0,1).Chr(99+$disCodeLen).$disCode.substr($strTmp,1);
}

function base64_decode_de($f)
{	//对加了干扰码的base64编码 去除干扰码
	if(!$f){return '';}
	$strTmp=$f;
	$disCodeLen=ord(substr($strTmp,1,1))-99;	//干扰码的长度
	return substr($strTmp,0,1).substr($strTmp,2+$disCodeLen);
	//return substr($strTmp,0,1).substr($strTmp,2,0-$disCodeLen);
}
function enCodeID($src) 
{
	$bit7  = ($src>>7)&0x01;
	$bit15 = ($src>>15)&0x01;
	$bit23 = ($src>>23)&0x01;
	$bit30 = ($src>>30)&0x01;
	$bit31 = ($src>>31)&0x01;

	$bitbyte = ($bit7<<6)|($bit15<<5)|($bit23<<4)|($bit31<<3)|($bit23<<2)|($bit7<<1)|$bit15;
	$bit30 = $bit30 ^ 1;

	$byte0 = $src&0xFF;
	$byte1 = ($src>>8)&0xFF;
	$byte2 = ($src>>16)&0xFF;
	$byte3 = ($src>>24)&0xFF;
	
	$byte3 = $byte3^$byte0;
	$byte2 = $byte2^$byte0;
	$byte1 = $byte1^$byte0;
	$byte0 = $byte0^$bitbyte;
	$dst = ($byte3<<24)|($byte2<<16)|($byte1<<8)|$byte0;
	$dst = ($dst & 0x3F7F7F7F)|($bit7<<7)|($bit15<<15)|($bit23<<23)|($bit30<<30)|($bit31<<31);
	return $dst;
}	
function deCodeID($dst)
{
	$bit7  = ($dst>>7)&0x01;
	$bit15 = ($dst>>15)&0x01;
	$bit23 = ($dst>>23)&0x01;
	$bit30 = ($dst>>30)&0x01;
	$bit31 = ($dst>>31)&0x01;
	
	$bitbyte = ($bit7<<6)|($bit15<<5)|($bit23<<4)|($bit31<<3)|($bit23<<2)|($bit7<<1)|$bit15;
	
	$byte0 = $dst&0xFF;
	$byte1 = ($dst>>8)&0xFF;
	$byte2 = ($dst>>16)&0xFF;
	$byte3 = ($dst>>24)&0xFF;
	$byte0 = $byte0^$bitbyte;
	$byte1 = $byte1^$byte0;
	$byte2 = $byte2^$byte0;
	$byte3 = $byte3^$byte0;
	$dstsrc = ($byte3<<24)|($byte2<<16)|($byte1<<8)|$byte0;
	$bit30 = $bit30 ^ 1;
	$dstsrc = ($dstsrc & 0x3F7F7F7F)|($bit7<<7)|($bit15<<15)|($bit23<<23)|($bit30<<30)|($bit31<<31);
	return $dstsrc;
}
/*编解码系列结束*/

function delDir($dir)
{//递归法删除文件夹
	if(!$dir){return true;}
	if(is_dir($dir))
	{
		if (@rmdir($dir)==false)
		{
			if ($dp = opendir($dir))
			{
				while (($file=readdir($dp)) != false)
				{
					if( $file!='.' && $file!='..')
					{
						$file=$dir.'\\'.$file;					
						if (is_dir($file)) 
						{
							delDir($file);
						}
						else
						{
							@unlink($file);
						}
					}
				}
				closedir($dp);
				if(@rmdir($dir)) {return true;} else {return false;}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return true;
		}
	}
	return true;
}
function creDir($dir)
{//创建文件夹 支持多级创建
	if(!$dir){return false;}
	if(is_dir($dir))
	{
		return true;
	}
	else
	{
		if(@mkdir($dir,'0777',true)){return true;}else{return false;}		
	}
}
function rFile($f)
{//读取文件
	if(!$f){return '';}
	return @file_get_contents($f);
}
function wFile($f,$cnt="")
{//写入文件
	if(!$f){return -1;}
	return @file_put_contents($f,trim($cnt));
}

function YXL_HC($mc,$key,$Val='R',$exp=0)
{//添加内存映射 需要YXL.cn_memcached-client.php支持
//YXL_HC($mc,$key)		取值
//YXL_HC($mc,$key,'')	删除缓存
//YXL_HC($mc,$key,$val)	添加缓存
//$exp 有效期 单位秒
//往 memcached 中写入对象，$key 是对象的唯一标识符，$val 是写入的对象数据，$exp 为过期时间，单位为秒，默认为不限时间；
	if(!$key){return '';}
	if(!$Val)
	{
		@$mc->delete($key);return true;
	}
	elseif($Val=='R')
	{
		return @$mc->get($key);
	}
	elseif($Val)
	{
		if($mc->get($key))
		{//存在 直接替换 更新
			@$mc->replace($key,$Val,$exp);
		}
		else
		{//不存在 就添加
			@$mc->add($key,$Val,$exp);
		}
		return true;
	}
}
function YXL_HC_Redis($key,$Val='R',$exp=0)
{//添加内存映射 需要YXL.cn_Redis.php支持
//YXL_HC_Redis($key)		取值
//YXL_HC_Redis($key,'')	删除缓存
//YXL_HC_Redis($key,$val)	添加缓存
//$exp 有效期 单位秒，当为日期时，则将 exp日期-当前时间，然后得出秒
//往 Redis 中写入对象，$key 是对象的唯一标识符，$val 是写入的对象数据，$exp 为过期时间，单位为秒，默认为不限时间；
	if(!$key){return '';}
	// wLog("YXL_HC_Redis2($key,$Val,$exp)");
	global $redis;
	if(!$Val)
	{
		@$redis->del($key);return true;
	}
	elseif($Val=='R')
	{
		$val = $redis->get($key);
		return unserialize($val);
	}
	elseif($Val)
	{
		if(!$exp){$exp='';}
		$exp2='';
		if(!is_numeric($exp) && strtotime($exp)>0){$exp2=strtotime($exp)-time();}	//2017-01-01 or 2017-01-01 12:00:00//罡子
		if(!is_numeric($exp) && !$exp2){$exp=3600*24*rand(90,120);}		//3600*24*90	90 day=7776000
		$exp=abs($exp);
		if($exp>15552000){$exp=15552000;}						//最长180天
		if($exp2){$exp=$exp2;}									//日期有效期优先//罡子
		@$redis->del($key);
		$Val=serialize($Val);
		if($exp<0){$exp=1;}
		if($exp==999)											//当exp=999时，则永久保存
		{
			@$redis->set($key,$Val);
		}
		else
		{
			@$redis->setex($key,$exp,$Val);
		}
		return true;
	}
}
function YXL_HC_File($HCPath,$key,$Val='R',$exp=0)
{//添加文件缓存映射 需要$HCPath具有读写的权限支持
//YXL_HC_File($HCPath,$key)		取值
//YXL_HC_File($HCPath,$key,'')	删除缓存
//YXL_HC_File($HCPath,$key,$val)	添加缓存
//$exp 有效期 单位秒
//往 YXL_HC_File 中写入对象，$key 是对象的唯一标识符，$val 是写入的对象数据，$exp 为过期时间，单位为秒，默认为不限时间
	if(!$key){return '';}
	if($HCPath)
	{
		$HCPath=str_replace('\\','/',$HCPath).'/';
		while(strpos($HCPath,'//')>0){$HCPath=str_replace('//','/',$HCPath);}
	}
	if(!is_dir($HCPath)){return '文件缓存目录['.$HCPath.']不存在!';}
	$HC_KEY=md5($key).'.txt';
	//$HCPath=$HCPath.substr($HC_KEY,0,2).'/';
	$HCPath=$HCPath.'/Data/'.substr($HC_KEY,0,1).'/'.substr($HC_KEY,1,2).'/';
	$HCPath=str_replace('//','/',$HCPath);
	//creDir($HCPath);
	$HC_KEY=$HCPath.$HC_KEY;
	if(!$Val)
	{
		@unlink($HC_KEY);return true;
	}
	elseif($Val=='R')
	{
		$filemtime=@filemtime($HC_KEY);
		if(!$filemtime){return '';}
		if(round($filemtime)<time()){@unlink($HC_KEY);return '';}
		return @rFile($HC_KEY);
	}
	elseif($Val)
	{
		creDir($HCPath);
		@unlink($HC_KEY);
		@clearstatcache();
		wFile($HC_KEY,$Val);
		if($exp>0){$exp=time()+round($exp);}else{$exp=strtotime("+10 year");}
		@touch($HC_KEY,$exp);
		@clearstatcache();
		return true;
	}
}


function ReadHTML($urlt,$post_string='')
{//主要用来http抓取		ver:2017/2/8 by YXL.CN
	if(!$urlt){return '';}
	//$htmlCnt = file_get_contents($urlt);

	global $curl_timeout;
	global $curl_timeout_connect;
	global $curl_referer;
	if(!is_numeric($curl_timeout)){$curl_timeout = 5;}
	if(!is_numeric($curl_timeout_connect)){$curl_timeout_connect = 3;}
	if(!$curl_referer){$curl_referer = myURL();}

	if(!$post_string)
	{//对GET过来的URL 里面暗含?post?标记 表示 自动拆解以POST方式提交		ver:2017/2/8 by YXL.CN
		if(stripos($urlt,'?post?')>0){$urltA=explode(substr($urlt,stripos($urlt,'?post?'),6),$urlt);	$urlt=$urltA[0];$post_string=$urltA[1];unset($urltA);}
	}

	$chtmp = curl_init();	
	curl_setopt($chtmp, CURLOPT_URL, $urlt);
	if($post_string){if(is_array($post_string)){$post_string=http_build_query($post_string);}curl_setopt($chtmp,CURLOPT_POST,1);curl_setopt($chtmp,CURLOPT_POSTFIELDS,$post_string);}
	curl_setopt($chtmp, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'].' runbey/RBBrowser com.runbey.readhtml/2015');	// 模拟用户使用的浏览器	curl_setopt($chtmp, CURLOPT_AUTOREFERER, 1);		// 自动设置Referer   
	curl_setopt($chtmp, CURLOPT_AUTOREFERER, 1);		// 自动设置Referer   
	curl_setopt($chtmp, CURLOPT_REFERER,$curl_referer);		// 伪造来源referer
	curl_setopt($chtmp, CURLOPT_RETURNTRANSFER, 1);		// 获取的信息以文件流的形式返回
	curl_setopt($chtmp, CURLOPT_CONNECTTIMEOUT, $curl_timeout_connect);	//在发起连接前等待的时间，如果设置为0，则无限等待。
	curl_setopt($chtmp, CURLOPT_TIMEOUT, $curl_timeout);	//设置cURL允许执行的最长秒数。
	curl_setopt($chtmp, CURLOPT_FOLLOWLOCATION,1);		//是否抓取跳转后的页面	301 302
	if(stripos('yxl'.$urlt,'https://')>0){curl_setopt($chtmp, CURLOPT_SSL_VERIFYPEER, FALSE);curl_setopt($chtmp, CURLOPT_SSL_VERIFYHOST,  FALSE);}
	$htmlCnt = curl_exec($chtmp);
	//echo $urlt."----";
	//echo curl_getinfo($chtmp, CURLINFO_HTTP_CODE);
	//echo "<br/>";
	global $http_code;
	$http_code=curl_getinfo($chtmp, CURLINFO_HTTP_CODE);
	if ($http_code != '200') {curl_close($chtmp);return '';}
	curl_close($chtmp);
	while (ord(substr($htmlCnt,0,1))==239&&ord(substr($htmlCnt,1,1))==187&&ord(substr($htmlCnt,2,1))==191)
	{//去除UTF8的三字节头
		{$htmlCnt=substr($htmlCnt,3);}
	}
	return $htmlCnt;
}


function getHTMLData($htmlCnt,$mark1,$mark2)
{//分析$htmlCnt 数据 获取$mark1与$mark2之间的数据
	if (!$htmlCnt) {return '';}
	$htmlCntTmp=$htmlCnt;
	if ($mark1=="" && $mark2=="")
	{return $htmlCntTmp;}
	elseif ($mark1!="" && $mark2=="")
	{$htmlCntTmp = explode($mark1,$htmlCntTmp);return $htmlCntTmp[1];}
	elseif ($mark1=="" && $mark2!="")
	{$htmlCntTmp = explode($mark2,$htmlCntTmp);return $htmlCntTmp[0];}
	elseif ($mark1!="" && $mark2!="")
	{
		$htmlCntTmp = explode($mark1,$htmlCntTmp);
		$htmlCntTmp = $htmlCntTmp[1];
		$htmlCntTmp = explode($mark2,$htmlCntTmp);
		$htmlCntTmp = $htmlCntTmp[0];
		return $htmlCntTmp;
	}
}


function wLog($content = '',$logSubDir='')
{
	global $DEBUG;
	if(!$DEBUG)
	{
		if(!defined('DEBUG')){define('DEBUG',false);}
		if(! DEBUG) return "";
	}

	$logRootDir = $_SERVER["DOCUMENT_ROOT"] .'/logs/';
	$logSubDir.=myFile();

	$fileName = $logRootDir ."". date('Ymd',time()) ."_". $logSubDir .".log.txt";
	$fileCont = ($content == "")? "\n" : date('m-d H:i:s',time()) ." ### $content\n";
	@file_put_contents($fileName,$fileCont,FILE_APPEND);
}

/*************************
 * 推送任务至消息队列
 * 该接口适合于异步接口调用，不适合同步接口调用
 * @param array msmqData 传入消息数据体，格式如下所述
 * @return string result 返回标准JSON串({"result":"success",...})，为空时由表示调用失败
 *************************
 * msmqData 格式如下(编码:"UTF-8"):
	array(
		array(	
			"type"  => "api",									//任务类型，目前只支持api请求
			"url"   => "http://api.mnks.cn/v1/sms/send.php",	//请求接口的URL，可带参数，URL中的参数以GET方式传递
																	参数中可使用变量，如：{DT}{DATE}{TIME}{YEAR}{MONTH}{DAY}{HOUR}{MINUTE}{WEEK},变量名使用全大写或全小写，不支持大小写组合
			"param" => "",  									//请求接口的参数，可为空，该参数以POST方式传递
																	格式如下：
																	1.templete=newstudent&mobile=13057506160&jxname=元贝驾校"
																	2.array("templete"=>"newstudent","mobile"=>"13057506160","jxname"=>"元贝驾校"),
																	参数中可使用变量，如：{DT}{DATE}{TIME}{YEAR}{MONTH}{DAY}{HOUR}{MINUTE}{WEEK},变量名使用全大写或全小写，不支持大小写组合
			"time"  => "",          							//定时执行时间，可为空，
																	空 : 轮循(10秒/次)
																	0 ：立即执行
																	20 : 定义20秒后执行
																	timestamp 或 yyyy-MM-dd HH:ii:ss : 定时执行
			"backurl" => "",									//业务平台定义回调接口
																	可为空，空则不回调，不为空(且要http开头)时，不管任务成功或失败(失败会重试10次后确认失败)，都会进行回调
																	回调时，URL后增加参数status=success/failed
			"notify" => "",										//执行失败时通知号码，可为空，多个以半角,分隔
																	失败界定：1.调用url超时，2.请求返回非200/301/301，3：返回标准JSON时，result=faild
																	参数内容：1.邮箱，发邮件通知，2.手机，发短信通知，3.SQH，发推送通知(向com.runbey.runbeypushtask【元贝任务APP】发送)																
		)
	)
*/
/****************************
 * 简单用例
 * 方式一(参数请求):putMSMQ (url,postData)
					putMSMQ (url,postData,time)
					putMSMQ (url,postData,time,callbackUrl)
					putMSMQ (url,postData,time,callbackUrl,mail)
					putMSMQ (url,postData,mail)
					putMSMQ (url,postData,callbackUrl,mail)
 * 方式二(单对象请求)：putMSMQ (array("url"=>url,"param"=>postData))
 * 方式一(多对象请求)：putMSMQ (array(array("url"=>url,"param"=>postData),array("url"=>url,"param"=>postData)))
 */
function putMSMQ($msmqData=array(), $param='', $time=0, $backurl='', $notify=''){
	if(!is_array($msmqData)){
		if(strtolower(substr($msmqData,0,7))=='http://'||strtolower(substr($msmqData,0,8))=='https://')	{
			if(strtolower(substr($backurl,0,7))!='http://' && strtolower(substr($backurl,0,8))!='https://'){if(!$notify){$notify = $backurl;}$backurl='';}
			if((!is_numeric($time)&&!is_numeric(strtotime($time)))||$time > 8000 ){if(!$backurl){$backurl=$time;}$time=0;}
			$msmqData=array(array('type' => 'api','url' => $msmqData,'param' => $param,'time'=> $time,'backurl' => $backurl,'notify' => $notify));
		}else {$msmqData=array();}
	}
	if(!$msmqData || !is_array($msmqData)){return "";}
	if(isset($msmqData['url'])){
		$msmqDataT[] = $msmqData;
		$msmqData = $msmqDataT;
		unset($msmqDataT);
	}
	foreach($msmqData as $k => $m){
		if(!isset($m['type'])){$m['type'] = 'api';}
		if($m['url'] && !is_array($m['url'])){$m['url'] = urlencode($m['url']);}
		if($m['param'] && !is_array($m['param'])){$m['param'] = urlencode($m['param']);}
		if($m['backurl'] && !is_array($m['backurl'])){$m['backurl'] = urlencode($m['backurl']);}
		$msmqData[$k] = $m;
	}
	$postURL = "http://msmq.mnks.cn/api_job.php";
	$postData = "p=".json_encode($msmqData);
	$result = ReadHTML($postURL,$postData);
	return $result;
}

/*************************
 * 发送APP推送
 * 该接口适合于异步接口调用，不适合同步接口调用
 * @param array pushData 传入消息数据体，格式如下所述
 * @return string result 返回标准JSON串({"result":"success",...})，为空时由表示调用失败
 *************************
 * pushData 一维或二维数组，格式如下(编码:"UTF-8"):
	array(
		"pkg"  		=> "",  	包名(必填):com.runbey.ybjk|com.runbey.ybjkcoach
		"tag" 		=> "",		标签(必填):SQH_1188618,SQH_9999或AGE_2025或PCA_32 (为空时是所有用户推，在这里限制PHP内只能针对指定tag的用户推)
		"content" 	=> "",		内容(必填):推送内容
		"title"  	=> "",		标题:推送标题(Android用)
		"weburl" 	=> "",		地址:httpUrl|schemeUrl
		"pf"   		=> "",		平台:all|ios|android
		"extras"    => array(),	扩展:扩展数据,默认为空数组,如array("key"=>"value")
		"mode"		=> "0",		消息模式:0-弹窗+消息(默认),1-消息
		"iosenv"	=> "1",		环境:1-生产(默认),0-测试  (只针对iOS)
	)
 */
/****************************
 * 简单用例
 * 方式一(参数请求):pushAPP (pkg,tag,content)
					pushAPP (pkg,tag,content,title)
					pushAPP (pkg,tag,content,weburl)
					pushAPP (pkg,tag,content,title,weburl)
					pushAPP (pkg,tag,content,title,weburl,pf)
					pushAPP (pkg,tag,content,title,weburl,pf,extras=Array(k=>v),mode,iosenv)
 * 方式二(单对象请求):pushAPP (array("pkg"=>pkg,"tag"=>tag,"title"=>title))
 * 方式一(多对象请求):pushAPP (array(array(""pkg"=>pkg,"tag"=>tag,"title"=>title),array("pkg"=>pkg,"tag"=>tag,"title"=>title)))
 */
function pushAPP($pushData = array(), $tag='', $content='', $title='', $weburl = '', $pf='', $extras=array(), $mode='0' ,$iosenv='1'){
	if($pushData && !is_array($pushData)){
		if($tag && $title){
			if($title && in_array(strtolower($title),array('all','ios','android'))){if(!$pf){$pf=$title;}$title='';}
			if($weburl && in_array(strtolower($weburl),array('all','ios','android'))){if(!$pf){$pf=$weburl;}$weburl='';}
			if($title && strpos($title,'://') > 0){if(!$weburl){$weburl=$title;}$title='';}
			if(!$pf){$pf='all';}
			if(!is_array($extras)){$extras = array();}
			if($weburl){$extras["weburl"] = $weburl;}
			$pushData=array('pkg' => $pushData,'tag' => $tag,'title'=> $title,'content' => $content,'extras'=>$extras,'pf' => strtolower($pf),'iosenv'=>$iosenv, 'mode' => $mode);
		}else{$pushData=array();}
	}
	if(!$pushData || !is_array($pushData)){return "";}
	if(isset($pushData['pkg'])){
		$pushDataT[] = $pushData;
		$pushData = $pushDataT;
		unset($pushDataT);
	}
	//从MSMQ发送
	$msmqData = array();
	foreach($pushData as $p){
		if(!isset($p['content'])&&isset($p['title'])){
			$p['content'] = $p['title'];
		}
		if(!isset($p['pkg']) || !isset($p['tag']) || !isset($p['content'])){continue;}
		$extras = array();
		if(isset($p['extras']) && is_array($p['extras'])){$extras = $p['extras'];}
		if(isset($p['weburl']) && $p['weburl']){
			$extras["weburl"] = $p['weburl'];
			unset($p["weburl"]);
		}
		if(is_array($extras)){$p['extras'] = json_encode($extras);unset($extras);$p['extras'] = str_ireplace('\/','/',$p['extras']);$p['extras']=urlencode($p['extras']);}
		global $pushHost;
		if(!$pushHost){$pushHost='http://api.mnks.cn';}	$pushHost=Trim($pushHost,'/');
		$url = $pushHost.'/v1/push/sendtag.php';
		$data = '';
		foreach($p as $k => $v){
			$k = ($k=='pf' ? 'platform' : $k);
			if(substr($v,0,1)=='{'||substr($v,0,1)=='['){}else{$v = str_ireplace('"','\"',$v);}
			$data .= '&'. $k .'='. $v;
		}
		$data = trim($data,'&');
		$msmqData[] = array(
			'url' 	=> $url,
			'param' => $data,
			'time' 	=> 0,
		);		
	}
	if($msmqData){
		$result = putMSMQ($msmqData);
		return $result;
	}
	return '{"result":"faild","":""}';
}


function outFrame($iframeSRC,$iframeWidth='100%',$iframeHeight='600px')
{//支持跨域自适应高度 是基于同一个域名 跨的是二级域名 并且相关页面都要加上<script>document.domain = 'domian.com';</script>
	if (!$iframeSRC){return '';}
	echo '<script type="text/javascript">'.chr(13).chr(10);
	echo 'function stateChangeIE(_frame) {'.chr(13).chr(10);
	echo 'if (_frame.readyState == "complete") { ResizeFrame(_frame);}'.chr(13).chr(10);
	echo '}'.chr(13).chr(10);
	echo 'function ResizeFrame(iframe) {'.chr(13).chr(10);
	echo 'try {'.chr(13).chr(10);
	echo 'var height = Math.max(iframe.contentWindow.document.body.scrollHeight, iframe.contentWindow.document.documentElement.scrollHeight);'.chr(13).chr(10);
	echo 'iframe.style.height = Math.max(height, 460)+"px";'.chr(13).chr(10);
	echo '}'.chr(13).chr(10);
	echo 'catch (e) {}'.chr(13).chr(10);
	echo '}'.chr(13).chr(10);
	echo '</script>'.chr(13).chr(10);
	echo '<iframe id="ContainerFrame" name="ContainerFrame" onreadystatechange="stateChangeIE(this)" onload="ResizeFrame(this)" src="'.$iframeSRC.'" scrolling="no" style="background-color:#fff;margin-left:0px; padding-left:0px;height:'.$iframeHeight.';width:'.$iframeWidth.';" frameborder="0" marginwidth="0" marginheight="0" ></iframe>'.chr(13).chr(10);
	echo ''.chr(13).chr(10);
	echo ''.chr(13).chr(10);
	echo ''.chr(13).chr(10);
}

$DriveTypes='A1,A2,A3,B1,B2,C1,C2,C3,C4,C5,D,E,F,M,N,P';
function isDriveType($dt='')
{//是否有效车型
	if(!$dt){return 0;}
	if(DriveTypeArray($dt)){return 1;}else{return 0;}
}
function DriveTypeArray($keyT='')
{//准驾车型
	$dtArray=array();
	$dtArray['A1']='大型客车';
	$dtArray['A2']='牵引车';
	$dtArray['A3']='城市公交车';
	$dtArray['B1']='中型客车';
	$dtArray['B2']='大型货车';
	$dtArray['C1']='小型汽车';
	$dtArray['C2']='小型自动档汽车';
	$dtArray['C3']='低速载货汽车';
	$dtArray['C4']='三轮汽车';
	$dtArray['C5']='残疾人专用小型自动挡载客汽车';
	$dtArray['D']='普通三轮摩托车';
	$dtArray['E']='普通二轮摩托车';
	$dtArray['F']='轻便摩托车';
	$dtArray['M']='轮式自行机械车';
	$dtArray['N']='无轨电车';
	$dtArray['P']='有轨电车';
	if($keyT==''){return $dtArray;}else{return $dtArray[strtoupper($keyT)];}
}
function DriveType2Slect($dt='C1')
{
	$dts='';
	$DTAT=DriveTypeArray();	
	foreach ($DTAT as $k => $v)
	{
		$dts=$dts.'<option value="'.$k.'">'.$k.'&nbsp;&nbsp;--&nbsp;&nbsp;'.$v.'</option>';
	}
	$dts=str_ireplace('value="'.$dt.'"','value="'.$dt.'" selected',$dts);
	return $dts;
}
function Checked($str1Tmp,$str2Tmp,$Model='instr',$str3Tmp='checked')
{
//判断是否符合输出str3Tmp
//str1Tmp--基础校验字符（串）	被搜索的字符串
//str2Tmp--待校验字符（串）		要查找的字符
//Model--校验类型（串）  默认匹配(=) 值：=(匹配)、instr(包含)
//str3Tmp--输出字符（串）默认Checked
	if($str1Tmp===''||$str2Tmp===''){return '';};
	$str1Tmp=strval($str1Tmp);
	$str2Tmp=strval($str2Tmp);
	if($Model!='=')
	{
		if(stripos('{yxl}'.$str1Tmp,$str2Tmp)>=5){return $str3Tmp;}
	}
	else
	{
		if(strtoupper($str1Tmp)===strtoupper($str2Tmp)){return $str3Tmp;}
	}
	return '';
}
function PCAsplit($f)
{//自动分解$f 根据$f告知 P值 C值 A值
	$PCA=$f;
	$PCA_P='';$PCA_C='';$PCA_A='';
	if($PCA==''){return array("","","");}

	$PCA_P=substr($PCA,0,2);
	$PCALen=strlen($PCA);
	if($PCALen==4)
	{
		$PCA_C=$PCA;
	}
	elseif($PCALen==6)
	{
		if($PCA_P=='11'||$PCA_P=='12'||$PCA_P=='31'||$PCA_P=='50'||$PCA_P=='81'||$PCA_P=='82'||substr($PCA,2,2)=='90')
		{//直辖市 省管县
			$PCA_C=$PCA;
		}
		else
		{
			$PCA_C=substr($PCA,0,4);
			$PCA_A=$PCA;
		}
	}
	elseif($PCALen==9)
	{
		$PCA_C=substr($PCA,0,6);
		$PCA_A=$PCA;
	}
	return array($PCA_P,$PCA_C,$PCA_A);
}
function PCAgetName($f,$basehost='http://base.cysq.com')
{//根据$pcav获取其Name		320324 -> 睢宁县
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/Name/JSON');
	$fv=json_decode($fv, true);
	return $fv[0]['Name'];
}
function PCAgetNameAll($f,$basehost='http://base.cysq.com')
{//根据$pcav获取其Name全称	320324 -> 江苏-徐州市-睢宁县
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/PYN/Name');
	return $fv;
}
function PCAgetPinYin($f,$basehost='http://base.cysq.com')
{//根据$pcav获取其PinYin	320324 -> suining
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/Name/JSON');
	$fv=json_decode($fv, true);
	return $fv[0]['PinYin'];
}
function PCAgetPinYinAll($f,$basehost='http://base.cysq.com')
{//根据$pcav获取其PinYin全	320324 -> jiangsu-xuzhou-suining
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/PYN/PinYin');
	return $fv;
}
function PCAgetPY($f,$basehost='http://base.cysq.com')
{//根据$pcav获取其PY	320324 -> sn
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/Name/JSON');
	$fv=json_decode($fv, true);
	return $fv[0]['PY'];
}
function PCAgetPYAll($f,$basehost='http://base.cysq.com')
{//根据$pcav获取其PY全	320324 -> js-xz-sn
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/PYN/PY');
	return $fv;
}
function PYgetAll($f,$basehost='http://base.cysq.com')
{//根据$PY获取其全部信息	js -> 江苏|jiangsu|js|32	js-xz -> 江苏-徐州市|jiangsu-xuzhou|js-xz|3203		js-xz-sn -> 江苏-徐州市-睢宁县|jiangsu-xuzhou-suining|js-xz-sn|320324
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/');
	return $fv;
}
function PYgetPCA($f,$basehost='http://base.cysq.com')
{//根据$PY获取其PCA			js -> 32		js-xz -> 3203		js-xz-sn -> 320324
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/Code');
	return $fv;
}
function PYgetName($f,$basehost='http://base.cysq.com')
{//根据$PY获取其PCAName		js -> 江苏		js-xz -> 江苏-徐州市		js-xz-sn -> 江苏-徐州市-睢宁县
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/Name');
	return $fv;
}
function PYgetPinYin($f,$basehost='http://base.cysq.com')
{//根据$PY获取其PinYin		js -> jiangsu		js-xz -> jiangsu-xuzhou		js-xz-sn -> jiangsu-xuzhou-suining
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/PinYin');
	return $fv;
}
function PinYingetPY($f,$basehost='http://base.cysq.com')
{//根据$PinYin获取其PY		jiangsu -> js	jiangsu-xuzhou -> js-xz			jiangsu-xuzhou-suining -> js-xz-sn
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/PY');
	return $fv;
}
function PinYingetPCA($f,$basehost='http://base.cysq.com')
{//根据$PinYin获取其PCA		jiangsu -> 32	jiangsu-xuzhou -> 3203			jiangsu-xuzhou-suining -> 320324
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/Code');
	return $fv;
}
function PCAgetURL($f,$basehost='http://base.cysq.com')
{//根据$PCA获取其URL		32 -> js	3203 -> js-xuzhou	320324 -> js-xuzhou-suining
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/URL');
	return $fv;
}
function URLgetPCA($f,$basehost='http://base.cysq.com')
{//根据$URL获取其PCA		32 <- js	3203 <- js-xuzhou	320324 <- js-xuzhou-suining
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/'.$f.'/URL');
	return $fv;
}
function IPgetPCA($f,$basehost='http://base.cysq.com')
{//根据$IP获取其PCA		180.109.148.220 -> 3201-江苏省南京市
	if(!$f){return '';}
	$fv=ReadHTML($basehost.'/Diqu/IP/'.$f.'/All');
	return $fv;
}
function IDTypeNames($m='')
{
	$IDTypeNames[1] = "身份证";
	$IDTypeNames[2] = "驾驶证";
	$IDTypeNames[3] = "警官证";
	$IDTypeNames[4] = "军官证";
	$IDTypeNames[5] = "护照";
	if($m=='')
	{
		return $IDTypeNames;
	}
	elseif($m=='select')
	{	$str='';
		for($tn=1;$tn<=5;$tn++){$str=$str.'<option value="'.$tn.'">'.$IDTypeNames[$tn].'</option>';}return $str;$str='';
	}
	elseif(substr($m,0,6)=='radio_')
	{	$str='';
		$radioN=str_replace('radio_','',$m);
		for($tn=1;$tn<=5;$tn++){$str=$str.'<input type="radio" name="'.$radioN.'" value="'.$tn.'" id="IDTy'.$tn.'"><label for="IDTy'.$tn.'">'.$IDTypeNames[$tn].'</label>';}return $str;$str='';
	}
}

function mailHost($f="")
{//通过email地址 返回登录host
	if(!$f){return '';}
	$str=$f;	
	if(!isEmail($str)){return '';}
	$str=explode('@',$str);
	$str=$str[1];
	switch ($str)
	{
		case "yahoo.com.cn":case "yahoo.cn":
		return "http://mail.cn.yahoo.com/"; 
		break;
		case "gmail.com":
		return "http://mail.google.com/"; 
		break;
		case "139.com":
		return "http://mail.10086.cn/"; 
		break;
		case "hotmail.com":
		return "https://login.live.com/"; 
		break;		
	}
	return 'http://mail.'.$str;
}

function R404($f='')
{
	@ob_clean();
	if(!$f){$f='/';}
	echo('<html><head><meta http-equiv="refresh" content="0; url='.$f.'"><script>location="'.$f.'";</script></head><body></body></html>');
	header("HTTP/1.1 404 Not Found");
	die();
}
function R301($f='')
{
	@ob_clean();
	if(!$f){$f='/';}
	header("HTTP/1.1 301 Moved Permanently");	//发出301头部   
	header("Location: $f");						//跳转到你希望的地址格式  
	die();
}
function R302($f='')
{
	@ob_clean();
	if(!$f){$f='/';}
	header("Location: $f");
	die();
}
function R500()
{
	@ob_clean();
	header("HTTP/1.0 500 Internal Server Error");
	die();
}
function RJS($f='')
{//对于不能使用header的 使用JS来转向
	@ob_clean();
	if(!$f){$f='/';}
	echo '<meta http-equiv="refresh" content="0; url='.$f.'">';
	echo '<script>';
	echo 'location="'.$f.'";';
	echo '</script>';
	echo '</div>';
	echo '</body>';
	echo '</html>';
	die();
}
function IFT($a,$b,$c='')
{	
	if($a){if($c){return $c;}else{return $a;}}else{return $b;}
}




function getSuccess($data=array(),$msg="成功",$extend=array()){
	$ajaxData=array();
	$ajaxData['result'] = 'success';
	$ajaxData['code'] = 200;	
	$ajaxData['resume'] = $msg;
	$ajaxData['datetime'] = date('Y-m-d H:i:s',time());
	$ajaxData['data'] = $data;
	if(is_array($extend)){
		foreach($extend as $key => $ext){
			if(is_array($ext)){
				$ajaxData = array_merge($ajaxData,$ext);
			}
			else{
				$ajaxData[$key] = $ext;
			}
		}
	}
	return $ajaxData;
}
function putSuccess($data=array(),$msg="成功",$extend=array()){
	if(is_array($data) && isset($data['result'])){
		$returnStr = json_encode($data);
	}
	else{
		$returnStr = json_encode(getSuccess($data,$msg,$extend));
	}
	//wLog($returnStr ."\n");
	return $returnStr;
}
function getError($code='0',$msg='',$extend=array()){
	@mysqli_close($conn);
	if(!$code){$code=0;}
	if(!$msg){global $ErrMsg;	if(is_array($ErrMsg)){$msg=$ErrMsg[$code];}}
	if(!$msg){$msg='失败';}
	$ajaxData=array();
	$ajaxData['result'] = 'faild';
	$ajaxData['code'] = $code;
	$ajaxData['resume'] = $msg;
	$ajaxData['datetime'] = date('Y-m-d H:i:s',time());
	$ajaxData['data'] = null;	
	if(is_array($extend)){
		foreach($extend as $key => $ext){
			if(is_array($ext)){
				$ajaxData = array_merge($ajaxData,$ext);
			}
			else{
				$ajaxData[$key] = $ext;
			}
		}
	}
	return $ajaxData;
}
function putError($code='0',$msg='',$extend=array()){
	$returnStr = json_encode(getError($code,$msg,$extend));
	//wLog($returnStr);
	return $returnStr;
}

function outError($resume='失败',$result='faild')
{
    global $outArray;
    $outArray['result']=$result;
    $outArray['resume']=$resume;
    $outArray['data']='';
    //wLog(json_encode($outArray));
    echo json_encode($outArray);	unset($outArray);
}

function outOK($rowArray='')
{
    global $outArray;
    global $JSONCALL;
    $outArray['data']=$rowArray;
    //print_r($rowArray);
    $outArray['ecode'] = 200;
    $outJSON=json_encode($outArray);
    if($JSONCALL){$outJSON=$JSONCALL.'('.$outJSON.')';}
    //wLog($outJSON);
    echo $outJSON;	unset($outArray);unset($rowArray);unset($outJSON);
}

?>