<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Устанавливаем соединение с базой данных
  require_once ("../config.php");
  // Настраиваем HTML-форму для исправления позиции с фотографией
  $titlepage = "Редактирование фотографической позиции";
  $button = "Исправить";
  $action = "editphoto.php";
  $id_photo = $_GET['id_photo'];
  // Формируем и выполняем SQL-запрос
  $query = "SELECT * FROM photo 
            WHERE id_photo = $id_photo";
  $pht = mysqli_query($dbcnx, $query);
  if ($pht)
  {
    $photo = mysqli_fetch_array($pht);
    $name = $photo['name'];
    $pos = $photo['pos'];
    if($photo['hide'] == "show") $showhide = "checked";
    else $showhide = "";
    include "addphotoform.php";
  }
?>