<?php
include "databaseInfo.php";
include "veryfyUsersSession.php";

//检测是否进行了更改/删除帖子操作
if (isset($_POST['changedMsgNum']) && !empty($_POST['changedMsgNum'])) {
    $changedNum = checkSQLExp($_POST['changedMsgNum']);
    //判断是否尝试非法修改帖子
    if ($row['isAdmin'] == 0)
        $sqlExp = "SELECT * FROM $mysql_postMsg_table WHERE usersEmail='$email' AND num='$changedNum'";
    else
        $sqlExp = "SELECT * FROM $mysql_postMsg_table WHERE num='$changedNum'";
    $mysql_aResult = mysqli_query($sql_connect, $sqlExp);
    $a_row = $mysql_aResult->fetch_assoc();
    if (empty($a_row))
        exit("所指向的帖子无法修改");

    //进行修改帖子操作
    if (isset($_POST['changedPostMsg']) && !empty(trim($_POST['changedPostMsg']))) {
        $changedPostMsg = checkSQLExp($_POST['changedPostMsg']);

        if (mysqli_query(
            $sql_connect,
            "UPDATE $mysql_postMsg_table SET postMsg='$changedPostMsg' WHERE num='$changedNum'"
        ))
            echo "帖子修改成功！";
        else
            echo "帖子修改失败！";
        //删除帖子
    } else {
        if (mysqli_query(
            $sql_connect,
            "DELETE FROM $mysql_postMsg_table WHERE num='$changedNum'"
        ))
            echo "帖子删除成功！";
        else
            echo "帖子删除失败!";
    }
} else {
    //发帖
    if (isset($_POST["sentPostMsg"]) && !empty(trim($_POST["sentPostMsg"]))) {
        $sentPostMsg = $_POST["sentPostMsg"];
        if (strlen($sentPostMsg) > 255)
            exit("帖子长度过长，疑似不正常的操作！");
        $sentPostMsg = checkSQLExp($sentPostMsg);
        //插入帖子
        $dateTimeClass = new DateTime('Asia/Shanghai');
        $dateTime = $dateTimeClass->format('Y-m-d H:i:s');
        $sqlStr = "INSERT INTO $mysql_postMsg_table (postMsg, usersEmail, dateTime) VALUES ('$sentPostMsg', '$email', '$dateTime')";

        if (mysqli_query($sql_connect, $sqlStr)) {
            echo "发帖成功！";
        } else
            echo "发帖失败！";
    }
}
echo "<br>";

//针对不同用户设置不同查询条件
if ($row['isAdmin'] == 0)
    $sqlStr = "SELECT * FROM $mysql_postMsg_table WHERE usersEmail='$email' ORDER BY dateTime DESC";
else
    $sqlStr = "SELECT * FROM $mysql_postMsg_table ORDER BY dateTime DESC";
//输出帖子信息
if ($result = mysqli_query($sql_connect, $sqlStr)) {
    while ($rowMsg = $result->fetch_assoc()) {
        echo htmlspecialchars($rowMsg['num'] . ":  " . " / UserEmail: " . $rowMsg['usersEmail'] . " / DateTime: " . $rowMsg['dateTime'] . " / Msg: " . $rowMsg['postMsg']) . "<br>";
    }
} else
    echo "查看帖子失败！";


mysqli_close($sql_connect);
