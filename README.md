# ОТЧЁТ О ЛАБОРАТОРНОЙ РАБОТЕ №2
#### *По курсу "Основы Программирования"*
#### *Работу выполнил студент группы №3131 Баглаенко Е.Ю.*
#### [Ссылка на GitHub](https://github.com/scifipunk/lab2.git)

## Цель работы:
Разработать и реализовать клиент-серверную информационную систему, реализующую механизм CRUD
## Выполненные требования
* Возможность добавления постов в общую ленту
* Реагирования на посты (лайки и дизлайки)
* Публикация записей с прикреплением картинки
## Ход работы
### Пользовательский интерфейс
1. Форма отправки сообщения                        
   ![slide1](pictures/comment_section.png)
2. Чат пользователей                           
   ![slide2](pictures/comments.png)

### Пользовательский сценарий
1. Пользователь попадает на страницу index.php и вводит в текстовом поле сообщение, которое хочет оставить, при желании, добавляет изображение, или добавляет только его. После этого жмет на кнопку "Опубликовать" и его сообщение выводится на правой части экрана вместе с картинкой (если он ее добавлял).  
2. Пользователь видит пост,  который ему понравился/не понравился и нажимает кнопку Bump/Sage. Счетчик Bump'ов на данном посте увеличивается/уменьшается на 1.

### API сервера

В основе приложения использована клиент-серверная архитектура. Обмен данными между клиентом и сервером осуществляется с помощью HTTP POST запросов. В теле POST запроса отправки поста используется поле  'text', а в поле глобальной переменной FILES используются поля 'name' и 'tmp_name'. Для увеличения счётчика Bump'ов используется форма с POST запросом. В теле POST запроса реакции используются следующие поля: dislikes, likes.

### Хореография
1. *Отправка сообщения*. Принимается введенное сообщение и картинка. Если оба поля оказалось пустым, то сайт просит заполнить его. Иначе отправляется запрос на добавление сообщения в базу данных, так же туда добавляется название картинки, дата и  время написания сообщения. Затем происходит перенаправление на страницу index.php. Из базы данных выводится данное сообщение с датой и временем его написания, а также картинкой если она была добавлена. За этим постом закрепляется индивидуальный 'id'.
2. *Просмотр и оценка сообщений*. Кнопка 'bump'/'sage' вызывает отправление запроса в базу данных на изменение количества Bump'ов в посте с привязанным 'id'.

## Описание структуры базы данных
Браузерное приложение phpMyAdminДля используется для просмотра содержимого базы данных. Всего 5 столбцов:
1. "id" типа int с автоинкрементом для выдачи уникальных id всем сообщениям
2. "text" типа varchar для хранения текста поста
3. "img" типа varchar для хранения пути, по которому находится картинка
4. "date" типа datetime для хранения даты и времени создания сообщений
5. "likes" типа int для хранения количества Bump'ов


## Описание алгоритмов
1. Алгоритм отправки сообщения                        
![user_alg1](pictures/alg-setComment.png)                                         
2. Алгоритм оставления реакции                                                   
![user_alg1](pictures/alg-likeSubmit.png)                 


## Примеры HTTP запросов/ответов
![user_scen1](pictures/get_example1.png)
![user_scen2](pictures/get_example2.png)

## Значимые фрагменты кода
1. Функция отправки текста и картинки поста в базу данных
```
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
```
2. Функция вывода постов и кнопок 'bump'/'sage'
```
<?php
    $postsql = mysqli_query($connect, "SELECT * FROM `posts` ORDER BY `date` DESC LIMIT 100");
    
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
    ?>
```
1. Функция bump/sage
```
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
```