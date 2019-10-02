<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $trueName = $_POST["trueName"];
        $sex = $_POST["sex"];
        $vitalEmail = $_POST["vitalEmail"];
        $callNum = $_POST["callNum"];
        $internDepartment = $_POST["internDepartment"];
        $internPosition = $_POST["internPosition"];
        $phoneNum = $_POST["phoneNum"];
        $commonEmail = $_POST["commonEmail"];
        $whatSexToLove = $_POST["whatSexToLove"];

        $mysql_server_URL='localhost';
        $mysql_username='root';
        $mysql_password='';
        $mysql_datebase='msc_web_learning';
        $mysql_table='testtable';

        $sql_connect=mysqli_connect($mysql_server_URL,$mysql_username,$mysql_password, $mysql_datebase);
        if(mysqli_connect_errno($sql_connect))
            exit ("连接数据库失败:".mysqli_connect_error());

        //在此处不做插入数据的判断,因为此代码侧重点不在这里
        //这个SQL语句快把我搞死了   `和'的区别
        $sqlStr="INSERT INTO `$mysql_table` (`trueName`, `sex`, `vitalEmail`, `callNum`, `internDepartment`, `internPosition`, `phoneNum`, `commonEmail`, `whatSexToLove`) 
            VALUES ('$trueName', '$sex', '$vitalEmail', '$callNum', '$internDepartment', '$internPosition', '$phoneNum', '$commonEmail', '$whatSexToLove')";

        if(mysqli_query($sql_connect,$sqlStr))
        {
            echo "报名成功！";
        }
        else
            echo "提交报名表失败！";
        mysqli_close($sql_connect);
    }
    else
        exit ("你想干啥？？？");
?>