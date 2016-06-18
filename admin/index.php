<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="/blog/common/base.css" />
    <link rel="stylesheet" href="/blog/components/sidebar/sidebar.css">
    <link rel="stylesheet" href="css/index.css" />
</head>
<body>
<?php
include("admin_fn.php");
@$action= $_GET['action']||'';
 if($action == 'log-out') {
    unset($action);
    $_SESSION=array();
    session_destroy();
  }
if(!check_valid_user()){
	echo "<p>没有登录，请登录！<a href='Login.html'>登陆<a/></p>";
	die();
}
?>
	<div id="header">        
	<div><p>管理员：<?php echo "{$_SESSION['user']}"?></p>
	<a href="index.php?action='log-out'"><i class="icon-switch"></i>退出登陆</a></div>
	</div>
    <div id="sidebar">
        <div id="head">
            <div id="avatar"></div>
            <p>天边的博客</p>
            <p id="size_small">Sing it for the people like us.</p>
        </div>
        <div id="body">
            <ul id="nav">
                <li class="active"><a href="control.php"><i class="icon-home3"></i>首页</a></li>
                <li>
                    <a href="control.php?action=article_manage"><i class="icon-code"></i>文章管理</a>
                </li>
                <li>
                    <a href="control.php?action=comment_manage"><i class="icon-life"></i>评论管理</a>
                </li>
                <li>
                    <a href="control.php?action=file_manage"><i class="icon-file-text"></i>文件管理</a>
                </li>
                  <li>
                    <a href="/blog/index.php"><i class="icon-news"></i>返回网站</a>
                </li>
            </ul>
        </div>
    </div>
    <script src="/blog/common/jquery.js"></script>
    <script src="/blog/components/sidebar/sidebar.js"></script>
</body>
</html>
