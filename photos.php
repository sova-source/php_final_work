<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // число фотографий в строке таблицы
  $numphoto = 3;
?>
  <table class=bodytable width="100%" border="1" cellpadding=5 cellspacing=0 bordercolorlight=gray bordercolordark=white>     
<?php
 
		  
  // Выбираем из базы данных фотографии
  if(!preg_match("|^[\d]+$|",$id_parent) && !empty($id_parent)) exit();
  $query = "SELECT * FROM photo 
            WHERE id_catalog = $id_parent AND
            hide = 'show' 
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
    // фотографий по 5 штуки в строке
    $td == 0;
    // Выводим заголовок таблицы
    while($par = mysqli_fetch_array($prt))
    {
      // Формируем переменную $image, несущую ответственность за вывод
      // уменьшенного изображения, являющегося ссылкой на увеличенное
      if(!empty($par['small']) &&
         $par['small']!="-" &&
         file_exists($par['small']))
      {
	    $size = getimagesize($par['big']);
        $image = "<a href=# onclick=\"show_img('".$par['big']."',".$size[0].",".$size[1]."); return false \" ><img src=".$par['small']." border=0 vspace=3></a>";
      }
      else $small = "Нет";
	  //==========================формируем ссылки на тэги==================================
	  
		$tags = explode (" ",$par['name']);
		
		$select = "SELECT name FROM userlist 
					WHERE id_user = ".$par['id_user'];
		$result = mysqli_query($dbcnx, $select);
		$user = mysqli_fetch_array($result);
	
	  //====================================================================================
      // Если значение временной переменной равно 0
      // выводим тэг начала строки таблицы <tr>
      if ($td == 0) echo "<tr>";
      // Выводим фотографию с тэгами
	  
    echo "<td><table border=0 width=100%><tr align=center><td><p><b>";     	  
	foreach ($tags as $value){echo "<a href=#>".' '.$value.' '."</a>";}	  		  
	echo "</b></p></td></tr>
              <tr align=center>
                 <td>$image</td>
				 <td>просмотрено: ".(string)$par['view_count']." раз<br> добавлено: ".$user[0]."</td>
				 
            </tr></table></td>";
      // Увеличиваем значение временной переменной $td
      $td++;
      // Если временная переменная $td принимает значение
      // равное 5, следовательно строка завершена, и необходимо
      // вывести завершающий тэг </tr>, а значение самой
      // переменной обнулить
      if ($td == $numphoto)
      {
        echo "</tr>";
        $td = 0;
      }         
    }
  }
?>
  </table><br><br>