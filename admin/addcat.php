<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Устанавливаем соединение с базой данных
  require_once ("../config.php");
  // Проверим достаточно ли информации для занесения в базу данных
  // нового каталога
  if(empty($_POST['name'])) links($_POST['id_catalog'],
                                  "Отсутствует название каталога");
  if(empty($_POST['pos'])) links($_POST['id_catalog'],
                                    "Не введена позиция каталога");
  // Определяем скрыт каталог (hide) или доступен (show)
  if($_POST['hide'] == "on") $showhide = "show";
  else $showhide = "hide";
  // Заменяем одинарные кавычки обратными
  $_POST['name'] = str_replace("'","`",$_POST['name']);
  $_POST['description'] = str_replace("'","`",$_POST['description']);
  // Формируем и выполняем SQL-запрос на добавление нового каталога
  $query = "INSERT INTO photocat VALUES (NULL,
                                       '".$_POST['name']."',
                                       '".$_POST['description']."',
                                       ".$_POST['pos'].",
                                       '$showhide',
                                       ".$_POST['id_parent'].")";
  if(mysqli_query($dbcnx, $query))
  {
    // Автоматически осуществляем переход на главную страницу
    // администрирования
    echo "<HTML><HEAD>
          <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?id_parent=".$_POST['id_parent']."'>
          </HEAD>";

  } else links($_POST['id_catalog'], "Ошибка при добавлении каталога<br> $query");
  // Функция вывода предупреждения и ссылок возврата
  function links($id_catalog, $msg)
  {
    echo "<p>".$msg."</p>";
    echo "<p><a href=# onClick='history.back()'>
              Вернуться к правке каталога</a></p>";
    echo "<p><a href=index.php?id_parent=$id_catalog>
              Администрирование каталога</a></p>";
    exit();
  }
?>