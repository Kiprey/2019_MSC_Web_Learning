<?php
include "databaseInfo.php";
include "checkSQLExp.php";

session_start();
if (isset($_SESSION['user'])) {
    $email = $_SESSION['user'];
} else
    exit("请先登录");

//插入数据库

$sql_connect = mysqli_connect($mysql_server_URL, $mysql_username, $mysql_password, $mysql_datebase);
if (mysqli_connect_errno($sql_connect))
    exit("连接数据库失败:" . mysqli_connect_error());

$mysql_result = mysqli_query(
    $sql_connect,
    "SELECT * FROM $mysql_users_table WHERE email='$email'"
);
$row = $mysql_result->fetch_assoc();
if (empty($row))
    exit("无效的登录信息，请重新登录");


$email = checkSQLExp($email);
