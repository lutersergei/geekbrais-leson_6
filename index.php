<?php
error_reporting(E_ALL);
//Добавить проверку существования папки
$folder=opendir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'img');
if (isset($_FILES['images']))
{
    move_uploaded_file($_FILES['images']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/'.'img/'.$_FILES['images']['name']);
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
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
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
        <div class="col-md-8">
            <h1 style="text-align: center">Gallery</h1>
            <?php
            if (isset($files_array))
            {
                foreach ($files_array as $files)
                {
                    echo "<div class=\"col-xs-6 col-md-3\">
                          <a class=\"thumbnail\" href=\"img/{$files}\" target=\"_blank\"><img src=\"img/{$files}\"></a>
                      </div>";
                }
            }
            ?>
        </div>
        <div class="col-md-2">
            <?php
            var_dump($_FILES);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h2>Загрузка файлов в галерею</h2>
            <form class="form-inline" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="images">
                </div>
                <button type="submit" class="btn btn-default">Загрузить</button>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
</body>
</html>
