<?php
function makeThumbnails($updir, $img, $id,$MaxWe=500,$MaxHe=500){
    $arr_image_details = getimagesize($img);
    $width = $arr_image_details[0];
    $height = $arr_image_details[1];
    $percent = 100;
    if($width > $MaxWe) $percent = floor(($MaxWe * 100) / $width);
    if(floor(($height * $percent)/100)>$MaxHe)
        $percent = (($MaxHe * 100) / $height);
    if($width > $height) {
        $newWidth=$MaxWe;
        $newHeight=round(($height*$percent)/100);
    }else{
        $newWidth=round(($width*$percent)/100);
        $newHeight=$MaxHe;
    }
    $imgt=false;
    $imgcreatefrom=false;
    if ($arr_image_details[2] == 1) {
        $imgt = "ImageGIF";
        $imgcreatefrom = "ImageCreateFromGIF";
    }
    if ($arr_image_details[2] == 2) {
        $imgt = "ImageJPEG";
        $imgcreatefrom = "ImageCreateFromJPEG";
    }
    if ($arr_image_details[2] == 3) {
        $imgt = "ImagePNG";
        $imgcreatefrom = "ImageCreateFromPNG";
    }
    if ($imgt) {
        $old_image = $imgcreatefrom($img);
        $new_image = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $imgt($new_image, $updir."".$id);
        return;
    }
}
