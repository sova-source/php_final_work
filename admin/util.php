<?php
  Error_Reporting(E_ALL & ~E_NOTICE);
  // ������� ��������� ����������� ����� ���������� $filename,
  // ������� ���������� � ���� $smallimage
  // ����������� ����� ����� ������ � ������ ������
  // $w � $h ��������, ��������������. ��� ����������� ��������� ��������.
  // ��� ����� ����������� ����� ��������� ��������� ��������������� �����������.
  function resizeimg($filename, $smallimage, $w, $h) 
  { 
    // ��� ����� � �������������� ������������ 
    $filename = "../".$filename; 
    // ��� ����� � ����������� ������. 
    $smallimage = "../".$smallimage;     
    // ��������� ����������� ������ �����������, ������� ����� �������� 
    $ratio = $w/$h; 
    // ������� ������� ��������� ����������� 
    $size_img = getimagesize($filename); 
    // ���� ������� ������, �� ��������������� �� ����� 
    if (($size_img[0]<$w) && ($size_img[1]<$h)) return true; 
    // ������� ����������� ������ ��������� ����������� 
    $src_ratio=$size_img[0]/$size_img[1]; 

    // ����� ��������� ������� ����������� �����, ����� ��� ��������������� ����������� 
    // ��������� ��������� ����������� 
    if ($ratio<$src_ratio) 
    { 
      $h = $w/$src_ratio; 
    } 
    else 
    { 
      $w = $h*$src_ratio; 
    } 
    // �������� ������ ����������� �� �������� �������� 
    $dest_img = imagecreatetruecolor($w, $h);   
    $white = imagecolorallocate($dest_img, 255, 255, 255);        
    if ($size_img[2]==2)  $src_img = imagecreatefromjpeg($filename);                       
    else if ($size_img[2]==1) $src_img = imagecreatefromgif($filename);                       
    else if ($size_img[2]==3) $src_img = imagecreatefrompng($filename); 

    // ������������ �����������     �������� imagecopyresampled() 
    // $dest_img - ����������� ����� 
    // $src_img - �������� ����������� 
    // $w - ������ ����������� ����� 
    // $h - ������ ����������� �����         
    // $size_img[0] - ������ ��������� ����������� 
    // $size_img[1] - ������ ��������� ����������� 
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);                 
    // ��������� ����������� ����� � ���� 
    if ($size_img[2]==2)  imagejpeg($dest_img, $smallimage);                       
    else if ($size_img[2]==1) imagegif($dest_img, $smallimage);                       
    else if ($size_img[2]==3) imagepng($dest_img, $smallimage); 
    // ������ ������ �� ��������� ����������� 
    imagedestroy($dest_img); 
    imagedestroy($src_img); 
    return true;          
  }   
?>