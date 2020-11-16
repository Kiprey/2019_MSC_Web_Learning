<?php
include "databaseInfo.php";
include "veryfyUsersSession.php";

if (isset($_POST['changedUser_Email']) && !empty(trim($_POST['changedUser_Email']))) {
    $changedUser_Email = checkSQLExp($_POST['changedUser_Email']);

    //设置用户所能操作的用户空间
    if ($row['isAdmin'] == 0)
        $sql_msgs = "SELECT * FROM $mysql_users_table WHERE email='$email' AND email='$changedUser_Email'";
    else
        $sql_msgs = "SELECT * FROM $mysql_users_table WHERE email='$changedUser_Email'";
    $mysql_aResult = mysqli_query($sql_connect, $sql_msgs);
    $a_row = $mysql_aResult->fetch_assoc();
    if (empty($a_row))
        exit("所指向的用户无法修改");

    if (isset($_POST['changedQQ']) && !empty(trim($_POST['changedQQ']))) {
        $changedQQ = $_POST['changedQQ'];

        if (!is_numeric($changedQQ) || strlen($changedQQ) > 11)
            exit("QQ非纯数字，或者长度过长，疑似不正常的操作！");
        if (!mysqli_query($sql_connect, "UPDATE $mysql_users_table SET qq='$changedQQ' WHERE email='$changedUser_Email'"))
            exit("在修改用户QQ时发生错误");
        else
            echo "QQ修改成功<br>";
    }
    if (isset($_POST['changedName']) && !empty(trim($_POST['changedName']))) {
        $changedName = $_POST['changedName'];
        if (strlen($changedName) > 15)
            exit("姓名长度过长，疑似不正常的操作！");
        $changedName = checkSQLExp($changedName);
        if (!mysqli_query($sql_connect, "UPDATE $mysql_users_table SET name='$changedName' WHERE email='$changedUser_Email'"))
            exit("在修改用户name时发生错误");
        else
            echo "name修改成功<br>";
    }
    if (isset($_POST['changedEmail']) && !empty(trim($_POST['changedEmail']))) {
        $changedEmail = $_POST['changedEmail'];
        if (strlen($changedEmail) > 100)
            exit("邮箱长度过长，疑似不正常的操作！");
        else if (!(filter_var($changedEmail, FILTER_VALIDATE_EMAIL)))
            exit("不是一个有效的邮箱");
        $changedEmail = checkSQLExp($changedEmail);
        $mysql_result = mysqli_query(
            $sql_connect,
            "SELECT * FROM $mysql_users_table WHERE email='$changedEmail'"
        );
        $row = $mysql_result->fetch_assoc();
        if (!empty($row))
            exit("此邮箱已被注册，更改邮箱失败");

        if (!mysqli_query($sql_connect, "UPDATE $mysql_users_table SET email='$changedEmail' WHERE email='$changedUser_Email'"))
            exit("在修改用户email时发生错误");
        else
            echo "email修改成功<br>";
    }
    //两次密码只要有一个输入框中输入了密码
    if ((isset($_POST['changedPassword']) && !empty(trim($_POST['changedPassword'])))
        || (isset($_POST['changedPassword_repeat']) && !empty(trim($_POST['changedPassword_repeat'])))
    ) {
        if ($_POST['changedPassword'] !== $_POST['changedPassword_repeat'])
            exit("两次密码输入不相同，请重新输入");
        $changedPassword = checkSQLExp($_POST['changedPassword']);
        if (strlen($changedPassword) > 16)
            exit("密码长度过长，疑似不正常的操作！");
        if (!mysqli_query($sql_connect, "UPDATE $mysql_users_table SET password='$changedPassword' WHERE email='$changedUser_Email'"))
            exit("在修改用户password时发生错误");
        else
            echo "password修改成功<br>";
    }
}
//两次删除邮箱只要有一个输入框中输入了邮箱
else if ((isset($_POST['deletedUser_Email']) && !empty(trim($_POST['deletedUser_Email'])))
    || (isset($_POST['deletedUser_Email_repeat']) && !empty(trim($_POST['deletedUser_Email_repeat'])))
) {
    if ($_POST['deletedUser_Email'] !== $_POST['deletedUser_Email_repeat'])
        exit("两次邮箱输入不相同，请重新输入");
    $deletedUser_Email = $_POST['deletedUser_Email'];
    if (strlen($deletedUser_Email) > 100)
        exit("邮箱长度过长，疑似不正常的操作！");
    else if (!(filter_var($deletedUser_Email, FILTER_VALIDATE_EMAIL)))
        exit("不是一个有效的邮箱");
    $deletedUser_Email = checkSQLExp($deletedUser_Email);

    if ($row['isAdmin'] == 0)
        $sql_str = "DELETE FROM $mysql_users_table WHERE email='$email' AND email='$deletedUser_Email'";
    else
        $sql_str = "DELETE FROM $mysql_users_table WHERE email='$deletedUser_Email'";

    if (!mysqli_query($sql_connect, $sql_str))
        exit("在删除用户时发生错误");
    else
        echo "命令已执行，如果用户没有删除，可能是因为权限不足<br>";
}

//针对不同用户设置不同查询条件
if ($row['isAdmin'] == 0)
    $sqlStr = "SELECT * FROM $mysql_users_table WHERE email='$email'";
else
    $sqlStr = "SELECT * FROM $mysql_users_table";
//输出用户信息
if ($result = mysqli_query($sql_connect, $sqlStr)) {
    while ($rowMsg = $result->fetch_assoc()) {
        if ($email == $rowMsg['email'])
            echo "==> ";
        echo  htmlspecialchars("Email: " . $rowMsg['email'] . " / name: " . $rowMsg['name'] . " / QQ: " . $rowMsg['qq']) . "<br>";
    }
} else
    echo "查看用户信息失败！";


mysqli_close($sql_connect);
