<?php
include_once "common/include.php";
include_once "admin/admin_fn.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name='keywords 'content='天边,个人博客,前端开发,生活感悟,web开发,blog,html,css,js,php'/>
<meta name='description' content='天边的个人博客,前端开发分享与生活交流，利用html,css,js,php等工具将想法创造成现实。'/>
    <title>留言板--天边的博客</title>
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <link rel="icon" sizes="any" mask="" href="../favicon.ico">
    <link rel="stylesheet" href="/blog/common/article.css">
</head>
<body>
    <div id='comment'>
        <h1>留言</h1>
        <form id='comment_form'>
            <p id='tips'>
                &lt;(￣v￣)/点击头像</br>可以更换头像</p>
            <div class='pic'>
             <div class='mask'><i class='icon-loop'></i></div><img src='/blog/img/avatar/5.jpg' name='avatar' id='photo' /></div>
            <input type='text' id='nickname' name='nickname' placeholder='昵称' value='Ming'/>
            <textarea placeholder='留言...' name='content'></textarea>
            <div id='toolbar'><i class='icon-sentiment_satisfied' id='face'></i><?php echo get_emoji() ?></div>
            <div id='emoji'></div>
            <input type='hidden' name='pic' value='/blog/img/avatar/5.jpg' />
            <input type='hidden' name='article_id' value='0'>
            <input type='hidden' id='comment_parent' name='comment_parent' />
            <input type='hidden' name='comment_html' id='comment_html' />
            <button type='submit' />发表</button>
            <div id='result'></div>
        </form>
        <div id="comment_list"></div>
        </div>
        <div id='choose'><div id='choices'><div id='avatar_pics'></div>
<div id='button'><div id='true'>确认</div></div></div><div id='cancel'><i class='icon-cancel'></i></div></div>
<div id='big_mask'></div>
<script src="/blog/common/article.js"></script>
</body>
</html>
