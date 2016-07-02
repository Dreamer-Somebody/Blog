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
	@$id= isset($_REQUEST['id'])?$_REQUEST['id']:'none';
	@$real= isset($_REQUEST['real'])?$_REQUEST['real']:'false';
	del_article($id,$real);
}
if($action=='recover'){
	@$id=$_REQUEST['id'];
	recover_article($id);
}
if($action=='insert_article'){
	@$title= $_REQUEST['title'];
	@$file_name= $_REQUEST['file_name'].".html";
	@$class= $_REQUEST['class'];
	@$tags= $_REQUEST['tags'];
	@$content= $_REQUEST['content'];
	@$new= $_REQUEST['new'];
	@$id= $_REQUEST['id'];
	insert_article($title,$file_name,$class,$tags,$content,$new,$id);
}
?>