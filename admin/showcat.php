<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Осуществляем соединение с базой данных
  require_once("../config.php");
  // Формируем и выполняем SQL-запрос на сокрытие каталога
  $query = "UPDATE photocat SET hide='show' 
            WHERE id_catalog=".$_GET['id_catalog'];
  if(mysqli_query($dbcnx, $query))
  {
    // Осуществляем автоматический переход на главную страницу
    echo "<HTML><HEAD>
          <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?id_parent=".$_GET['id_parent']."'>
          </HEAD>";
  } else puterror("Ошибка при сокрытии каталога");
?>