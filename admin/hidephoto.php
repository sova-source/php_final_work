<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Осуществляем соединение с базой данных
  require_once("../config.php");
  // Формируем и выполняем SQL-запрос на сокрытие каталога
  $query = "UPDATE photo SET hide='hide' 
            WHERE id_photo=".$_GET['id_photo'];
  if(mysqli_query($dbcnx, $query))
  {
    echo "<HTML><HEAD>
          <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?id_parent=".$_GET['id_catalog']."'>
          </HEAD>";
  } else puterror("Ошибка при сокрытии контактной информации");
?>