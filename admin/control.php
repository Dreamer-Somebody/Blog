<?php
include("admin_fn.php");
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
?>