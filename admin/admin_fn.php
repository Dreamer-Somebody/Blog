<?php
include("../common/db_fns.php");
function login($username, $password) {
// check username and password with db
// if yes, return true
// else throw exception

  // connect to db
  $password= sha1($password);
  $conn = db_connect();
  // check if username is unique
  $result = $conn->query("select * from user where username='{$username}' and password ='{$password}'");
  if (!$result) {
     return false;
  }
  if ($result->num_rows>0) {
     return true;
  } else {
     return false;
  }
}

function check_valid_user() {
  $result= false;
  if (isset($_SESSION['valid_user'])){
  $result= true;
  }
  return $result;
}

function change_password($username, $old_password, $new_password) {
// change password for username/old_password to new_password
// return true or false

  // if the old password is right
  // change their password to new_password and return true
  // else throw an exception
  login($username, $old_password);
  $conn = db_connect();
  $result = $conn->query("update user
                          set passwd = sha1('".$new_password."')
                          where username = '".$username."'");
  if (!$result) {
    throw new Exception('Password could not be changed.');
  } else {
    return true;  // changed successfully
  }
}
?>