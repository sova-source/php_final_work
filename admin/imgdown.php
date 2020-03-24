<?php
  Error_Reporting(E_ALL & ~E_NOTICE);

  // Устанавливаем соединение с базой данных
  require_once("../config.php");

  // Проверяем передано ли число в параметре
  if(!preg_match("|^[\d]+$|",$_GET['id_catalog'])) exit("Недопустимый формат URL-запрос");
  if(!preg_match("|^[\d]+$|",$_GET['id_photo'])) exit("Недопустимый формат URL-запрос");

  // Извлекаем позицию текущего элемента
  $query = "SELECT pos FROM photo
            WHERE id_photo = $_GET[id_photo] AND
                  id_catalog = $_GET[id_catalog]
            LIMIT 1";
  $img = mysqli_query($dbcnx, $query);
  if(!$img) exit("Ошибка при извлечении позиции");
  if(mysqli_num_rows($img)) $pos_current = mysqli_data_seek($img, 0);
  // Извлекаем позицию следующего элемента
  $query = "SELECT id_photo, pos FROM photo
            WHERE pos > $pos_current AND
                  id_catalog = $_GET[id_catalog]
            ORDER BY pos
            LIMIT 1";
  $img = mysqli_query($dbcnx, $query);
  if(!$img) exit("Ошибка при извлечении позиции");
  if(mysqli_num_rows($img))
  {
    $next = mysqli_fetch_array($img);

    $query = "UPDATE photo SET pos = $next[pos] 
              WHERE id_photo = $_GET[id_photo] AND
                    id_catalog = $_GET[id_catalog]
            LIMIT 1";
    mysqli_query($dbcnx, $query);
    $query = "UPDATE photo SET pos = $pos_current
              WHERE id_photo = $next[id_photo] AND
                    id_catalog = $_GET[id_catalog]
            LIMIT 1";
    mysqli_query($dbcnx, $query);
  }
  // Если запрос выполнен удачно, осуществляем автоматический переход
  // на главную страницу администрирования
  echo "<HTML><HEAD>
        <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?id_parent=".htmlspecialchars($_GET['id_catalog'])."'>
        </HEAD></HTML>";
?>