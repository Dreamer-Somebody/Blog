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
            echo "<p id='tips'>请选择正确的分类信息。。。</p>";
            return;
        }
        for ($i = 1; $i <= $rows; $i++) {
            $row = $result->fetch_array();
            echo "<div class='article'>";
            echo "<a class='head_pic' href='{$row['link']}'><div class='more'><i class='icon-control_point'></i></div><img src='{$row['head_pic']}'/></a>";
            echo "<a class='tags' href='/blog/sort.php?key=class&value={$row['class']}'>{$row['class']}</a>";
            echo "<a href='{$row['link']}' class='title'><h2>{$row['title']}</h2></a>";
            //411是137个汉字
            if (strlen($row['content']) > 600) {
                $row['content'] = substr($row['content'], 0, 600) . "......";
            }
            $row['content'] = strip_tags($row['content']);
            $row['content'] = preg_replace("/&nbsp;/", "", $row['content']);
            echo "<a href='{$row['link']}' class='content'><p>{$row['content']}</p></a>";
            $row['pub_time'] = explode(' ', $row['pub_time'])[0];
            echo "<p class='info2'><i class='icon-clock'></i>时间：{$row['pub_time']}<i class='icon-eye'></i>阅读({$row['click']}) <i class='icon-bubbles'></i>评论({$row['comment']})
            <i class='icon-good'></i>赞({$row['fav']})<i class='icon-price-tag'></i>标签: {$row['keywords']}</p>";
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

function show_classify($all = 0)
{
    echo "<div id='classify'><div class='date'><h3>文章归档</h3>";
    $conn   = db_connect();
    $query  = "select pub_time from article order by pub_time desc";
    $result = $conn->query($query);
    $rows   = $result->num_rows;
    $dates  = array();
    echo "<ul class='date'>";
    for ($i = 1; $i <= $rows; $i++) {
        $array  = array();
        $row    = $result->fetch_array();
        $datess = explode('-', $row['pub_time']);
        array_push($array, $datess[0], $datess[1]);
        $string = implode('', $array);
        if (@is_null($dates[$string])) {
            $dates[$string] = 1;
        } else {
            $dates[$string]++;
        }
    }
    $num       = 0;
    $date_rows = ($all == 1 || count($dates) < 5 ? count($dates) : 5);
    while ((list($key, $val) = each($dates)) && $num < $date_rows) {
        echo "<li><a href='/blog/sort.php?key=article_id&value={$key}'>{$key} ({$val})</a></li>";
        $num++;
    }
    if ($all == 0) {
        echo "<li><a href='/blog/sort.php?key=article_id'>更多 &nbsp;&gt;&gt;</a></li>";
    }
    echo "</ul></div><div class='category'><h3>文章分类</h3>";
    $query  = "select distinct class from article";
    $result = $conn->query($query);
    $rows   = $result->num_rows;
    echo "<ul class='category'>";
    $category_rows = ($all == 1 || $rows < 7 ? $rows : 7);
    for ($i = 1; $i <= $category_rows; $i++) {
        $row = $result->fetch_array();
        echo "<li><a href='/blog/sort.php?key=class&value={$row['class']}'>{$row['class']}</a></li>";
    }
    if ($all == 0) {
        echo "<li><a href='/blog/sort.php?key=class'>更多 &nbsp;&gt;&gt;</a></li>";
    }
    echo "</ul></div><div class='tags'><h3>标签分类</h3>";
    $query  = "select keywords from article";
    $result = $conn->query($query);
    $rows   = $result->num_rows;
    $tags   = array();
    echo "<ul class='tags'>";
    $tags_row = ($all == 1 ? $rows : 11);
    for ($i = 0, $num = 0; ($i < $rows) && ($num < $tags_row); $i++) {
        $row = $result->fetch_array();
        if ($row['keywords'] == null) {
            continue;
        }
        $tagss = explode(' ', $row['keywords']);
        $len   = count($tagss);
        for ($j = 0; $j < $len; $j++) {
            if (@is_null($tags[$tagss[$j]])) {
                $tags[$tagss[$j]] = $tagss[$j];
                $num++;
            }
        }

    }
    while (list($val) = each($tags)) {
        echo "<li><a href='/blog/sort.php?key=keywords&value={$val}'>{$val}</a></li>";
    }
    if ($all == 0) {
        echo "<li><a href='/blog/sort.php?key=keywords'>更多 &nbsp;&gt;&gt;</a></li>";
    }
    echo "</ul></div>";
    if ($all == 0) {
        echo "<div class='hot'><h3>推荐文章</h3>";
        $query  = "select title,link from article order by click desc,fav desc limit 8";
        $result = $conn->query($query);
        $rows   = $result->num_rows;
        echo "<ul class='hot'>";
        for ($i = 1; $i <= $rows; $i++) {
            $row = $result->fetch_array();
            echo "<li><a href='{$row['link']}'>{$row['title']}</a></li>";
        }
        echo "</ul></div>";
    }
    echo "</div>";
}
