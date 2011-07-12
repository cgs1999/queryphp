<?php
/*
*控制访问表
1000000表示禁止管理员访问，比如有些功能是要让管理员回避的，如工会讨论组 自己私人日志,当然老板命令或数据库管理员可以查看。
                   不过数据库管理员也是普通员工，如果查看私人东西可能冒着比较大的道德风险和管理风险，数据库管理员也有责任不看私人的东西。
                   比如QQ管理员也不能查看QQ之间传递的密码。邮箱也不能被查看。可以设置过滤 直接删除 
0100000表示只能是管理员访问 
0010000表示是可以管理的组 就是许可访问组 
0001000表示是许身份管理   就是许可访问身份 
0000100表示组管理组员     组长可以管理组员 
0000010表示自己管理自己   只能编辑自己的内容 
0000001表示登录才能看     防止匿名访问 
0000000表示无限制，任可人可以访问
*/
class curdACL extends acl {
	public $routername="curd";
	public $aclid='';
	public $roledisable=array();
	public $aclgroup=array("create"=>"4,45,8"); //create需要的组才能创建
	public $aclrole=array("all"=>"6","create"=>"7,95,78"); //create需要的角色才能创建,该组需要ID为6的角色才能访问
	public $acl=array("all"=>0,
		              "index"=>0,    //表列0表示任何人可以访问
		              "delete"=>1,   //删除只登录后删除,当然呆以设置为2或4
		              "update"=>1,   //更新提交只能登录后才能更新，在这里做也防止非法、post，edit是不能访问显示编辑内容页
		              "createForm"=>0, //也不能新提交数据库
	                  "edit"=>0,       //登录才显示编辑框
		              "show"=>0,       //不用登录也能显示
		              "create"=>0);    //创新表单需要登录操作 可以设置某个组才能创建

} 
?>