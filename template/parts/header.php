<?php

include($_SERVER['DOCUMENT_ROOT'] . '/include/main_menu.php');
include($_SERVER['DOCUMENT_ROOT'] . '/include/functions.php');

$fileDB = $_SERVER['DOCUMENT_ROOT'] . '/db_success.txt';
if (!file_exists($fileDB)) {
    include($_SERVER['DOCUMENT_ROOT'] . '/create_db.php');
}

$link = @connectDB();
if (!$link) {
    die('Ошибка соединения: ' . mysqli_connect_errno() . '. База данных не найдена');
}

$sessionName = session_name();
session_start();
$cookSessID = session_id();

$message = '';
$successLogin = null;
$getLoginValue = $_GET['login'] ?? '';
$getLoginValue = htmlspecialchars($getLoginValue);
$name = $_POST['login'] ?? '';
$name = $_COOKIE['loginCookie'] ?? $name;
$name = cleanData($name);
$userName = $_COOKIE['loginCookie'] ?? 'Гость';
$userName = cleanData($userName);
$outPass = $_POST['password'] ?? '';
$outPass = cleanData($outPass);
$mes_id = requestMessssage();
$mes_id = cleanData($mes_id);
$currentItem = pageTitle($mainMenu);
$postContent = $_POST['content'] ?? '';
$postContent = cleanData($postContent);
$postTitle = $_POST['title'] ?? '';
$postTitle = cleanData($postTitle);
$postTopic = $_POST['topic'] ?? '';
$postTopic = cleanData($postTopic);
$postRecipient = $_POST['recipient'] ?? '';
$postRecipient = cleanData($postRecipient);

$contentFile = showFileName($currentItem['path']);
$secretFile = $currentItem['private'];

if (isset($_POST['submit']) && $_POST['submit'] == 'Войти'){
    $successLogin = false;
    if (!empty($_POST['login']) && !empty($_POST['password'])) {
        $row = userValidationDB($name, $outPass);
        if (!empty($row)) {
            $name = createSession($sessionName, $cookSessID, $row);
            $userName = $row['login'];
            $successLogin = true;
        } else {
            $message = 'Неверный логин или пароль';
        }
    } else {
        $message = 'Поля "login" и "password" должны быть заполнены';
    }
} elseif (isset($_POST['submit']) && $_POST['submit'] == 'Выйти') {
    setcookie('loginCookie', null, time() - 1000);
    deleteSession($sessionName, $cookSessID);
    header('Location: /?login=yes');
} elseif (isset($_POST['submit']) && $_POST['submit'] == 'Отправить') {

    if (!empty($_POST['content']) && !empty($_POST['title'])) {
        insertMessageToDB($postRecipient, $postTitle, $postContent, $postTopic);
        header('Location: /posts/');
    } else {
        $message = 'Поля "Заголовок" и "Текст сообщения" должны быть заполнены';
    }
}

if (isset($_SESSION['authorized'])) {
    $currentTime = time();
    if ($_SESSION['expire'] > $currentTime) {
        setExpireSesCook($sessionName, $cookSessID);
        $successLogin = true;
        $userId = cleanData($_SESSION['user']['id']);

        if ($currentItem['path'] == '/posts/') {
            //Списки прочитанных и непрочитанных писем
            $unreadMessages = getUnreadMessages($userId);
            $readMessages = getReadMessages($userId);
        } elseif ($currentItem['path'] == '/posts/view_message/') {
            //Получение информации по выбранному письму
            $oneMessage = getMessage($mes_id, $userId);
        }
        //запрос для получения пользователей имеющих право писать
        $usersCollection = getWriters();
        //запрос для получения разделов и цветов
        $topicsCollection = getTopics();
    } else {
        deleteSession($sessionName, $cookSessID);
        $successLogin = null;
    }
} elseif (empty($_SESSION) && (!isset($_GET['login']) || $_GET['login'] != 'yes')) {
    $successLogin = null;
}

if ($successLogin != true && $secretFile == true) {
    header('Location: /?login=yes');
}

mysqli_close(connectDB());
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="/styles.css" rel="stylesheet">
    <title>Project - ведение списков</title>
</head>

<body>
    <div class="header">
        <div class="logo"><img src="/i/logo.png" width="68" height="23" alt="Project"></div>
        <div class="clearfix"></div>
    </div>
    <div class="clear">
        <?php createMenu($mainMenu, 'cmp_function');?>
    </div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="left-collum-index">
            <?php switch ($currentItem['title']) :
                case false :?>
                    <h1>Объект не найден!</h1>
                    <p>Запрашиваемый ресурс не найден. Адрес на ресурс неверный или устарел. </p>
                    <?php break;?>
                <?php default:?>
                    <h1><?= $currentItem['title'];?></h1>
                    <?php include($_SERVER['DOCUMENT_ROOT'] . '/template/content/'. $contentFile);?>
                    <?php break;?>
            <?php endswitch;?>
            </td>
            <td class="right-collum-index">
                <div class="project-folders-menu">
                    <ul class="project-folders-v">
                        <li class="project-folders-v-active"><a href="/?login=yes">
                            <?= isset($_SESSION['authorized']) ? 'Выход' : 'Авторизация';?></a></li>
                        <li><a href="#">Регистрация</a></li>
                        <li><a href="#">Забыли пароль?</a></li>
                    </ul>
                <div class="clearfix"></div>
                </div>
                <div class="user-name">
                    <p><?= $userName;?></p>
                </div>
                <div class="index-auth">
                <?php if ($successLogin) :?>
                    <?php include($_SERVER['DOCUMENT_ROOT'] . '/template/info_message/success.php');?>
                <?php elseif ($successLogin === false) :?>
                    <?php include($_SERVER['DOCUMENT_ROOT'] . '/template/info_message/error.php');?>
                <?php endif;?>
                <?php if (isset($_SESSION['authorized'])) :?>
                    <?php include($_SERVER['DOCUMENT_ROOT'] . '/template/parts/logout_form.php');?>
                <?php else :?>
                    <?php if ($getLoginValue == 'yes') :?>
                        <?php include($_SERVER['DOCUMENT_ROOT'] . '/template/parts/login_form.php');?>
                    <?php endif;?>
                <?php endif;?>
                </div>
            </td>
        </tr>
    </table>
