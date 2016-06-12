<?php
function db_connect() {
   $result = new mysqli('localhost', 'root', '545877356', 'blog');
   if (!$result) {
     throw new Exception('不能连接到数据库');
   } else {
     return $result;
   }
}

?>
