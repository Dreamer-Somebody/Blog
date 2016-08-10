<?php
function show_article_list($query, $page = 1, $sub_query = '', $num = 10)
{
    $conn      = db_connect();
    $start     = ($page - 1) * $num;
    $new_query = $query . " limit {$start},{$num}";
    $result    = $conn->query($new_query);
    if ($result) {
        $rows = $result->num_rows;
        if ($rows == 0) {
            echo "<p align='center'>很抱歉/(ㄒoㄒ)/~~，没有相关文章。。。</p>";
        }
        for ($i = 1; $i <= $rows; $i++) {
            $row = $result->fetch_array();
            echo "<div class='article'>";
            echo "<a class='tags' href='/blog/sort.php?key=class&value={$row['class']}'>{$row['class']}</a>";
            echo "<a href='{$row['link']}' class='title'><strong>{$row['title']}</strong></a>";
            echo "<p class='info1'>{$row['user']} 发表于 {$row['pub_time']}</p>";
            //411是137个汉字
            $row['content'] = strip_tags($row['content']);
            $row['content'] = preg_replace("/&nbsp;/", "", $row['content']);
            echo "<a href='{$row['link']}' class='content'><p>{$row['content']}</p></a>";
            echo "<p class='info2'><i class='icon-eye'></i>阅读({$row['click']}) <i class='icon-bubbles'></i>评论({$row['comment']})<i class='icon-good'></i>赞({$row['fav']})<i class='icon-price-tag'></i>标签: {$row['keywords']}</p>";
            echo "</div>";
        }

        //分页条相关

        $pattern    = "/select[\w\W]+from/";
        $query2     = preg_replace($pattern, "select count(*) from", $query);
        $result2    = $conn->query($query2);
        $row        = $result2->fetch_array();
        $total      = $row[0];
        $total_page = ceil($total / ($num * 1.0));
        echo "<ul id='page_select'><li><a href='{$_SERVER['PHP_SELF']}?" . $sub_query . "'>首</a></li>";
        if ($page > 1) {
            echo "<li><a href='" . $_SERVER['PHP_SELF'] . "?{$sub_query}&page=" . ($page - 1) . "'>&lt&lt</a></li>";
        } else {
            echo "<li id='disabled'><span>&lt&lt</span></li>";
            echo "<li class='current'><span class='current'>1</span></li>";
        }
        if ($page - 2 >= 1) {
            if ($page != 3) {
                echo "<li><span>...</span></li>";
            }
            echo "<li><a href='" . $_SERVER['PHP_SELF'] . "?{$sub_query}&page=" . ($page - 2) . "'>" . ($page - 2) . "</a></li>";
            echo "<li><a href='" . $_SERVER['PHP_SELF'] . "?{$sub_query}&page=" . ($page - 1) . "'>" . ($page - 1) . "</a></li>";
            echo "<li class='current'><span class='current'>{$page}</span></li>";
        } elseif ($page == 2) {
            echo "<li class='current'><span class='current'>2</span></li>";
        }
        if ($page + 2 <= $total_page) {
            echo "<li><a href='" . $_SERVER['PHP_SELF'] . "?{$sub_query}&page=" . ($page + 1) . "'>" . ($page + 1) . "</a></li>";
            echo "<li><a href='" . $_SERVER['PHP_SELF'] . "?{$sub_query}&page=" . ($page + 2) . "'>" . ($page + 2) . "</a></li>";
            if ($page != $total_page - 2) {
                echo "<li><span>...</span></li>";
            }
        }
        if ($page < $total_page) {
            echo "<li><a href='" . $_SERVER['PHP_SELF'] . "?{$sub_query}&page=" . ($page + 1) . "'>&gt&gt</a></li>";
        } else {
            echo "<li id='disabled'><span>&gt&gt</span></li>";
        }
        echo "<li><a href='" . $_SERVER['PHP_SELF'] . "?{$sub_query}&page=" . ($total_page) . "'>尾</a></li>";
        echo "<li id='total'><span>共{$total_page}页</span></li>";
        echo "</ul>";
    }
}
