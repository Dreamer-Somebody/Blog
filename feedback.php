<?php
include_once "common/include.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>留言板</title>
    <link rel="stylesheet" href="/blog/common/article.css">
</head>
<body>
    <div id='comment'>
        <h1>留言</h1>
        <form id='comment_form'>
            <p id='tips'>
                &lt;(￣v￣)/点击头像</br>可以更换头像</p>
            <div class='pic'>
             <div class='mask'><i class='icon-loop'></i></div><img src='' name='avatar' id='photo' /></div>
            <input type='text' id='nickname' name='nickname' placeholder='昵称' />
            <textarea placeholder='留言...' name='content'></textarea>
            <div id='emoji'></div>
            <input type='hidden' name='pic' value='' />
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
