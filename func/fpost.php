
<?php
session_start();
require_once 'connect.php';

if(empty($_POST['text']) and empty($_FILES['img']['name']))
{
    $_SESSION['message'] = 'ничего отправить не получится....0.0';
    header('Location: ../index.php');
    exit();
}

$text = $_POST['text'];
$path =  $_FILES['img']['name'];

if((empty($text) or $text) and (empty($path) or $path) == null)
{
     $_SESSION['message'] = 'ничего отправить не получится....0.0';
    header('Location: ../index.php');
    exit();
    
}

if (empty($_FILES) or $path == null){
    mysqli_query ( $connect,"INSERT INTO `posts`(`id`, `text`, `img`, `date`, `likes`) VALUES ( NULL,'$text', NULL, NOW(), '0')");
    header('Location: ../index.php'); 
    exit();
}

$path = 'uploads/' . time() . $path;
    if (!move_uploaded_file($_FILES['img']['tmp_name'], '../' . $path)) {
        $_SESSION['message'] = 'Ошибка при загрузке сообщения';
        header('Location: ../index.php');
    }

mysqli_query ( $connect,"INSERT INTO `posts`(`id`, `text`, `img`, `date`, `likes`) VALUES ( NULL,'$text', '$path', NOW(), '0')");
header('Location: ../index.php'); 
exit();

?>