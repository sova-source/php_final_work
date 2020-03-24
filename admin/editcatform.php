<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Устанавливаем соединение с базой данных
  require_once ("../config.php");
  $titlepage = "Редактирование группы контактов";
  $button = "Исправить";
  $action = "editcat.php";
  $query = "SELECT * FROM photocat 
            WHERE id_catalog=".$_GET['id_catalog'];
  $cat = mysqli_query($dbcnx, $query);
  if ($cat)
  {
    $catalog = mysqli_fetch_array($cat);
    $name = $catalog['name'];
    $description = $catalog['description'];
    $pos = $catalog['pos'];
  }
  include "addcatform.php";
?>