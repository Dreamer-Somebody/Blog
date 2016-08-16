<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="/blog/common/base.css" />
    <link rel="stylesheet" href="/blog/components/sidebar/sidebar.css">
</head>
<body>
    <div id="sidebar">
        <div id="head">
            <div id="avatar"></div>
            <p>天边的博客</p>
            <p id="size_small">Sing it for the people like us.</p>
        </div>
        <div id="body">
            <ul id="nav">
                <li><a href="/blog/index.php"><i class="icon-home3"></i>首页</a></li>
                <li>
                    <a href="/blog/sort.php"><i class="icon-code"></i>分类</a>
                </li>
<!--                 <li>
                    <a href="/blog/lab.php"><i class="icon-switch"></i>实验室</a>
                </li> -->
                <li>
                    <a href="/blog/feedback.php"><i class="icon-file-text"></i>留言板</a>
                </li>
                <li>
                    <a href="/blog/about.php"><i class="icon-account_circle"></i>关于</a>
                </li>
            </ul>
        </div>
    </div>
    <script src="/blog/common/jquery.js"></script>
    <script>
    jQuery(document).ready(function($) {
        $path= document.location.pathname;
        if($("#nav a[href*='"+$path+"']").length>0){
            $("#nav li").removeClass("active");
            $("#nav a[href*='"+$path+"']").parent().addClass('active');
        }else{
            $("#nav li:first-child").addClass('active');
        }
    });
    </script>
</body>
</html>
