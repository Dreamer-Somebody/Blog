<?php
header("Content-type:text/html;charset=utf-8");
echo '开始mysql数据库连接．<br>';
$con = new mysqli("127.0.0.1", "root", "545877356", "blog");
if ($con) {
    echo ' mysql yes．<br>';
    $sql    = "select title,content from article";
    $result = $con->query($sql);
    $len    = $result->num_rows;
    for ($i = 0; $i < $len; $i++) {
        $row = $result->fetch_array();
        echo "begin:  ";
        echo $row['title'] . $row['content'];
        echo "    :end  ";
    }
} else {
    die(' mysql no ：' . mysql_error());
}
mysql_close($con);
echo ' mysql close．';
