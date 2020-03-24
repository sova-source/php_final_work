<?php
session_start();
require_once("../config.php");

?>


<div align=center>
	<div  style="background-color:#D3D3D3; width:200px; padding:10px" >
		<a href='auth.php?enter=1'> Войти </a>
		<br /> <a href='auth.php?enter=2'> Зарегистрироваться </a><br>
	</div>
</div>

<?php

$enter_form = '<div align=center>
<div  style="background-color:#D3D3D3; width:200px; padding:10px" >
<form  method=post>
Имя <br /><input type=text name=log><br>
Пароль <br /> <input type=password name=passw><br>
<br /><input type=submit name=button value=Enter><br>
</form>
</div>
</div>';

$sign_form = '<div align=center>
<div  style="background-color:#D3D3D3; width:200px; padding:10px" >
<form  method=post>
Имя <br /><input type=text name=log_to_sign><br>
Пароль <br /> <input type=password name=passw_to_sign><br>
Пароль еще раз <br /> <input type=password name=passw_to_sign2><br>
<br /><input type=submit name=button_to_sign value=SignIn><br>
</form>
</div>
</div>';

$notfound = "<HTML><HEAD>
            <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php'>
            </HEAD></HTML>";

if ($_GET['enter']==1)
{
	echo $enter_form;
}

if ($_GET['enter']==2)
{
	echo $sign_form;
}

if ($_GET['enter']>2)
{
	echo $notfound;
}
?>


<?php
if ($_POST['button'])
{
	if (!preg_match("/http\:\/\/photo\/admin\/auth\.php/", $_SERVER['HTTP_REFERER'])) exit();
	
	$log = $_POST['log'];
	if(empty($_POST['log'])) exit ('Enter login');
	$passw = md5(shaetywvqcyuqwetcvbqyw.$_POST['passw']);
	if(empty($_POST['passw'])) exit ('Enter password');
	//====================================================================
	$select = mysqli_query($dbcnx,"SELECT name, pass, ip, ip_del_time FROM userlist WHERE name = '".(string)$_POST['log']."'");
if (!$select) exit ('error 110');
$row = mysqli_fetch_row($select);
if (!$row) exit ('No user with this name');

if (ini_get('register_globals')) unset($auth);

if (!$_SESSION['bruteforce'])
{
$_SESSION['bruteforce'] = 0;
$_SESSION['bruteforce_t'] = time();
$_SESSION['bruteforce_ip'] = $_SERVER['REMOTE_ADDR'];
}

$_SESSION['bruteforce']++;

if ((time()-$_SESSION['bruteforce_t'])>9) unset ($_SESSION['bruteforce']);

if (!empty ($row[2]))
{
	if ( (time() - $row[3])<9 ) 
	{
		exit ('IP try after 10 minutes');
	}
	else
	{
		$select = @mysqli_query($dbcnx, "UPDATE userlist SET ip='', ip_del_time=0 WHERE name = '".$_POST['log'].'"');
										
		if (!$select) exit ('112');
		unset ($_SESSION['bruteforce']);
		exit();
	}	

}

if ($_SESSION['bruteforce']> 3)
{
	$select = @mysqli_query($dbcnx, "UPDATE userlist SET ip='$_SESSION[bruteforce_ip]', ip_del_time='$_SESSION[bruteforce_t]'
										WHERE name = '".$_POST['log'].'"');
	exit('Try after 10 minutes');
}
	//====================================================================

	if (($log === $row[0]) and ($passw === $row[1]))
	{
		$_SESSION['auth'] = $log;
		//echo "Enter to admin -> <a href='index.php'> admin </a>";
		echo "<HTML><HEAD>
            <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php'>
            </HEAD></HTML>";
	}
	else
	{
		
		return exit ('Not correct');
	}
}



if ($_POST['button_to_sign'])
{
	if (!preg_match("/http\:\/\/photo\/admin\/auth\.php/", $_SERVER['HTTP_REFERER'])) exit();
	
	$log = $_POST['log_to_sign'];
	if(empty($_POST['log_to_sign'])) exit ('Enter login');
	$passw = md5(shaetywvqcyuqwetcvbqyw.$_POST['passw_to_sign']);
	if(empty($_POST['passw_to_sign'])) exit ('Enter password');
	$passw2 = md5(shaetywvqcyuqwetcvbqyw.$_POST['passw_to_sign2']);
	if(empty($_POST['passw_to_sign2'])) exit ('Enter password repeatedly');
	
	if ($passw !== $passw2) exit ('The passwords you entered dont match');
	
	
	$select = mysqli_query($dbcnx, 'SELECT name, pass FROM userlist WHERE name = "'.$_POST['log_to_sign'].'"');
	$row = mysqli_fetch_row($select);
	
		if ($log === $row[0])
	{
		echo "Not correct, a user with this name already exists";
		
	}
	else
	{
		$query = "INSERT INTO userlist (name, pass) VALUES ('$log','$passw')";
		mysqli_query($dbcnx, $query);
		$_SESSION['auth'] = $log;
		echo $log.' you have successfully registered';
	}
}


?>