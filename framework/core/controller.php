<?php
class controller{
  var $render;
  var $htmlpath;
  function assign($name,$value=null)
  {
    C("view")->assign($name,$value);
  }
  function render($view)
  {
     $this->render=$view;
  }
  function fetch($view)
  {
    return C("view")->fetch($view);
  }
  function view($view='')
  {
    if($this->render) return  $this->render;
	return $view;
  }
  function __set($name,$value)
  {
	 C("view")->vvar[$name]=$value;
  }
  function __get($name)
  {
    return C("view")->get($name);
  }
  /*
  *�����Լ���������html�ļ���
  *��Ȼʹ��$_SERVER["REQUEST_URI"]����
  */
  function setHtmlPath($htmlpath) {
  	$this->htmlpath=$htmlpath;
  }
  /*
  *��ʾ���һ����������,��������html���滻��ʾ���������.
  *���Լ̳б�����,������������ٵ��ø���view_filter()Ҳ���Ǳ���������html
  *$GLOBALS['config']['htmlcache']['class'],$GLOBALS['config']['htmlcache']['method']
  *��inc.ini.php�����õ�ȻҲ������project����������ÿһ����Ŀ������ͬ�Ļ��淽����
  *���ֻ����дģ��html��̬ҳ����԰�
  *$GLOBALS['config']['htmlcache']['class']),$GLOBALS['config']['htmlcache']['method']����Ϊ��
  *$GLOBALS['config']['htmlcache']=''��ע�͵�
  */
  function view_filter($content) {
    if(C("router")->isPathInfo()||C("router")->isScript) Return $content;
  	if(isset($GLOBALS['config']['html'])&&(substr($_SERVER["REQUEST_URI"],-strlen($GLOBALS['config']['html']))==$GLOBALS['config']['html']))
    {	  
	  if(empty($this->htmlpath))
	  {
	   if(isset($GLOBALS['config']['realhtml']))
	   {   
		 $this->htmlpath=$GLOBALS['config']['realhtml'].substr($_SERVER["REQUEST_URI"],strlen(substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/"))));
	   }else{
		 $this->htmlpath=substr($_SERVER["REQUEST_URI"],strlen(substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/"))));
	   }
	  }
	  $this->htmlpath=filepath_safe($this->htmlpath);//������ȫĿ¼
	  //��projectĿ¼����
	  $htmlpath=$GLOBALS['config']["webprojectpath"].$this->htmlpath;
	  $htmlpath=str_replace("//","/",$htmlpath);
      //������û�����û����࣬û�оͲ����ɣ�ֻ��html��βģ��
	  if(class_exists($GLOBALS['config']['htmlcache']['class']))
	  {
	    call_user_func(array(C($GLOBALS['config']['htmlcache']['class']),$GLOBALS['config']['htmlcache']['method']),$content,$htmlpath);
	  }
	}
	Return $content;
  }
}
?>