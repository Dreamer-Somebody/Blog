<?php
include_once "admin_fn.php";
$conn   = db_connect();
$query  = "select article_id,title from article";
$result = $conn->query($query);
$rows   = $result->num_rows;
for ($i = 0; $i < $rows; $i++) {
    $row = $result->fetch_array();
    $id  = $row['article_id'];
    create_file($id);
}
