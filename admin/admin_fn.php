<?php
include("../common/db_fns.php");
session_start();
function login($username, $password) {
  $password= sha1($password);
  $conn = db_connect();
  //转义输出
  $username= mysqli_real_escape_string($conn,$username);
  $password= mysqli_real_escape_string($conn,$password);
  $result = $conn->query("select * from user where username='{$username}' and password ='{$password}'");
  if (!$result) {
    echo "出错了，请刷新页面重试...";
  }
  if ($result->num_rows>0) {
    $_SESSION['user']= "{$username}";
    echo "登陆成功！";
  } else {
    echo "账号或密码错误，请重试...";
  }
}

function check_valid_user() {
  $result= false;
  if (isset($_SESSION['user'])){
  $result= true;
  }
  return $result;
}

function change_password($username, $old_password, $new_password) {
  login($username, $old_password);
  $conn = db_connect();
  $result = $conn->query("update user
                          set passwd = sha1('".$new_password."')
                          where username = '".$username."'");
  if (!$result) {
    throw new Exception('不能改密码');
  } else {
    return true;
  }
}
?>