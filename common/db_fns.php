<?php

function db_connect() {
   $result = new mysqli('localhost', 'root', '545877356', 'blog');
   if (!$result) {
     throw new Exception('Could not connect to database server');
   } else {
     return $result;
   }
}

?>
