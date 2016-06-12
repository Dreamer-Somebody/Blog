<?php
include("admin_fn.php");
if(isset($_POST['user'])&&isset($_POST['pwd'])){
	$result= login($_POST['user'],sha1($_POST['pwd']));
	return $result;
}
?>