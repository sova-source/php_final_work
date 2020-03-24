<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Устанавливаем соединение с базой данных
  require_once ("../config.php");
  include "util.php";    
  // Проверим - достаточно ли информации для занесения в базу данных
  if(empty($_POST['name'])) links($_POST['id_catalog'],"Отсутствует название фотографии");
  if(empty($_POST['pos'])) links($_POST['id_catalog'],"Не введена позиция фотографии");
  // Проверяем скрыта или нет фотография
  if($_POST['hide'] == "on") $showhide = "show";
  else $showhide = "hide";
  // Заменяем одинарные кавычки обратными
  $_POST['name'] = str_replace("'","`",$_POST['name']);
  // Инициализируем временные переменные
  $small = "";
  $big = "";
  ///////////////////////////////////////////////////////////////////////
  // Блок удаления изображений
  ///////////////////////////////////////////////////////////////////////
  // Если редактор в поле для изображения передаёт символ "-" 
  // или другую фотографию, следует уничтожить предыдущую
  if (!empty($_FILES['image']['tmp_name']))
  {
    $query = "SELECT small, big, id_photo FROM photo
              WHERE id_photo=".$_POST['id_photo'];
    $pct = mysqli_query($dbcnx, $query);
    if(!$pct) links($_POST['id_catalog'],
                    "Ошибка обращения к базе данных"); 
    if(mysqli_num_rows($pct)>0)
    {
      $photo = mysqli_fetch_array($pct);
      // Если малое изображение существует - уничтожаем его
      if(file_exists("../".$photo['small']) && $photo['small'] != "-")
         unlink("../".$photo['small']);
      // Если увеличенное изображение существует - уничтожаем его
      if(file_exists("../".$photo['big']) && $photo['big'] != "-")
         unlink("../".$photo['big']);
    }
    $small = "small = '-',";
    $big = "big = '-',";
  }
  //////////////////////////////////////////////////////////////////////////////
  // Блок загрузки изображений
  //////////////////////////////////////////////////////////////////////////////
  // Если поле выбора изображения не
  // пустое - копируем его из временного каталога в каталог /files
  if (!empty($_FILES['image']['tmp_name']))
  {
    // Определяем расширение файла
    $ext = strrchr($_FILES['image']['name'], "."); 
    // Формируем путь к файлу    
    $image = "files/".date("YmdHis",time())."$ext";
    $smallimage = "files/".date("YmdHis",time())."_s$ext";  
    // Перемещаем файл из временной директории сервера в
    // директорию /files Web-приложения
    if (copy($_FILES['image']['tmp_name'], "../".$image))
    {
      // Уничтожаем файл во временной директории
      unlink($_FILES['image']['tmp_name']);
      // Изменяем права доступа к файлу
      chmod("../".$image, 0644);
      $big = " big = '$image',";
    }
    // Вызываем функцию resizeimg(), создающую уменьшенную копию фотографии
    // $image и помещающую её в файл $smallimage
    if(!resizeimg($image, $smallimage, 133, 100)) links($_POST['id_catalog'],"Ошибка при создании уменьшенной копии изображения с помощью GDLib");
    $small = " small = '$smallimage',";
  }
  // Формируем SQL-запрос на исправление позиции
  $query = "UPDATE photo SET name = '".$_POST['name']."',
                             pos=".$_POST['pos'].",
                             $small
                             $big
                             hide='$showhide' 
            WHERE id_photo=".$_POST['id_photo'];
  if(mysqli_query($dbcnx, $query))
  {
    echo "<HTML><HEAD>
          <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?id_parent=".$_POST['id_catalog']."'>
          </HEAD></HTML>";
  } else links($_POST['id_catalog'],"Ошибка при исправлении фотографии");
  // Небольшая вспомогательная функция для вывода сообщений в окно браузера
  function links($id_catalog,$msg)
  {
    echo "<p>".$msg."</p>";
    echo "<p><a href=# onClick='history.back()'>Вернуться к правке фотографии</a></p>";
    echo "<p><a href=index.php?id_parent=$id_catalog>Администрирование каталога продукции</a></p>";
    exit();
  }
?>