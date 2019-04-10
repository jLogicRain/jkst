<?php
error_reporting(E_ALL^E_NOTICE);
define ('Root_Path',$_SERVER["DOCUMENT_ROOT"].'/');
//站点的根目录(绝对路径)
////define ('YXL_Framework_Path','/');
//设定YXL_Framework在站点中的相对路径 赋值给YXL_Framework_Path作为一个常量 使用
////define ('YXL_Framework_Path_Map',$_SERVER["DOCUMENT_ROOT"].YXL_Framework_Path);
//设定YXL_Framework的物理路径 赋值给YXL_Framework_Path_Map作为一个常量 使用

session_start();	//开启Session

require Root_Path.'/YXL.cn_Config.php';
require Root_Path.'/YXL.cn_Conn.php';
require Root_Path.'/YXL.cn_Fun.php';
require Root_Path.'/YXL.cn_Redis.php';
require Root_Path.'/YXL.cn_App.php';
?>