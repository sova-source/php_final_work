<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // Функция создающая уменьшенную копию фотографии $filename,
  // которая помещается в файл $smallimage
  // Уменьшенная копия имеет ширину и высоту равную
  // $w и $h пикселам, соответственно. Это максимально возможные значения.
  // Они будут пересчитаны чтобы сохранить пропорции масштабируемого изображения.
  function resizeimg($filename, $smallimage, $w, $h) 
  { 
    // Имя файла с масштабируемым изображением 
    $filename = "../".$filename; 
    // Имя файла с уменьшенной копией. 
    $smallimage = "../".$smallimage;     
    // определим коэффициент сжатия изображения, которое будем генерить 
    $ratio = $w/$h; 
    // получим размеры исходного изображения 
    $size_img = getimagesize($filename); 
    // Если размеры меньше, то масштабирования не нужно 
    if (($size_img[0]<$w) && ($size_img[1]<$h)) return true; 
    // получим коэффициент сжатия исходного изображения 
    $src_ratio=$size_img[0]/$size_img[1]; 

    // Здесь вычисляем размеры уменьшенной копии, чтобы при масштабировании сохранились 
    // пропорции исходного изображения 
    if ($ratio<$src_ratio) 
    { 
      $h = $w/$src_ratio; 
    } 
    else 
    { 
      $w = $h*$src_ratio; 
    } 
    // создадим пустое изображение по заданным размерам 
    $dest_img = imagecreatetruecolor($w, $h);   
    $white = imagecolorallocate($dest_img, 255, 255, 255);        
    if ($size_img[2]==2)  $src_img = imagecreatefromjpeg($filename);                       
    else if ($size_img[2]==1) $src_img = imagecreatefromgif($filename);                       
    else if ($size_img[2]==3) $src_img = imagecreatefrompng($filename); 

    // масштабируем изображение     функцией imagecopyresampled() 
    // $dest_img - уменьшенная копия 
    // $src_img - исходной изображение 
    // $w - ширина уменьшенной копии 
    // $h - высота уменьшенной копии         
    // $size_img[0] - ширина исходного изображения 
    // $size_img[1] - высота исходного изображения 
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);                 
    // сохраняем уменьшенную копию в файл 
    if ($size_img[2]==2)  imagejpeg($dest_img, $smallimage);                       
    else if ($size_img[2]==1) imagegif($dest_img, $smallimage);                       
    else if ($size_img[2]==3) imagepng($dest_img, $smallimage); 
    // чистим память от созданных изображений 
    imagedestroy($dest_img); 
    imagedestroy($src_img); 
    return true;          
  }   
?>