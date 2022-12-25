<?php
require_once 'connect.php';

if(isset($_POST['like']))
{
    $id_like = $_POST['like'];
    $sql = mysqli_query($connect, 'UPDATE posts SET likes = likes + 1 WHERE id = '.$id_like.'');
    header('Location: ../index.php');
    exit();
}
if(isset($_POST['dislike']))
{
    $id_dislike = $_POST['dislike'];
    
    $sql = mysqli_query($connect, 'UPDATE posts SET likes = likes - 1 WHERE id = '.$id_dislike.'');
    
}
header('Location: ../index.php');
exit();
?>