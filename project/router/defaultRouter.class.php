<?php
 
class defaultRouter extends controller{
  function index()
  {
    echo "hello world!";
	$array1 = array("a" => "green", "b" => "brown","www"=>array("aa"=>"8888"), "c" => "blue", "red");
$array2 = array("a" => "green", "yellow", "red");
$result_array = array_intersect($array1, $array2);
echo "<pre>";
print_r($result_array);
print_r(MY()->array_multi2single($array1));
echo "</pre>";
		  echo "<pre>";
		  /*$this->assign("ssss","aa");
	$this->hhh="88";
	$this->pager=C("pager");
	$this->pager->setPager(500,10,'page');
	print_r($this->pager->getWholeBar(url_for("default/index/page/:page")));

	echo("page:".$this->pager->offset().":");
	$booktype=M("booktype");
    $booktype->limit($this->pager->offset(),10);
	$booktype->getAll();
	print_r($booktype->getRecord());
	//echo C("zh2pinyin")->T("开源硬件能否像    开源软件一样流行");
	//print_r($booktype->getAll());
	C("waterimg")->createWaterPng("水印开始");
	$img=C("img");
	echo "</pre>";
	//echo($img->safeName("开源硬件能否像    开源软件一样流行@#$%@#asdf=-_afasdf.jpg"));
	
	echo $booktype->fetch('FETCH_OBJ')->up()->bookid;
	print_r($booktype->data);
	echo $booktype->classname;
	*/
	//   $booktype=M("booktype");
	   //$booktype->selectSupply("address,title");
       //$booktype->selectbooktype("bookid,classname")->selectsupply("address,title")->leftjoin("supply")->joinon("supply.bookid=booktype.bookid")->where('bookid',404)->fetch();
     //  print_r($booktype->getObjRecord());

	//J("saybye",array("bbee"=>6666,"ccdd"=>888));
	return false;
  }
  function saybye()
  {
	$a=func_get_args();
	print_r($a);
    echo "bye<pre>";
	$supply=M("supply");
	//$supply->get(3,4);
	//print_r($supply->record);
	//echo $supply->title;
	//$supply->up();
	//print_r($supply->getData());
	//$supply->up();
	//print_r($supply->getData());

	//$supply->getDataBaseName();
    
	//echo "===".$supply->Books->Supply->title;
	//$supply=M("supply");
	//$supply->get(3,4);
	//$supply->up();//edit 3
	//M("booktype")->classname="星际解霸2";

   // $supply->copyRecord()->save(M("booktype"));

	//$supply->Books=array("classname"=>"星际解霸5");
	//print_r($supply->save());

	//$supply->where($supply->PRI.">12")->delete();
	//$supply->save();
	//$books=M("booktype");
	//echo "aaa";
	//$books->get(246)->up(); //取一个值
	//$books->classname="开发游戏新行"; //更新字段
	//$supply->update($books);  //关联保存
	//$books->where($books->PRI.">3")->delete();
	//M("booktype")->where($books->PRI.">12")->delete();
	//$supply->Books->setclassname("星际争霸9")->save();
   // print_r($supply->data);
	//$supply->address="北京海淀区";
	//$supply->update("address");
	//$supply->update(array("mobile"=>126666,"address"=>"清上河"));
	//$supply->update("mobile,address",array(13800138000,"上地站"));
  /*
  * update为指定字段更新，不像save什么都更新
  * $supply->update('fields,fields');
  * $supply->update(array('fields'=>"aaabbb","fields2"=>8888));
  * $supply->update(array('fields'=>"aaabbb","fields2"=>8888),true); //true表示更新到$supply->data
  * $supply->update($Books); //关联更新 $Books是M对像,表示更新到$supply->data
  * $books 为类对象，record将会改为对像的。
  * $supply->update($books,true); 
  * $supply->update('fields,fields',array("aa","bbb"));
  */

	//$supply->Books(array('classname'=>"星星争霸78"))->save();
	//print_r($supply->Books->record);
    //print_r($supply->Books->record);
    //$supply->Books=array("classname"=>"星际解霸21");
	//$supply->Books=array("classname"=>"星际解霸22");
	//$supply->Books=array("0"=>array("classname"=>"星际解霸88"),2=>array("classname"=>"星际解霸98"));
	//print_r($supply->data);
	//$supply->copyRecord();
	//print_R($supply);
	//$supply->copyRecord()->save();
	//print_r($books->record);
	//print_r($supply);
	//print_r(M("booktype")->record);
	//$sub="useridANDlanguageORlangLIKEcnpri";
	//$sub="useridAND";
	//$sub="asdfdgdasdLIKE";
	echo "</pre>";
  }
  function hasOne()
  {
    echo "<pre>";
    $books=M("booktype");
	print_r($books->find(946,911)->getRecord());
	print_r($books->Supply->getRecord());
	print_r($books->getRecord());
	echo "</pre>";
	Return false;//表示不使用模板视图
  }
  /*
  *ajax测试显示页面
  */
  function ajax() {
       echo(strrpos($_SERVER["REQUEST_URI"],$_SERVER["PATH_INFO"]));
  }
  /*
  *ajax显示页面
  */
  function ajaxtest() {
  	ECHO json_encode($_SERVER);
    Return ajax;
  }
}
?>