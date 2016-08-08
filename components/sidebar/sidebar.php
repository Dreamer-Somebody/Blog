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
                <li class="active"><a href="/blog/index.php"><i class="icon-home3"></i>首页</a></li>
                <li>
                    <a href="/blog/sort.php?key=keywords&value=技术"><i class="icon-code"></i>技术</a>
                </li>
                <li>
                    <a href="/blog/sort.php?key=keywords&value=生活"><i class="icon-life"></i>生活</a>
                </li>
                <li>
                    <a href=""><i class="icon-switch"></i>实验室</a>
                </li>
                <li>
                    <a href="/blog/feedback.php"><i class="icon-file-text"></i>留言板</a>
                </li>
                <li>
                    <a href=""><i class="icon-account_circle"></i>关于</a>
                </li>
            </ul>
        </div>
    </div>
    <script src="/blog/common/jquery.js"></script>
    <script>
    jQuery(document).ready(function($) {
        $("ul#nav a").click(function(event) {
            var $target = event.target;
            $("ul#nav li").removeClass("active");
            $($target.parentNode).addClass('active');
        });
        var $i=0;
    });
    </script>
</body>
</html>
