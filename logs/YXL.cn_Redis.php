<?php

//http://www.cnblogs.com/weafer/archive/2011/09/21/2184059.html		PHP-redis中文文档

$PREDIS_DIR = substr(redis_ini_File,0,strrpos(redis_ini_File,'\\')).'/libs/predis/';

define ('PREDIS_DIR',$PREDIS_DIR);
    echo PREDIS_DIR.'src/Autoloader.php';die();
require_once PREDIS_DIR.'src/Autoloader.php';
Predis\Autoloader::register();

function getRedisini($Team,$iniFile="")
{//读取数据库配置文件
	if(!$iniFile){$iniFile=redis_ini_File;}			//echo 'SQL_ini:'.$iniFile;
	if(!file_exists($iniFile)){echo '<h2>发生了非常严重的错误！<p>Redis数据库配置文件不存在或暂时无法读取！</h2>';die();}
	$iniArray=parse_ini_file($iniFile,true);
	return $iniArray[$Team];
}

function getRedisClusterServers($serverStr='',$scheme='tcp'){
	/* 根据字符串生成服务器连接数组
	$servers = array(
		array(
		   'host' => '127.0.0.1',
		   'port' => 6379,
		   'database' => 15,
		   'alias' => 'master',
		   'timeout' => '0.100',
		),
	);
	*/
	$servers = array();
	foreach(explode(',',$serverStr) as $v){
		$hp = explode(':',$v);
		$server = array(
			'scheme' 	=> $scheme,
			'host' 		=> $hp[0],
			'port' 		=> $hp[1],
			'database' 	=> 0,
		);
		if(isset($hp[2])){
			$server['alias'] = $hp[2];
		}
		$servers[] = $server;
	}
	
	return $servers;
}
function openRedis($db_Team_name='')
{
	global $redis;
	if($redis){return $redis;}	

	if(!$db_Team_name){$db_Team_name=redis_ini_Team_name;}
	if(!$db_Team_name){echo '<h2>发生了严重的错误！<p>Redis数据库配置选项卡{'.$db_Team_name.'}不存在！</h2>';die();}

	if(strpos($db_Team_name,':')>0&&strpos($db_Team_name,',')===false)
	{
		// x('单连模式');
		$servers = getRedisClusterServers($db_Team_name);
		// print_r($servers);
		$options = array();
		$redis = new Predis\Client($servers[0],$options);
			
		//下面2行代码 判断连接是否可用
		// if($redis){$redisArray=get_object_vars($redis);if(count($redisArray)<=0){unset($redis);}}
		// if($redis){if(strtoupper($redis->ping())!='+PONG'){unset($redis);}}
	}
	else
	{
		$db_cfg=getRedisini($db_Team_name);
		if(!is_array($db_cfg)){echo '<h2>发生了严重的错误！<p>Redis数据库配置选项卡{'.$db_Team_name.'}不存在！！</h2>';die();}
		$db_host=$db_cfg['host'];
		$db_type=$db_cfg['type'];
		$db_time=$db_cfg['time'];
		// x($db_host);
		if(strpos($db_host,',')===false){
			// x('openRedis One<br/>');
			return openRedis($db_host);
		}
		else{
			$db_hosts = explode(',',$db_host);
			$servers = array();
			$servers = getRedisClusterServers($db_host);
			//集群模式
			if($db_type == 'cluster'){
				// x('集群模式');
				require_once PREDIS_DIR.'src/CustomRedisCluster.php';
				$servers = getRedisClusterServers($db_host);
				$options = array(
					'cluster' => 'redis'
				);
			}
			//主从复制模式
			elseif($db_type == 'replication'){
				// x('主从复制模式');
				foreach($servers as $k => $v){
					// $servers[$k]['alias'] = $k==0 ? 'master' : 'slave';
					$servers[$k]['timeout'] = $db_time;
				}
				$options = array('replication' => 'true');
				$options = array('replication' => 'sentinel', 'service' => 'mymaster');
			}
			$redis = new Predis\Client($servers, $options);
			// var_dump($db_hosts);
			unset($db_hosts);
		}
	}
	// x('连接成功');
	return $redis;
}
$redis=openRedis();
