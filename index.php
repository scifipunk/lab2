
<?php
session_start();
require_once 'func/connect.php'; 

?>


<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вторая лаба</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <article class="columns-2">
    <h1><br>добро пожаловать в тред</br></h1>
    <figure class = "block"><form action="func/fpost.php" method="POST" enctype="multipart/form-data">
        
        <textarea name="text" cols="30" rows="10" placeholder="Ваш текст"></textarea>
        <div class= "imgblock"><label>место для фоточки</label>
        <br></br>
        <input type="file" name="img"></div>
        <button class="publisher" type="submit">Опубликовать</button>
        
        <?php
            if ($_SESSION['message']) {
                echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
            }
            unset($_SESSION['message']);
        ?>
    </form></figure>
    <figure><?php
    $postsql = mysqli_query($connect, "SELECT * FROM `posts` ORDER BY `date` DESC LIMIT 10");
    while($textmsg = mysqli_fetch_assoc($postsql)){
        echo '<br>';
        print "<p class='comments'>{$textmsg['date']}
        <br></br>
        <img src='{$textmsg['img']}' width='50'>
        <br></br>
        {$textmsg['text']} 
        <form action='func/flike.php' method='POST'>
        <p1 class='butlike'><button name='like' style='color:green;  ' type='submit'value='{$textmsg['id']}'> bump<img src='/bumpsage/Emoticon_troll.webp' width='25'> </button>
        {$textmsg['likes']}
        <button name='dislike' style='color:red;  ' type='submit' value='{$textmsg['id']}'> sage <img src='/bumpsage/Emoticon_sick.webp' width='25'></button></form></p1></p>";
        
    }
    
    ?></figure></article>
</body>
</html>