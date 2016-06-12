<?php
include("admin_fn.php");
$_POST['user']='skyside';
$_POST['pwd']='545877356';
$result= login($_POST['user'],($_POST['pwd']));
echo("$result");
?>