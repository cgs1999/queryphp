<?php

/***
*ǰ̨URL����
*�������ĿURL��ַ������$_SERVER["SCRIPT_NAME"]ȥ��index.php�����ļ��ó�
***/
function PU($fix='/') {
 Return url_project($fix);	
}
function url_project($fix='/') {
  if(!isset($GLOBALS['__PROJECT__']))
  $GLOBALS['__PROJECT__']=substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/"));
  Return $GLOBALS['__PROJECT__'].$fix;	
}
/*
*�û����񷵻غ���
*/
function MY() {
	if(isset($GLOBALS['myUser']))
	{
	 return $GLOBALS['myUser'];
	}  	
	$GLOBALS['myUser']=new myUser();
	return $GLOBALS['myUser'];
}
/*
*��ת����
*/
function redirect($url,$msg,$second=0,$o=true) {
	header("Content-type: text/html; charset=utf-8");
    $str='<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta http-equiv="refresh" content="'.$second.';URL='.$url.'"></head><body>'.$msg.'</body></html>';
	if($o){
	  echo $str;exit;
	}
   Return $str;
}
/*
*Ȩ�޿��ƺ���
*/
function ACL($acl) {
   if(isset($GLOBALS[$acl."ACL"]))
   {
     return $GLOBALS[$acl."ACL"];
   }

		if(file_exists(P("webprojectpath")."router/acl/".$acl."ACL.class.php"))
		{
		   require_once P("webprojectpath")."router/acl/".$acl."ACL.class.php";
		}elseif(file_exists(P("modelpath")."router/acl/".$acl."ACL.class.php")){
		   require_once P("modelpath")."router/acl/".$acl."ACL.class.php";	
		}else{
		  Return false;
		}	

     $t=$acl."ACL";
	 $GLOBALS[$acl."DM"]=new $t();
	 return $GLOBALS[$acl."DM"];
}
/*
*������ʾ�����Զ���ʾĿ������
*Ĭ����I('systemlanuage');
*��Ҫת��������I('language');
*/
function L($str,$model='') {
	if(I('language')!=I('systemlanuage'))
	{
		 /*
		 *������ȡ�û��淭����û�оͷ���
		 */
		$url = 'http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=' .
			urlencode($str) . 
			'&langpair=' . I('systemlanuage') . '%7C' .
			I('language');			
		$json_data = file_get_contents($url);		
		$j = json_decode($json_data);		   
		if (isset($j->responseStatus) and $j->responseStatus == 200)
		{
			$t = $j->responseData->translatedText;
			 /*
			 *�����￪ʼ������õ��ı�����
			 */
			 
			 /*
			 *��������õ��ļ�����;
			 */
			 Return $t;
		}
	}
	Return $str;
}

/*
*��ͼ����
*/
function V() {
	Return new view();
}
/*
* �ļ�����ȫ����
*/
function filepath_safe($name) {
    $except = array('\\',' ', '..', ':', '*', '?', '"', '<', '>', '|');
    return str_replace($except,'', $name);
} 
/*
* �ļ�����ȫ����
*/
function filename_safe($name) {
    $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|');
    return str_replace($except, '', $name);
} 
/*
*�������
*/
function import($libpath)
{
   if(preg_match("|^@lib|i",$libpath))
   {
     $libpath=str_replace(".","/",substr($libpath,4)).".class.php";
	 if(checkrequire(P("webprojectpath")."lib".$libpath)) return true;
	 if(checkrequire(P("frameworkpath")."lib".$libpath)) return true;
   }
   if(preg_match("|^@plugin|i",$libpath))
   {
     $libpath=str_replace(".","/",substr($libpath,4)).".class.php";
     if(checkrequire(P("webprojectpath")."plugin".$libpath)) return true;
	 if(checkrequire(P("frameworkpath")."plugin".$libpath)) return true;
   }
}
/*
*
*/
function checkrequire($files)
{
  if(file_exists($files))
  {
    require_once($files);
    return true;
  }else{
    return false;
  }
}
/*
*���ݿ���������
*/
function pdoconnects($dsn,$connmodel)
{    
   try {
	    $GLOBALS['pdolinks'][$connmodel]=new PDO($dsn['dsn'],$dsn['username'],$dsn['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND =>'SET CHARACTER SET '.$dsn['CHARACTER']));
	    return $GLOBALS['pdolinks'][$connmodel];
	  } catch (PDOException $e) {
       print "connects Error!: " . $e->getMessage() . "<br/>";
    }
}
/*
*���ݿ����Ӵ���
*/
function getConnect($table,$model=null,$connper=0)
{
	 $tconn=array();
	 if(!isset($GLOBALS['pdolinks'])) $GLOBALS['pdolinks']=array();
	 if(is_array($GLOBALS['config']['pdoconn']))
	 {
        foreach($GLOBALS['config']['pdoconn'] as $k=>$v)
		{
		  if($k==$model||preg_match("|".$k."|i",$table)||preg_match("|".$k."|i",$model))
		  {
			 $prand=rand(0,count($v["master"])-1);
			 $connmodel=md5(json_encode($v["master"][$prand]));
			 $table_fix=$v["master"][$prand]['table_fix'];
			 if($GLOBALS['pdolinks'][$connmodel]!='')
			   $tconn['master']=$GLOBALS['pdolinks'][$connmodel];
			 else
			 {
			   $tconn['master']=pdoconnects($v["master"][$prand],$connmodel);
			 }
			$prand=rand(0,count($v["slaves"])-1);
			$connmodel=md5(json_encode($v["slaves"][$prand]));
			 if($GLOBALS['pdolinks'][$connmodel]!='')
			   $tconn['slaves']=$GLOBALS['pdolinks'][$connmodel];
			 else
			 {
			   $tconn['slaves']=pdoconnects($v["slaves"][$prand],$connmodel);
			 }
		    break;
		  }
		}
	 }
	 if(count($tconn)<2)
	 {
		$prand=rand(0,count($GLOBALS['config']['pdoconn']['default']["master"])-1);
	    $connmodel=md5(json_encode($GLOBALS['config']['pdoconn']['default']["master"][$prand]));
		$table_fix=$GLOBALS['config']['pdoconn']['default']["master"][$prand]['table_fix'];
		 if(isset($GLOBALS['pdolinks'][$connmodel]))
		 {
			$tconn['master']=$GLOBALS['pdolinks'][$connmodel];
		 }else{
			$tconn['master']=pdoconnects($GLOBALS['config']['pdoconn']['default']["master"][$prand],$connmodel);
		 }
        $prand=rand(0,count($GLOBALS['config']['pdoconn']['default']["slaves"])-1);
		$connmodel=md5(json_encode($GLOBALS['config']['pdoconn']['default']["slaves"][$prand]));
		 if(isset($GLOBALS['pdolinks'][$connmodel]))
		   $tconn['slaves']=$GLOBALS['pdolinks'][$connmodel];
		 else
		 {
		   $tconn['slaves']=pdoconnects($GLOBALS['config']['pdoconn']['default']["slaves"][$prand],$connmodel);
		 }
	 }
	 if($connper==1)
	 {
	   return array('master'=>$tconn['master'],'slaves'=>$tconn['slaves'],'table_fix'=>$table_fix);//����$model�������ӾͿ�����
	 }else if($connper==0)
	 {
	   return array('master'=>$tconn['master'],'slaves'=>$tconn['master'],'table_fix'=>$table_fix);//����$model�������ӾͿ�����
	 }
     
}
/*
*P()ȡ·������������lib plugin model view class
*/
function P($name)
{
 if(isset($GLOBALS['config'][$name])) return $GLOBALS['config'][$name];
 else return $GLOBALS['config']["frameworkpath"];
}
/*
*$config['key']ֵ��ȡ;
*/
function I($name)
{
  return isset($GLOBALS['config'][$name])?$GLOBALS['config'][$name]:null;
}
//J·����ת
function J()
{
   $arg = func_get_args();
   if(is_object($arg[0]))
	{
	  $controller=get_class($arg[0]);
	  $controller=substr($controller,0,-6);
	  C("router")->controller=$controller;	  
	  if($arg[1]=='') $arg[1]=$GLOBALS['config']['defaultindex'];      
	  C("router")->action=$arg[1];
	  array_shift($arg);
	  array_shift($arg);
	}else if(is_string($arg[0]))
	{
	  if($arg[1]=='')
	  {
	    C("router")->action=$arg[0];
		array_shift($arg);
	  }else if(is_array($arg[1])){
	    C("router")->action=$arg[0];
		array_shift($arg);
	  }else{
		C("router")->controller=$arg[0];
	    C("router")->action=$arg[1];
		array_shift($arg);
		array_shift($arg);
	  }
	}
	$router=R(C("router")->controller);
	if(method_exists($router,C("router")->action)) {
		$router->render(C("router")->action);
		Return $router->{C("router")->action}($arg);
		//call_user_func(array($router,C("router")->action),$arg);
	}
}
/*
*DM��datamodel����ģ����
*���������ݼ�����
*/
function DM($newc) {
   if(isset($GLOBALS[$newc."DM"]))
   {
     return $GLOBALS[$newc."DM"];
   }

		if(file_exists(P("webprojectpath")."model/dm/".$newc."DM.class.php"))
		{
		   require_once P("webprojectpath")."model/dm/".$newc."DM.class.php";
		}elseif(file_exists(P("modelpath")."model/dm/".$newc."DM.class.php")){
		   require_once P("modelpath")."model/dm/".$newc."DM.class.php";	
		}	
      $t=$newc."DM";
     $GLOBALS[$newc."DM"]=new $t();
	 return $GLOBALS[$newc."DM"];

}
//C������
function C($class=null)
{
   if($class==null) return null;
   if(isset($GLOBALS[$class."class"]))
   {
     return $GLOBALS[$class."class"];
   }else{
     $GLOBALS[$class."class"]=new $class();
	 return $GLOBALS[$class."class"];
   }
}
//RΪ����Router
function R($router=null)
{
   if($router==null) return null;
   $router=$router."Router";
   if(isset($GLOBALS[$router]))
   {
     return $GLOBALS[$router];
   }else{
	 try{
        $GLOBALS[$router]=new $router();
	  }catch (PDOException $e) 
      {
        echo $e->getMessage();
      }
	 return $GLOBALS[$router];
   }
}
//MΪ�������ģ�ͣ���һ�ξͿ�ʼ�����ļ���
function M($modelname=null,$tablename=null)
{
   if($modelname==null) return null;
   $table=$modelname."Model";
   if(isset($GLOBALS[$table]))
   {
     return $GLOBALS[$table];
   }else{
	 if(!empty($tablename))
	   {
		 initModelclass($modelname."Base",$tablename);
	   }
     $GLOBALS[$table]=new $table();

	 return $GLOBALS[$table];
   }
}
//��ʼ���������ļ����ļ���ʽ����mysql���ݿ��Զ��ѽṹд��ȥ
function initModelclass($modelname,$tablename=null)
{
   $fix=substr($modelname,-4);
   if($tablename==null) $tablename=$modelname;
   if($fix=="Base") $modelname=substr($modelname,0,-4);
   $string="DESCRIBE ".$tablename;	
   
   $DB=getConnect($tablename,$modelname);
	try{
	    $res=$DB['master']->query($string);
        $mate =$res->fetchAll(PDO::FETCH_ASSOC);  
	} catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
   if(is_array($mate))
	 {
	   $newmodelstr="<?php \n class ".$modelname."Base extends model{ \n ";
	   $fields=array();
       $types=array();
	   $newmodelstr.="  var \$tablename='".$tablename."';";
	   foreach($mate as $key=>$value)
	   {
		  $value['Field']=strtolower($value['Field']);
	      if($value['Key']=='PRI')
		   {
             $newmodelstr.="\n var \$PRI='".$value['Field']."';";
	         if($value['Extra']=='auto_increment')
			   {
			     $newmodelstr.="\n var \$autoid=true;";
			   }else{
			     $newmodelstr.="\n var \$autoid=false;";
			   }
		   }
		  $fields[$value['Field']]=$value['Default'];
		  $types[$value['Field']]=$value['Type'];
	   }
	   $newmodelstr.="\n var \$fields=".var_export($fields,true).";";
	   $newmodelstr.="\n var \$types=".var_export($types,true).";";
	   $newmodelstr.="\n}\n?>";
	 }
	 file_put_contents(P("modelpath")."model/".$modelname.'Base.class.php',$newmodelstr);
}
/*
* �Զ�������
*/
function __autoload($class_name) {
    $fix=substr($class_name,-5);
	if($fix=='Model'){ //ģ�����ꡣ���Ҫ��Ϊ��new����model��
		$newc=substr($class_name,0,-5);		
		if(file_exists(P("webprojectpath")."model/".$class_name.".class.php"))
		{
		   require_once P("webprojectpath")."model/".$class_name.".class.php";
		   return;
		}elseif(file_exists(P("modelpath")."model/".$class_name.".class.php")){
		   require_once P("modelpath")."model/".$class_name.".class.php";	
		   return;
		}else{		   
           $newmodelstr="<?php \nclass ".$newc."Model extends ".$newc."Base{ \n ";
		   $newmodelstr.=" var \$mapper=array();\n";
		   $newmodelstr.=" var \$maps;\n";
		   $newmodelstr.=" var \$maparray=array();\n";
           $newmodelstr.=" \n} \n?>";
		   file_put_contents(P("modelpath")."model/".$newc.'Model.class.php',$newmodelstr);
		   require_once P("modelpath")."model/".$newc.'Model.class.php';
		   return;//�ӷ����Զ��˳��������
		}
	}	
	$fix=substr($class_name,-4);
	if($fix=='Base'){ //ģ�ͻ�����
		$newc=substr($class_name,0,-4);
		if(!file_exists(P("modelpath")."model/".$newc.'Base.class.php')&&!file_exists(P("webprojectpath")."model/".$newc.'Base.class.php'))
		{		   
		   initModelclass($newc);	
		   clearstatcache();
		}		
		if(file_exists(P("webprojectpath")."model/".$newc.'Base.class.php'))
		{		   
		   require_once P("webprojectpath")."model/".$newc.'Base.class.php';		
		   return;
		}elseif(file_exists(P("modelpath")."model/".$newc.'Base.class.php')){
		   require_once P("modelpath")."model/".$newc.'Base.class.php';	
		   return;
		}
	}
	$fix=substr($class_name,-6);
	if($fix=='Router'){ //·���ļ�
		$newc=substr($class_name,0,-6);
		if(file_exists(P("webprojectpath")."router/".$newc."Router.class.php"))
		{
		   require_once P("webprojectpath")."router/".$newc."Router.class.php";
		   return;
		}elseif(file_exists(P("routerpath")."router/".$newc."Router.class.php")){
		   require_once P("routerpath")."router/".$newc."Router.class.php";	
		   return;
		}
	}
	if(isset($GLOBALS['config']['frameworklib'][$class_name])){
		require_once $GLOBALS['config']['frameworklib'][$class_name];
	    return;
	}
	if(file_exists(P("webprojectpath")."class/".$class_name.'.class.php'))
	{		   
	  require_once P("webprojectpath")."class/".$class_name.'.class.php';	
	  return;
	}
	if(file_exists(P("frameworkpath")."class/".$class_name.'.class.php'))
	{		   
	  require_once P("frameworkpath")."class/".$class_name.'.class.php';
	  return;
	}
	if(file_exists(P("frameworkpath")."lib/".$class_name."/".$class_name.'.class.php'))
	{		   
	  require_once P("frameworkpath")."lib/".$class_name."/".$class_name.'.class.php';
	  return;
	}
	if(file_exists(P("frameworkpath")."lib/".$class_name.'.class.php'))
	{		   
	  require_once P("frameworkpath")."lib/".$class_name.'.class.php';
	  return;
	}
	if(file_exists(P("webprojectpath")."lib/".$class_name.'.class.php'))
	{		   
	  require_once P("webprojectpath")."lib/".$class_name.'.class.php';
	  return;
	}
	if(file_exists(P("frameworkpath").$class_name.'.php'))
	{		   
	  require_once P("frameworkpath").$class_name.'.php';
	  return;
	}
	if(is_array($GLOBALS['config']['frameworklib']))
	{
	  foreach($GLOBALS['config']['frameworklib'] as $k=>$v)
	  {
		if(is_numeric($k))
		{
		   if(preg_match("@".$class_name."\.(class\.)?php$@i",$v)){ require_once $v; return; }
		}
	  }
	}
}
/*
* URL����������Ҫ���ǵ�ruleMaps���õ� ��Ϊ��ַ��ʾ�Ǳ���
* ��ȡruleMaps �ͳ�ʼ�����$controller
*/
function url_for()
{
  $arg_list = func_get_args();
	if(C("router")->isPathInfo()===true)
	{
	  $url=explode("?",$arg_list[0]);
	  $t=explode("/",$url[0]);
	    
	   $u="?router=".array_shift($t)."&action=".array_shift($t);
	   if(is_array($t))
	   {
	     $n=count($t);
		 for($i=0;$i<$n;$i++)
		 {
		   $u.="&".$t[$i]."=".$t[++$i];
		 }
	   }
	   if(!empty($url[1]))
	   {
		 $u.="&".$url[1];
	   }
	   $url='';
       $url=$_SERVER["SCRIPT_NAME"].$u;
	}else{
	  //����Ƕ�̬ʹ��$_SERVER["SCRIPT_NAME"]
	  if($_SERVER["PATH_INFO"]=='/'&&!isset($GLOBALS['config']['html']))
	  {
	   $url=$_SERVER["SCRIPT_NAME"]."/".$arg_list[0];	   
	  }else{
	   $url=substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],$_SERVER["PATH_INFO"]))."/".$arg_list[0];
      }
	  if(isset($GLOBALS['config']['html'])&&(substr($url,-strlen($GLOBALS['config']['html']))!=$GLOBALS['config']['html']))
	  {
		 if(isset($arg_list[1])&&$arg_list[1]===true)
		  {
		  }else{
		   $url.=$GLOBALS['config']['html'];
		   //�Ѿ�̬Ŀ¼����
		   if(isset($GLOBALS['config']['realhtml']))
		   {
			   $url=substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],$_SERVER["PATH_INFO"])).$GLOBALS['config']['realhtml']."/".$arg_list[0].$GLOBALS['config']['html'];
		   }
		  }
	   }
	}
  return $url;
}
?>