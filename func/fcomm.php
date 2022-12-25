
<?php

require_once 'connect.php';

if(isset($_GET['comment']))
{
    if(isset($_GET['com']))
    {
        $post_id = $_GET['com'];
        $comment = $_GET['comment'];
        $id = 0;
        $sql = mysqli_query($connect, "INSERT INTO `comment` (`id`, `text`, `img`, `date`, `likes`) VALUES ( NULL, '$post_id', '$comment', NOW(), '0')");

    }
}
header('Location: ../index.php');
exit();
?>