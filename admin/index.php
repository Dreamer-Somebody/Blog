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
include_once "admin_fn.php";
@$action = $_GET['action'] || '';
if ($action == 'log-out') {
    unset($action);
    $_SESSION = array();
    session_destroy();
}
if (!check_valid_user()) {
    echo "<p>没有登录，请登录！<a href='Login.html'>登陆<a/></p>";
    die();
}
?>
	<div id="header">
	<div><p>管理员：<?php echo "{$_SESSION['user']}" ?></p>
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
                <li class="active">
                    <a href="index.php" id="index"><i class="icon-home3"></i>首页</a>
                </li>
                <li>
                    <a href="###" id="article"><i class="icon-code"></i>文章管理</a>
                </li>
                <li>
                    <a href="###" id="comment"><i class="icon-life"></i>评论管理</a>
                </li>
                <li>
                    <a href="###" id="file"><i class="icon-file-text"></i>文件管理</a>
                </li>
                  <li>
                    <a href="/blog/index.php"><i class="icon-news"></i>返回网站</a>
                </li>
            </ul>
        </div>
    </div>
    <div id="content"></div>
    <script src="/blog/common/jquery.js"></script>
    <script src="/blog/components/sidebar/sidebar.js"></script>
    <script src="js/index.js"></script>
</body>
</html>
