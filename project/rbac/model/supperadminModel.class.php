<?php 
class supperadminModel extends supperadminBase{ 
  var $mapper=array();
 var $maps;
 var $maparray=array();
 /***
 *��������Ա��¼
 *
 ***/
  function login($a) {
 	$user=M("supperadmin");
	$user->whereadminnameANDadminpwd($a['adminname'],md5($a['adminpwd']))->limit(1)->fetch();
	if(!$user->isEmpty())  
    {  
      MY()->setLogin();
	  Return true;
	}else{
	  Return false;
	}
 }
} 
?>