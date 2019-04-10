<?php
header("Content-Type:text/html; charset=UTF-8");

date_default_timezone_set('PRC');	//设置为PRC时区 就是中国

define ('Site_Name','我的收藏夹');							//网站名称
define ('Site_Addr','http://fav.mnks.cn/');			//网站地址

//define ('db_ini_File','SQL_ini\\Config.ini');	//数据库配置的ini文件路径
define ('db_ini_File',__DIR__ .'/SQL_ini/Config.ini',TRUE);	//数据库配置的ini文件路径
//define ('db_ini_Team_name','Team_yxl_jsyks_fav');	//数据库配置的选项卡名字
define ('db_ini_Team_name','Team_yxl_jsyks_fav',TRUE);	//数据库配置的选项卡名字
define ('db_host','');	//数据库服务器名称
define ('db_user','');	//连接数据库用户名
define ('db_pass','');	//连接数据库密码
define ('db_name','');	//数据库的名字
define ('db_pcon','');	//是否使用长连接 Y-使用 !=Y-不使用


//define ('redis_ini_File','SQL_ini\\redis.ini');	//Redis 配置的ini文件路径
define ('redis_ini_File',__DIR__ .'/SQL_ini/redis.ini',TRUE);
define ('redis_ini_Team_name','Team_DEFAULT');	//Redis 配置的默认选项卡名字


//初始化一些设置
$Timer_Start=gettimeofday();	//当前脚本执行时的时间戳 用于计算运行时间

$CSS_Path			=	'/css/';	//框架的CSS路径
$JS_Path			=	'/js/';		//框架的JS路径
$JS_common			=	'';		//是否加载common.js
$JS_cookies			=	'';		//是否加载cookies.js
$JS_jquery			=	'load';		//是否加载jquery.js
$JS_swfobject		=	'';		//是否加载swfobject.js
$body_Parameters	=	'';			//Body加载的参数

$head_title			=	''.Site_Name;
$head_keywords		=	'';
$head_description	=	'';
$head_Tag			=	'';				//head头部其他标签信息



$View_ExecTime		=	"Y";			//是否显示执行时间 信息 Y-显示 N-不显示 大写


$MR_TKID=2000;	//默认全国通用题库ID
$MR_DT='C1';	//默认C1车型

?>