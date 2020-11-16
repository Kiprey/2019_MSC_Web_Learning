<?php
session_start();
include "databaseInfo.php";
include "checkSQLExp.php";

//如果已经登录，则退出
if (isset($_SESSION['user'])) {
    exit("您已登录，请不要重复登录");
}

if ($_POST) {
    if (!isset($_POST["email"]) || empty(trim($_POST["email"])))
        exit("不正常的提交，未输入邮箱或输入内容存在空格");
    else if (!isset($_POST["password"]) || empty(trim($_POST["password"])))
        exit("不正常的提交，未输入密码或输入内容存在空格");
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (strlen($password) > 16)
        exit("密码长度过长，疑似不正常的操作！");
    else if (strlen($email) > 100)
        exit("邮箱长度过长，疑似不正常的操作！");

    //插入数据库


    $sql_connect = mysqli_connect($mysql_server_URL, $mysql_username, $mysql_password, $mysql_datebase);
    if (mysqli_connect_errno($sql_connect))
        exit("连接数据库失败:" . mysqli_connect_error());

    $email = checkSQLExp($email);
    $password = checkSQLExp($password);


    $mysql_result = mysqli_query(
        $sql_connect,
        "SELECT * FROM $mysql_table WHERE email='$email' AND password='$password'"
    );
    $row = $mysql_result->fetch_assoc();
    if (empty($row))
        exit("邮箱不存在,或密码错误");

    if (!empty($mysql_result)) {
        $_SESSION['user'] = $email;
        header('Location: operatorCenter.html');
    } else
        echo "Error";
    mysqli_close($sql_connect);
} else
    echo "Hacker?";
