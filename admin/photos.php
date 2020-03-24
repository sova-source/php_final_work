<?php
include 'session.php';

  Error_Reporting(E_ALL & ~E_NOTICE);
?>
  <table cellspacing="8"><tr><td>
  <a class="menu" href=addphotoform.php?id_catalog=<? echo $id_parent ?>>Добавить фотографию</a>
  </td></tr></table>
  
  <table class=bodytable width="100%" border="1" cellpadding=5 cellspacing=0 bordercolorlight=gray bordercolordark=white>     
<?php
  // Выбираем из базы данных фотографии
  $query = "SELECT * FROM photo 
            WHERE id_catalog=$id_parent AND
			id_user = $session_id_user
            ORDER BY pos";
  $prt = mysqli_query($dbcnx, $query);
  if(!$prt)
  {
    echo "error : ".mysqli_error()."<br>";
    echo $query;
    puterror("Ошибка при обращении к блоку Фотогалерея");
  }
  // Если в текущей группе фотографии имеется хотя бы одна
  // фотография - формируем таблицу с фотографиями
  if(mysqli_num_rows($prt) > 0)
  {
    // Вспомогательная переменная для вывода
    // фотографий по 3 штуки в строке
    $td == 0;
    // Выводим заголовок таблицы
    while($par = mysqli_fetch_array($prt))
    {
      // Выясняем скрыта фотография или нет
      $styletable="";
      if($par['hide'] == "hide")
      {
         $showhide = "<a href=showphoto.php?id_photo=".$par['id_photo']."&id_catalog=$id_parent>Отобразить</a>";
         $styletable="class='hiddenrow'";
      } else $showhide = "<a href=hidephoto.php?id_photo=".$par['id_photo']."&id_catalog=$id_parent>Скрыть</a>";
      // Формируем переменную $image, несущую ответственность за вывод
      // уменьшенного изображения, являющегося ссылкой на увеличенное
      if(!empty($par['small']) &&
         $par['small']!="-" &&
         file_exists("../".$par['small']))
      {
	    $size = getimagesize("../".$par['big']);
        $image = "<a href=# onclick=\"show_img('".$par['big']."',".$size[0].",".$size[1].",'true'); return false \" ><img src=../".$par['small']." border=0 vspace=3></a>";
      }
      else $small = "Нет";
      // Если значение временной переменной равно 0
      // выводим тэг начала строки таблицы <tr>
      if ($td == 0) echo "<tr>";
      // Выводим фотографию
      echo "<td $styletable><table border=0 width=100%><tr align=center>
              <td colspan=2><p><b>".$par['name']."</b></p></td></tr>
              <tr>
                 <td>$image</td>
                 <td align=center>
                 <p>Поз:".$par['pos']."
                 <p>$showhide<br>
                 <a href=editphotoform.php?id_photo=".$par['id_photo']."&id_catalog=$id_parent>Исправить</a><br>
                 <a href=delphoto.php?id_photo=".$par['id_photo']."&id_catalog=$id_parent>Удалить</a><br>
                 <a href=imgup.php?id_photo=".$par['id_photo']."&id_catalog=$id_parent>Вверх</a><br>
                 <a href=imgdown.php?id_photo=".$par['id_photo']."&id_catalog=$id_parent>Вниз</a></td>
            </tr></table></td>";
      // Увеличиваем значение временной переменной $td
      $td++;
      // Если временная переменная $td принимает значение
      // равное 3, следовательно строка завершена, и необходимо
      // вывести завершающий тэг </tr>, а значение самой
      // переменной обнулить
      if ($td == 3)
      {
        echo "</tr>";
        $td = 0;
      }         
    }
  }
?>
  </table><br><br>