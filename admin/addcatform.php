<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  if($titlepage == "") $titlepage=$title = "Добавление группы фотографий";
  include "../util/topadmin.php";  
  // Устанавливаем соединение с базой данных
  require_once ("../config.php");
  // Устанавливаем управляющие переменные формы по умолчанию
  if(!isset($title)) $title = "Добавление нового каталога";
  if(!isset($button)) $button = "Добавить";
  if(!isset($action)) $action = "addcat.php";
  if(!isset($showhide)) $showhide = "checked";
  // Проверяем переданные параметры
  $id_catalog = $_GET['id_catalog'];
  $id_parent = $_GET['id_parent'];
  // Если позиция каталога не передана
  // назначаем каталогу последнюю позицию
  if(!isset($pos))
  {
    $query = "SELECT MAX(pos) FROM photocat
              WHERE id_parent=".$_GET['id_parent'];
    $num = mysqli_query($dbcnx, $query);
    if($num)
    {
      if(mysqli_num_rows($num)>0) $pos = mysqli_data_seek($num,0) + 1;
      else $pos = 1;
    } else $pos = 1;
  }
?>
<table><tr><td>
<p class=boxmenu><a class=menu href="index.php?id_catalog=<? echo $id_catalog; ?>&id_parent=<? echo $id_parent ?>">Вернуться на страницу администрирования</a></p>
</td></tr></table>
<form enctype='multipart/form-data' action=<?php echo $action; ?> method=post>
<table>
<tr><td><p class=zag2>Название</td><td><input style="font-weight: bold" size=61 class=input type=text name=name value='<?php echo $name; ?>'></td></tr>
<tr><td><p class=zag2>Позиция</td><td><input size=5 class=input type=text name=pos value='<?php echo $pos; ?>'></td></tr>
<tr><td><p class=zag2>Отображать</td><td><input type=checkbox name=hide <?php echo $showhide; ?>></td></tr>
<tr><td></td><td><input class=button type=submit value=<?php echo $button; ?>></td></tr>
<input type=hidden name=id_catalog value=<?php echo $id_catalog; ?>>
<input type=hidden name=id_parent value=<?php echo $id_parent; ?>>
</table>
</form>
<?php
  include "../util/bottomadmin.php";  
?>