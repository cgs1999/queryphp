<?php
//$iscacheconfig ��������˾��ǻ��棬������Ͳ�ʹ�û���
//����ʹ�����ݿ���
/*
*  queryphp ORM 
*
*  ��ʹ��ʱ��ֱ�Ӻ����ļ��Ϳ����ˣ��������԰�queryorm���뵽��Ŀ���С�
*  ʹ�÷������ĵ������������jquery doctrine������ʽ Ŭ��ģ��doctrine ORM��Ϊ
*  $books=M("booktype");ȡ��ģ��
*/
$config["frameworkpath"]=dirname(__FILE__)."/";
include($config["frameworkpath"]."config/inc.ini.php");
if($projectenv=='product'&&file_exists($config["frameworkpath"]."cache/orm.cache.php"))
{
  require_once $config["frameworkpath"]."cache/orm.cache.php";
}else{
 if($projectenv=='product')
 {
	$corecontent=substr(php_strip_whitespace($config["frameworkpath"]."core/model.php"),0,-2);
	$corecontent.=substr(php_strip_whitespace($config["frameworkpath"]."core/function.php"),5,-2);
	file_put_contents($config["frameworkpath"]."cache/orm.cache.php",$corecontent);
	unset($corecontent);
	require_once $config["frameworkpath"]."cache/orm.cache.php";
 }else{
	include($config["frameworkpath"]."core/model.php");
	include($config["frameworkpath"]."core/function.php");
 }
}
?>