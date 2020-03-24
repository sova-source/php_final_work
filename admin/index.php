<?php
include "session.php";
  Error_Reporting(E_ALL & ~E_NOTICE);
  $title = $titlepage = "Управление\nФотогалереей";
  // Устанавливаем соединение с базой данных
  require_once ("../config.php");
  // Выводим шапку страницы
  include "../util/topadmin.php";
  
  // Извлекаем из строки запроса параметр id_parent
  $id_parent = $_GET['id_parent'];
  if(empty($id_parent)) $id_parent = 0;
?>
<table border="0" width="100%">
<tr><td>
  <table cellspacing="8" cellspacing="0" border=0><tr>
    <tr>
	
<div align=left>
<div  style="background-color:#D3D3D3; width:200px; padding:10px" >
<br /><?php echo'USER: '.$_SESSION['auth'];?><br>
<br /><?php if (!empty($_SESSION['auth'])){echo "<a href='../index.php?exit=1'>  EXIT </a>";} ?><br>
<br /><?php if (empty($_SESSION['auth'])){echo "<a href='admin/auth.php'> go to login page </a>";} ?><br>
<!--<br /><input type=submit name=button value=Enter><br> -->
</div>
</div>

<?php
  // Если текущий каталог не является корневым,
  // выводим ссылку для возврата в предыдущее меню
  if ($id_parent != 0)
    echo "<td ><p><a class=menu href=index.php?id_parent=0>Верхний уровень</a></p></td>";
  // Если каталог является корневым - выводим ссылку для добавления подкаталога
  else echo "<td><p><a class=menu href=addcatform.php?id_parent=0>Добавить группу фотографий</a></td>";
  ?>
  </tr></table>
  <table class=bodytable width="100%" border="1" cellpadding=5 cellspacing=0 bordercolorlight=gray bordercolordark=white>     
  <?
  // Формируем и выполняем SQL-запрос, извлекающий
  // список групп фотографий
  $query = "SELECT * FROM photocat 
            WHERE id_parent=$id_parent 
            ORDER BY pos";
  $ctg = mysqli_query($dbcnx, $query);
  if (!$ctg) puterror("Ошибка при обращении к Фотогалерее");
  // Если в таблице catalog присутствует хотя бы одна
  // группа фотографий - выводим их в таблице
  if(mysqli_num_rows($ctg)>0)
  {
    // Выводим заголовок таблицы групп фотографий
    echo "<tr class='tableheadercat'>
            <td align=center><p class=zagtable>Название группы фотографий</td>
            <td width=20 align=center><p class=zagtable>Поз.</td>
            <td colspan=3 align=center><p class=zagtable>Действия</td>
          </tr>";
    while($cat = mysqli_fetch_array($ctg))
    {
      // Выясняем скрыт каталог или нет
      $stylerow="";
      if($cat['hide'] == "hide")
      {
        $strhide = "<a href=showcat.php?id_catalog=".$cat['id_catalog']."&id_parent=$id_parent>Отобразить</a>";
        $stylerow="class='hiddenrow'";
      } else $strhide = "<a href=hidecat.php?id_catalog=".$cat['id_catalog']."&id_parent=$id_parent>Скрыть</a>";
      // Выводим список каталогов
      echo "<tr $stylerow >
              <td><p><a href=index.php?id_parent=".$cat['id_catalog'].">".$cat['name']."</a></td>
              <td><p>".$cat['pos']."</td>
              <td align=center><p>$strhide</td>
              <td align=center><p><a href=editcatform.php?id_catalog=".$cat['id_catalog']."&id_parent=$id_parent>Исправить</a></td>
              <td align=center><p><a href=delcat.php?id_catalog=".$cat['id_catalog']."&id_parent=$id_parent>Удалить</a></td>
            </tr>";
    }
  }
?>
</table>
</td></tr>
<tr><td>
<?php
  // Выводим содержимое групп фотографий, если текущий каталог не является
  // корневым
  if ($id_parent != 0) include "photos.php";
?>
</td></tr></table>
<?php
  include "../util/bottomadmin.php";  
?>