<?php
session_start();
error_reporting(E_ALL);
define('MAX_SIZE', 5000000);
define('DANGER_NOT_IMAGE','Файл не является изображением');
define('ERROR','Произошла ошибка');
define('DANGER_SIZE_EXCEEDED','Превышен максимальный размер файла');
define('SUCCESS','Изображение успешно загружено');
$folder=false;
$result=false;
//Явно не лучший скрипт для создания превью. Но работает
include 'func_make_thumb.php';          
//Передача результата из сессии в переменную 
if (isset($_SESSION['result']))
{
    $result=$_SESSION['result'];
    unset($_SESSION['result']);
}
//Проверкa существования папки (если её нет - создаем)
if (!is_dir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'img'))
{
    mkdir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'img');
    mkdir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'img_thumbnails');
    header("Location: index.php");
    die();
}
else $folder=opendir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'img');
if ((isset($_FILES['images'])))
{           //Проверка типа загружаемого файла
    $type_array=getimagesize($_FILES['images']['tmp_name']);
    $_SESSION['type']=$type_array[2];
    if (($type_array[2])===(1)||($type_array[2])===(2)||($type_array[2])===(3))
        {   //Проверка размера файла
            if (($_FILES['images']['size'])<MAX_SIZE)
            {
                if (($_FILES['images']['error'])===0)   //На проверку всех ошибок не хватило времени
                {
                    move_uploaded_file($_FILES['images']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/'.'img/'.$_FILES['images']['name']);
                    makeThumbnails($_SERVER['DOCUMENT_ROOT'].'/'.'img_thumbnails/',$_SERVER['DOCUMENT_ROOT'].'/'.'img/'.$_FILES['images']['name'],$_FILES['images']['name']);
                    $_SESSION['result']=SUCCESS;
                    header("Location: index.php");
                    die();
                }
                else $_SESSION['result']=ERROR;
                header("Location: index.php");
                die();
            }
            else $_SESSION['result']=DANGER_SIZE_EXCEEDED;
            header("Location: index.php");
            die();
        }
    else $_SESSION['result']=DANGER_NOT_IMAGE;
    header("Location: index.php");
    die();
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans:700|Roboto+Mono:300' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 info">
            <p>Техническая информация</p>
            <?php
            if ($folder!=false)
            {
                echo "Дескриптор каталога {$folder}<br>";
                echo "Файлы:<br>";
                while ($file=readdir($folder))
                {
                    if (($file==".")||($file=="..")) continue;
                    echo "$file<br>";
                    $files_array[]=$file;
                }
                closedir($folder);
            }
            ?>
        </div>
        <div class="col-md-6">
            <h1 class="title">Image Gallery (Pre-Alpha Version)</h1>
            <?php
            if (isset($files_array))
            {
                foreach ($files_array as $files)
                {
                    echo "<div class=\"col-md-3\">
                          <a class=\"thumbnail\" href=\"img/{$files}\" target=\"_blank\"><img src=\"img_thumbnails/{$files}\"></a>
                      </div>";
                }
            }
            ?>
        </div>
        <div class="col-md-3 info">
            <p>Техническая информация</p>
            <?php
            var_dump($_FILES);
            var_dump($_SESSION);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2>Загрузка файлов в галерею</h2>
            <h4>Максимальный размер файла <?php echo MAX_SIZE/1000000?>Mb</h4>
            <form class="form-inline" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="images">
                </div>
                <button type="submit" class="btn btn-success">Загрузить</button>
            </form>
            <h4><?php echo $result?></h4>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
</body>
</html>
