<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Устанавливаем соединение с базой данных
  require_once ("../config.php");
  if($title == "") $titlepage=$title = "Добавление\nфотографии";
  $helppage='Заполните необходимые поля и нажмите кнопку "Добавить". Поля отмеченные * являются обязательными для заполнения.';
  include "../util/topadmin.php";  
  // Настраиваем управляющие переменные по умолчанию
  if(!isset($button)) $button = "Добавить";
  if(!isset($action)) $action = "addphoto.php";
  if(!isset($showhide)) $showhide = "checked";
  // Получаем параметры из строки запросов
  $id_catalog = $_GET['id_catalog'];
  $id_photo = $_GET['id_photo'];
  // Если позиция контактного блока не передана форме
  // определяем её из таблицы contacts
  if(!isset($pos))
  {
    $query = "SELECT MAX(pos) AS maxpos FROM photo
              WHERE id_catalog=$id_catalog";
    $maxpos = mysqli_query($dbcnx, $query);
    if($maxpos)
    {
      if(mysqli_num_rows($maxpos)>0) $pos = mysqli_data_seek($maxpos,0) + 1;
      else $pos = 1;
    } else $pos = 1;
  }
?>
<table><tr><td>
<p class=boxmenu><a class=menu href="index.php?id_catalog=<? echo $id_catalog; ?>&id_parent=<? echo $id_parent ?>">Вернуться в администрирование контактов</a></p>
</td></tr></table>
<form  enctype='multipart/form-data' action=<?php echo $action; ?> method=post>
<table>
<tr><td><p class=zag2>Добавьте тэги через пробел *</td><td><input size=61 class=input type=text name=name value='<?php echo $name; ?>'></td></tr>
<tr><td><p class=zag2>Изображение. *</td><td><input multiple class=input type=file name=image[]></td></tr>
<tr><td><p class=zag2>Позиция *</td><td><input class=input type=text name=pos value='<?php echo $pos; ?>'></td></tr>
<tr><td><p class=zag2>Отображать</td><td><input type=checkbox name=hide <?php echo $showhide; ?>></td></tr>
<tr><td></td><td><input class=button type=submit value=<?php echo $button; ?>></td></tr>
<input type=hidden name=id_catalog value=<?php echo $id_catalog; ?>>
<input type=hidden name=id_photo value=<?php echo $id_photo; ?>>
</table>
</form>
<?php
  include "../util/bottomadmin.php";  
?>