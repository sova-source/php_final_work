<?php
session_start();
if ($_SESSION['auth']!='admin')
{
	exit('You do not have the right to edit this section');
}
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Устанавливаем соединение с базой данных
  require_once ("../config.php");
  // Удаляем каталог рекурсивной функцией
  $id_catalog = $_GET['id_catalog'];
  $id_parent = $_GET['id_parent'];
  // Удаляем фотографии в принадлежащие текущему каталогу
  $result = mysqli_query($dbcnx,"SELECT * FROM photo WHERE id_catalog = $id_catalog");
  if (!$result) puterror("Ошибка при удалении группы");
  while($row = mysqli_fetch_array($result))
  {
    // Уничтожаем фотографии
    $query = "SELECT big, small, id_photo FROM photo
              WHERE id_photo=".$row['id_photo'];
    $pct = mysqli_query($dbcnx, $query);
    if($pct)
    {
      if(mysqli_num_rows($pct)>0)
      {
        $photo = mysqli_fetch_array($pct);
        if(file_exists("../".$photo['big']) && $photo['big'] != "-") unlink("../".$photo['big']);
        if(file_exists("../".$photo['small']) && $photo['small'] != "-") unlink("../".$photo['small']);
      }
    }
  }
  // Формируем SQL-запросы на удаление записей из таблиц photo и photocat
  $query_photo = "DELETE FROM photocat WHERE id_catalog=$id_catalog";
  $query_photocat = "DELETE FROM photo WHERE id_catalog=$id_catalog";
  if(mysqli_query($dbcnx, $query_photo) && mysqli_query($dbcnx, $query_photocat))
  {
    // Осуществляем автоматический переход к странице администрирования
    echo "<HTML><HEAD>
          <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?id_parent=$id_parent'>
          </HEAD>";
  }
?>