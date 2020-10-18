<?php

//Функция условия сортировки многомерного массива $arrayMenu по значению поля 'sort' по возрастанию
function cmp_function($a, $b){
    return $a['sort'] > $b['sort'];
}

//Функция условия сортировки многомерного массива $arrayMenu по значению поля 'sort' по убыванию
function cmp_function_desc($a, $b){
    return $a['sort'] < $b['sort'];
}

//Функция сокращения длины строки, с заменой сокращенного участка на троеточие
function shortString($string, $length = 15)
{
    return mb_strimwidth($string, 0, $length, "...");
}

//Функция проверки соответствия запроса и пути в разделе меню
function checkItem($itemPath)
{
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === $itemPath;
}

//Функция
function requestMessssage()
{
    $queryGet = $_GET['mes_id'] ?? '';
    $queryGet = (int) $queryGet;
    return $queryGet;
}

//Функция создания главного меню с разным стилем оформления в заголовке и футере
function createMenu($items, $sortBy, $ulClass = 'main-menu')
{
    uasort($items, $sortBy);
    include($_SERVER['DOCUMENT_ROOT'] . '/template/parts/main_menu_ul.php');
}

//Функция отображения раздела
function pageTitle($items)
{
    foreach ($items as $item) {
        if (checkItem($item['path'])) {
            return $item;
        }
    }
    return false;
}

//Функция вывода имени файла текущего раздела
function showFileName($path)
{
    if ($path == '/') {
        return 'main.php';
    } else {
        $name = explode('/', $path);
        array_pop($name);
        $name = array_pop($name) . '.php';
        return $name;
    }
}


//Функция проверки соответствия пароля и логина в БД
function userValidationDB($name, $outPass)
{
    $sql = "SELECT * FROM users WHERE login = ('$name');";
    $result = doQuery($sql);
    $collectedData = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $collectedData = array_pop($collectedData);
    if (password_verify($outPass, $collectedData['password'])) {
        unset($collectedData['password']);
        return $collectedData;
    }
}



//Функция старта и обновления времени начала сессии и куки
function setExpireSesCook($sessionName, $cookSessID)
{
    $_SESSION['start'] = time();
    $_SESSION['expire'] = $_SESSION['start'] + (60 * 20);
    setcookie($sessionName, $cookSessID, time() + (60 * 20), '/');
    setcookie('loginCookie', $_SESSION['login'], time() + (60 * 60 * 24 * 31), '/');
}

//Функция создания сессии, сессионной куки и куки пользователя при успешной авторизации
function createSession($sessionName, $cookSessID, $row)
{
    $_SESSION['login'] = $row['login'];
    $_SESSION['authorized'] = true;
    $_SESSION['user'] = $row;
    $_SESSION['groups'] = null;

    $userId = cleanData($row['id']);
    $connect = connectDB();
    $sql = "SELECT groups.name as 'group_name', groups.description as 'group_description' FROM group_user
        LEFT JOIN groups ON group_user.group_id = groups.id
        WHERE group_user.user_id = ('$userId');";

    $result = doQuery($sql);
    $collectedData = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $_SESSION['groups'] = $collectedData;
    setExpireSesCook($sessionName, $cookSessID);
    return $_SESSION['login'];
}

//Функция удаления сессии и сессионной куки
function deleteSession($sessionName, $cookSessID)
{
    session_unset();
    session_destroy();
    setcookie($sessionName, $cookSessID, time() - 1000);
    $_SESSION = [];
}

//Функция ввыполнения запроса к БД
function doQuery($sql)
{
    if (mysqli_connect_errno()) {
        echo mysqli_connect_error();
    } else {
        $result = mysqli_query(connectDB(), $sql);
        return $result;
    }
}

//Функция подключения к БД по заданным параметрам
function connectDB($dbname = 'taskmandb')
{
    $host = 'localhost';
    $user = 'mysql';
    $password = '';
    static $db = null;

    if (null === $db) {
       $db = mysqli_connect($host, $user, $password, $dbname);
    }
    return $db;
}

//Функция получения данных из БД
function collectData($sql)
{
    $result = doQuery($sql);
    $collectedData = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $collectedData;
}

//Функция изменения сообщения на прочитанное
function readMessage($mes_id)
{
    $sql = "UPDATE messages SET unread=0 WHERE id=('$mes_id');";
    return doQuery($sql);
}

//Функция проверки прав на отправку писем пользователем
function isWriter()
{
    foreach ($_SESSION['groups'] as $value) {
        if (array_search('writer', $value)){
            return true;
        }
    }
    return false;
}

//Функция очистки входных данных от вредоносного кода
function cleanData($dirtyData)
{
    $clean = htmlspecialchars($dirtyData);
    $clean = mysqli_real_escape_string(connectDB(), $clean);
    return $clean;
}

////////////////////////////////////////////////////////////

//Функция добавления в БД отправленного письма
function insertMessageToDB($postRecipient, $postTitle, $postContent, $postTopic)
{
    //запрос для получения id пользователя получателя письма
    $sql = "SELECT users.id FROM users
        WHERE users.login = '" . $postRecipient . "';";
    $userIdToInsert = collectData($sql);
    $userIdToInsert = array_shift($userIdToInsert);
    $recipientId = cleanData($userIdToInsert['id']);
    $senderId = cleanData($_SESSION['user']['id']);

    $sql = "INSERT into messages (title, content, unread, topic_id, sender_id, recipient_id)
        VALUES(('$postTitle'),
        ('$postContent'),
        '1',
        ('$postTopic'),
        ('$senderId'),
        ('$recipientId')
        );";
    doQuery($sql);
}

//Функция получения из БД непрочитанных сообщений пользователя
function getUnreadMessages($userId)
{
    $sql = "SELECT messages.id, messages.title, topics.topic FROM messages
        LEFT JOIN topics ON messages.topic_id = topics.id
        WHERE messages.recipient_id = ('$userId') AND messages.unread = '1';";
    return collectData($sql);
}

//Функция получения из БД прочитанных сообщений пользователя
function getReadMessages($userId)
{
    $sql = "SELECT messages.id, messages.title, topics.topic FROM messages
        LEFT JOIN topics ON messages.topic_id = topics.id
        WHERE messages.recipient_id = ('$userId') AND messages.unread = '0';";
    return collectData($sql);
}

//Функция получения из БД информации по сообщению, выбранному пользователем
function getMessage($mes_id, $userId)
{
    $sql = "SELECT messages.title, messages.sent, topics.topic, parent_topics.topic AS 'parent_topic',
        users.login, users.email, messages.content FROM messages
        LEFT JOIN users ON messages.recipient_id = users.id
        LEFT JOIN topics ON messages.topic_id = topics.id
        LEFT JOIN topics AS parent_topics ON parent_topics.id = topics.parent_id
        WHERE messages.id = ('$mes_id') AND users.id = ('$userId');";
    readMessage($mes_id);
    $selectedMessage = collectData($sql);
    if ($selectedMessage != null && $selectedMessage != false && $selectedMessage != ''){
        $selectedMessage = array_shift($selectedMessage);
        return $selectedMessage;
    }
    return false;
}

//Функция получения из БД пользователей имеющих право писать
function getWriters()
{
    $sql = "SELECT users.login FROM users
            LEFT JOIN group_user ON users.id = group_user.user_id
            LEFT JOIN groups ON groups.id = group_user.group_id
            WHERE groups.id = '3';";
    return collectData($sql);
}

//Функция получения разделов и цветов
function getTopics()
{
    $sql = "SELECT topics.id, topics.topic, colors.hex_code FROM topics
            LEFT JOIN colors ON colors.id = topics.color_id;";
    return collectData($sql);
}
