<?php
function checkSQLExp($value)
{
    if (!get_magic_quotes_gpc()) {
        // 进行过滤 
        $value = addslashes($value);
    }
    $value = str_replace("_", "\_", $value);
    $value = str_replace("%", "\%", $value);
    return $value;
}
