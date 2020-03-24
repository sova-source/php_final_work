<?php
include "session.php";
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Устанавливаем соединение с базой данных
  require_once ("../config.php");
  include "util.php";  
  // Проверим - достаточно ли информации для занесения в базу данных
  if(empty($_POST['name'])) links($_POST['id_catalog'], "Отсутствует название фотографии");
  if(empty($_POST['pos'])) links($_POST['id_catalog'], "Не введена позиция фотографии");
  // Проверяем скрыта или нет фотография
  if($_POST['hide'] == "on") $showhide = "show";
  else $showhide = "hide";
  $count = $_FILES['image']['tmp_name'];
  $i=0;
  foreach ($count as $value){
  // Если во временном каталоге имеется соответствующий полю image
  // файл, копируем его из временного каталога в каталог /files
  if (!empty($_FILES['image']['tmp_name'][$i]))
  {
    // Определяем расширение файла
    $ext = strrchr($_FILES['image']['name'][$i], "."); 
    // Формируем путь к файлу    
    $image = "files/".date("YmdHisu",time()).$i."$ext";
    $smallimage = "files/".date("YmdHisu",time()).$i."_s$ext";
	
    // Перемещаем файл из временной директории сервера в
    // директорию /files Web-приложения
    if (copy($_FILES['image']['tmp_name'][$i], "../".$image))
    {
      // Уничтожаем файл во временной директории
      unlink($_FILES['image']['tmp_name'][$i]);
      // Изменяем права доступа к файлу
      chmod("../".$image, 0644);
    }
  } else links($_POST['id_catalog'], "Фотография не передана на сервер");
  // Вызываем функцию resizeimg(), создающую уменьшенную копию фотографии
  // $image и помещающую её в файл $smallimage
  if(!resizeimg($image, $smallimage, 133, 100))
    links($_POST['id_catalog'], "Ошибка при создании уменьшенной копии изображения с помощью библиотеки GDLib");
  // Заменяем одинарные кавычки обратными
  $_POST['name'] = str_replace("'", "`", $_POST['name']);
  // Формируем запрос
  $query = "INSERT INTO photo VALUES (NULL,
                                     '".$_POST['name']."',
                                     '$smallimage',
                                     '$image',
                                     '$showhide',
                                     ".$_POST['pos'].",
                                     ".$_POST['id_catalog'].",
									 '$session_id_user',0)";
	mysqli_query($dbcnx, $query);
  $i++;
  }
  
  if(!mysqli_error($dbcnx))
  {
    // Осуществляем автоматический переход на главную страницу администрирования
    echo "<HTML><HEAD>
          <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?id_parent=".$_POST['id_catalog']."'>
          </HEAD>";

  } else links($_POST['id_catalog'], "Ошибка при добавлении новой записи в таблицу фотографий");
  // Небольшая вспомогательная функция для вывода сообщений в окно браузера
  function links($id_catalog,$msg)
  {
    echo "<p>".$msg."</p>";
    echo "<p><a href=# onClick='history.back()'>Вернуться к правке фотографии</a></p>";
    echo "<p><a href=index.php?id_parent=$id_catalog>Администрирование фотогалереи</a></p>";
    exit();
  }
?>