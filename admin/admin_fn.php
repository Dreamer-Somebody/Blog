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

function get_page($id){
  switch ($id) {
    case 'index':
      get_index_page();
      break;
    case 'article':
      get_article_page();
      break;
    case 'comment':
      get_comment_page();
      break;
    case 'file':
      get_file_page();
      break;
    case 'edit':
      get_edit_page();
      break;
    default:
      echo "获取页面出错!";
      break;
  }
}

function get_index_page(){
  $string= "<h1>信息摘要</h1>";
  $string.= "<table id='index_table'>";
  $string.= "<tr><th colspan='2'>信息统计</th><tr>";
  $string.= "<tr><td>当前用户：</td><td>{$_SESSION['user']}</td></tr>";
  $string.= "<tr><td>文章总数：</td><td>".get_table_data('article')."</td></tr>";
  $string.= "<tr><td>评论总数：</td><td>".get_table_data('comment')."</td></tr>";
  $string.= "<tr><td>分类总数：</td><td>".get_table_data('article','count(distinct class)')."</td></tr>";
  $string.= "<tr><td>浏览总数：</td><td>".get_table_data('article','sum(click)')."</td></tr>"; 
  $string.= "</table>";
  echo "$string";
}

function get_article_page(){
  $string= "<h1>文章管理</h1>";
  $string.= "<a href='###' id='edit'>+ 编写新文章</a>";
  $string.= "<table><tr><th>序号</th><th>文章编号</th><th>标题</th><th>发表时间</th><th colspan=2>操作</th></tr>";
  $conn= db_connect();
  $result= $conn->query("select article_id,title,pub_time from article order by pub_time desc");
  $rows= $result->num_rows;
  for($i=1;$i<=$rows;$i++){
    $row = $result->fetch_array();
    $string.= "<tr><td>{$i}</td>";
    $string.= "<td>".$row['article_id']."</td>";
    $string.= "<td>".$row['title']."</td>";
    $string.= "<td>".$row['pub_time']."</td>";
    $string.= "<td><a>编辑</a></td>";
    $string.= "<td>删除</td>";
    $string.= "</tr>";
  }
  $string.= "</table>";
  echo "$string";
}

function get_edit_page(){
  $string= "<h1>文章管理</h1>";
  $string.= "标题：<input type='text' value='未命名文章'></input></br>";
  $string.= "文件名：<input type='text' value='未命名文章'></input>".".html</br>";
  $string.= "标签名：<input type= 'text'></input>";
  $string.= "<form action='control.php 'method='post'>
  <script id='container' name='content' type='text/plain'></script>
  <input type='submit' />
</form>
  <!-- 配置文件 -->
    <script type='text/javascript' src='/blog/components/ueditor/utf8-php/ueditor.config.js'></script>
    <!-- 编辑器源码文件 -->
    <script type='text/javascript 'src='/blog/components/ueditor/utf8-php/ueditor.all.js'></script>
    <!-- 实例化编辑器 -->
    <script type='text/javascript'>
        var ue = UE.getEditor('container');
    </script>";
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