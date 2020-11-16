<?php
include "databaseInfo.php";
include "veryfyUsersSession.php";

unset($_SESSION['user']);
session_unset();
session_destroy();
header('Location: login.html');

mysqli_close($sql_connect);
