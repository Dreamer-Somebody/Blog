<?php
include_once("admin_fn.php");
include_once("../common/db_fns.php");
@$action= $_GET['action'];
if($action=='login'){
if(!get_magic_quotes_gpc()){
	//过滤输入
	$_POST['user']= addslashes($_POST['user']);
	$_POST['pwd']= addslashes($_POST['pwd']);
}
if(isset($_POST['user'])&&$_POST['user']!=''&&isset($_POST['pwd'])&&$_POST['pwd']!=''){
	$result= login($_POST['user'],$_POST['pwd']);
	return $result;
}else{
	echo "账号和密码都不能为空！";
}
}
if($action=='get_page'){
	@$id= $_REQUEST['id'];
	 get_page($id);
}
if($action=='del'){
	@$id= $_REQUEST['id'];
	del_article($id);
}
?>