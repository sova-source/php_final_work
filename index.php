<?php
session_start();
if ($_GET['exit'] == 1)
{
	unset ($_SESSION['auth']);
	session_destroy();
	//exit();
	echo "<HTML><HEAD>
            <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php'>
            </HEAD></HTML>";
}

  Error_Reporting(E_ALL & ~E_NOTICE);
  $title = $titlepage = "Фотогалерея";
  // Устанавливаем соединение с базой данных
  require_once ("config.php");
  // Выводим шапку страницы
  include "util/topadmin.php";
  
  // Извлекаем из строки запроса параметр id_parent
  $id_parent = $_GET['id_parent'];
  if(empty($id_parent)) $id_parent = 0;
  if(!preg_match("|^[\d]+$|",$id_parent) && !empty($id_parent)) exit();
?>


<div align=left>
<div  style="background-color:#D3D3D3; width:200px; padding:10px" >

<br /><?php echo'USER: '.$_SESSION['auth'];?><br>
<br /><?php if (!empty($_SESSION['auth'])){echo "<a href='index.php?exit=1'>  EXIT </a>";} ?><br>
<br /><?php if (empty($_SESSION['auth'])){echo "<a href='admin/auth.php'> go to login page </a>";} ?><br>
<!--<br /><input type=submit name=button value=Enter><br> -->

</div>
</div>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $titlepage; ?></title>
<link rel="StyleSheet" type="text/css" href="util/admin.css">
<script language="" src="photo.js"></script></head>
<body leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" bottommargin="0" topmargin="0" >
<br><br><table width=100%><tr><td width=10%>&nbsp;</td><td>
<table border="0" width="100%">
<tr><td>
  <table cellspacing="8" cellspacing="0" border=0><tr>
    <tr>
<?php
  // Если текущий каталог не является корневым,
  // выводим ссылку для возврата в предыдущее меню
  if ($id_parent != 0)
    echo "<td ><p><a class=menu href=index.php?id_parent=0>Верхний уровень</a></p></td>";
  ?>
  </tr></table>
  <table class=bodytable border="1" cellpadding=5 cellspacing=0 bordercolorlight=gray bordercolordark=white>     
  <?
  // Формируем и выполняем SQL-запрос, извлекающий
  // список групп фотографий
  $query = "SELECT photocat.id_catalog AS id_catalog,
                   photocat.name AS name,
                   COUNT(photo.id_photo) AS total 
            FROM photocat, photo
            WHERE photo.id_catalog = photocat.id_catalog AND photocat.hide = 'show' AND photo.hide = 'show'
            GROUP BY photocat.id_catalog";
  $ctg = mysqli_query($dbcnx, $query);
  if (!$ctg) puterror("Ошибка при обращении к Фотогалерее");
  // Если в таблице catalog присутствует хотя бы одна
  // группа фотографий - выводим их в таблице
  if(mysqli_num_rows($ctg)>0)
  {
    // Выводим заголовок таблицы групп фотографий
    echo "<tr class='tableheadercat'>
            <td align=center><p class=zagtable>Название группы фотографий</td>
            <td>&nbsp;</td>
          </tr>";
    while($cat = mysqli_fetch_array($ctg))
    {
      // Выводим список каталогов
      echo "<tr>
              <td><p><a href=index.php?id_parent=".$cat['id_catalog'].">".$cat['name']."</a></td>
              <td><p>".$cat['total']."</td>
            </tr>";
    }
  }
?>
</table>
</td></tr>
<tr><td>
<?php
  // Выводим содержимое групп фотографий, если текущий каталог не является
  // корневым
  if ($id_parent != 0) include "photos.php";
?>
</td></tr></table>
</td><td width=10%>&nbsp;</td></tr></table>
</body>
</html>