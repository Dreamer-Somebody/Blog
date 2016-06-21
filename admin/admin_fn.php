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
                          set password = sha1('".$new_password."')
                          where username = '".$username."'");
  if (!$result) {
    echo('不能改密码');
    die();
  } else {
    return true;
  }
}

function get_index_page(){
  $string= "<h1>信息摘要</h1>";
  $string.= "<table>";
  $string.= "<tr><th colspan='2'>信息统计</th><tr>";
  $string.= "<tr><td>当前用户：</td><td>{$_SESSION['user']}</td></tr>";
  $string.= "<tr><td>文章总数：</td><td>".get_table_data('article')."</td></tr>";
  $string.= "<tr><td>评论总数：</td><td>".get_table_data('comment')."</td></tr>";
  $string.= "<tr><td>分类总数：</td><td>".get_table_data('article','count(distinct class)')."</td></tr>";
  $string.= "<tr><td>浏览总数：</td><td>".get_table_data('article','sum(click)')."</td></tr>"; 
  $string.= "</table>";
  echo "$string";
}

function get_table_data($table='',$col='count(*)'){
  $conn= db_connect();
  if(!$conn){
    echo "连接数据库失败！";
    die();  
  }
  $query= "select ".$col." data from ".$table;  
  $result= $conn->query($query);
  $row= $result->fetch_array();
  return $row['data'];
}

?>