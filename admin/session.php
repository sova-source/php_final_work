<?php
// добавляем сессионную переменную
session_start();
require_once "../config.php";

if (!$_SESSION['auth'])
{
exit ("<a href='auth.php'> go to login page </a>");
}


if ($_GET['exit'] == 1)
{
	unset ($_SESSION['auth']);
	session_destroy();
	exit();
	
}

if ($_SESSION['auth'])
{
$select = "SELECT id_user FROM userlist WHERE name = '".$_SESSION['auth']."'";
$result = mysqli_query($dbcnx, $select);
$row = mysqli_fetch_row($result);
$session_id_user = $row[0];	
}




?>