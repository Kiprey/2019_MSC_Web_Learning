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

        $mysql_server_name='localhost';
        $mysql_username='root';
        $mysql_password='';
        $mysql_datebase='MSC_Web_Learning';
        $mysql_table='testtable';

        $sql_connect=mysqli_connect($mysql_server_name,$mysql_username,$mysql_password, $mysql_datebase);
        if(!$sql_connect)
            exit ("连接数据库失败！");

        //在此处不做插入数据的判断,因为侧重点不在这里
        $sqlStr="INSERT INTO $mysql_table VALUES ($trueName, $sex, $vitalEmail, $callNum,
        $internDepartment, $internPosition, $phoneNum, $commonEmail, $whatSexToLove)";
        //echo $sqlStr;
        if(mysqli_query($sql_connect,$sqlStr))
        {
            echo "报名成功！";
        }
        else
           exit ("提交报名表失败！");
    }
    else
        exit ("你想干啥？？？");
?>