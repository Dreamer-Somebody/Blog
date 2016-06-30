<?php
include_once("../common/db_fns.php");
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
    case 'recycle':
      get_recycle_page();
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
  $string.= "<a href='###' id='recycle'>- 回收站</a>";
  $string.= "<table><tr><th>序号</th><th>文章编号</th><th>标题</th><th>发表时间</th><th colspan=3>操作</th></tr>";
  $conn= db_connect();
  $result= $conn->query("select article_id,title,pub_time from article order by pub_time desc");
  $rows= $result->num_rows;
  for($i=1;$i<=$rows;$i++){
    $row = $result->fetch_array();
    $string.= "<tr><td>{$i}</td>";
    $string.= "<td>".$row['article_id']."</td>";
    $string.= "<td>".$row['title']."</td>";
    $string.= "<td>".$row['pub_time']."</td>";
    $string.= "<td><a href='###' id='edit'>编辑</a></td>";
    $string.= "<td><a href='/blog/admin/control.php?action=del&id={$row['article_id']}' class='del'>删除</a></td>";
    $string.= "<td><a href='/blog/admin/control.php?action=del&id={$row['article_id']}&real=true' class='del'>彻底删除</a></td>";
    $string.= "</tr>";
  }
  $string.= "</table>";
  echo "$string";
}

function get_recycle_page(){
  $string= "<h1>回收站</h1>";
  $string.= "<a href='###' id='article'>返回文章管理页</a>";  
  $string.= "<a href='/blog/admin/control.php?action=del&real=true' class='del'>× 清空回收站</a>";
  $string.= "<table><tr><th>序号</th><th>文章编号</th><th>标题</th><th>发表时间</th><th colspan=2>操作</th></tr>";
  $conn= db_connect();
  $result= $conn->query("select article_id,title,pub_time from recycle order by pub_time desc");
  $rows= $result->num_rows;
  for($i=1;$i<=$rows;$i++){
    $row = $result->fetch_array();
    $string.= "<tr><td>{$i}</td>";
    $string.= "<td>".$row['article_id']."</td>";
    $string.= "<td>".$row['title']."</td>";
    $string.= "<td>".$row['pub_time']."</td>";
    $string.= "<td><a href='/blog/admin/control.php?action=recover&id={$row['article_id']}' class='recover'>恢复</a></td>";
    $string.= "<td><a href='/blog/admin/control.php?action=del&id={$row['article_id']}&real=true' class='del'>彻底删除</a></td>";
    $string.= "</tr>";
  }
  $string.= "</table>";
  echo "$string";
}

function get_edit_page(){
  $string= "<h1>文章管理</h1>";
  $string.= "<form action='control.php?action=insert_article' method='post'>";
  $string.= "标题：<input type='text' name='title' value='未命名文章'></input></br>";
  $string.= "文件名：<input type='text' name='file_name' value='未命名文章'></input>".".html</br>";
  $string.= "分类：<input type= 'text' name='class'></input>";
  $string.= "标签名：<input type= 'text' name='tags'></input>";
  $string.= "<script id='container' name='content' type='text/plain'></script>
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
//$real 是彻底删除的意思。

function del_article($id,$real= false){
    $conn= db_connect();
    if($real=='false'){
      $query= "insert into recycle select * from article where article_id={$id}";
      $result= $conn->query($query);
      }else{
    $query1= "delete from recycle ";
    if($id!='none'){
      $query1.="where article_id={$id}";
    }
    $result1= $conn->query($query1);        
  }
    if($id=='none'){
      if(!$result1){
        echo "false";
      }else{
      echo "true";
    }
    exit();
    }
    $query2= "delete from article where article_id={$id}";
    $result2= $conn->query($query2);
    if(!$result2){
      echo "false";
    }else{
      echo "true";
    }
}

function recover_article($id){
    $conn= db_connect();
    $query= "insert into article select * from recycle where article_id={$id}";
    $result= $conn->query($query);
    $query2= "delete from recycle where article_id={$id}";
    $result2= $conn->query($query2);
    if(!$result2){
      echo "false";
    }else{
      echo "true";
    }
}

function insert_article($title='未命名文章',$file_name='未命名文章.html',$class,$tags,$content){
    $user= isset($_SESSION['user'])?$_SESSION['user']:'天边';
    $article_id= get_article_id();
    $link= get_article_link($article_id,$file_name);
    $conn= db_connect();
    $query= "insert into article(article_id,title,link,user,content,class,keywords) 
    values({$article_id},'{$title}','{$link}','{$user}','{$content}','{$class}','{$tags}')";
    $result=$conn->query($query);
    if(!$result){
      echo "false";
    }else{
      echo "true";
    }
    exit();
}
function get_article_id(){
    @$date= date("Ymd");
    $conn= db_connect();
    $query1= "select count(*) from article where article.article_id like '{$date}%'";
    $query2="select count(*) from recycle where recycle.article_id like '{$date}%'";
    $result= $conn->query($query1);
    $row= $result->fetch_array();
    $result2= $conn->query($query2);
    $row2= $result2->fetch_array();
    $total=$row['count(*)']+$row2['count(*)'];
    $article_id= $total+1;
    if($article_id<10){
      $article_id= "0".$article_id;
    }
    $article_id= $date.$article_id;
    return $article_id;
}
function get_article_link($article_id,$file_name){
    @$year= date("Y");
    @$month= date("m");
    $link= "/blog/page/{$year}/{$month}/{$file_name}";
    return $link;   
}
?>