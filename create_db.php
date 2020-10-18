<?php

$createDataBase = false;
$connect = connectDB('');
$sql = "CREATE DATABASE IF NOT EXISTS taskmandb CHARACTER SET utf8 COLLATE utf8_general_ci;";
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
} else {
    $createDataBase = mysqli_query($connect, $sql);
}

$sql = "use taskmandb";
doQuery($sql);

//Создаем таблицу users
$sql = "CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    login VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    activity TINYINT(1) NOT NULL,
    notification TINYINT(1) NOT NULL,
    PRIMARY KEY(id),
   UNIQUE INDEX id_UNIQUE (id ASC));";
$createDataBase = doQuery($sql) && $createDataBase;

//Создаем таблицу groups
$sql = "CREATE TABLE IF NOT EXISTS groups (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE INDEX id_UNIQUE (id ASC));";
$createDataBase = doQuery($sql) && $createDataBase;

//Создаем таблицу group_user
$sql  = "CREATE TABLE group_user (
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    INDEX group_id_c_idx (group_id ASC),
    INDEX user_id_c_idx (user_id ASC),
    PRIMARY KEY (group_id, user_id),
    CONSTRAINT group_id_c
    FOREIGN KEY (group_id)
    REFERENCES groups (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT user_id_c
    FOREIGN KEY (user_id)
    REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE);";
$createDataBase = doQuery($sql) && $createDataBase;

//Создаем таблицу colors
$sql = "CREATE TABLE IF NOT EXISTS colors (
    id INT NOT NULL AUTO_INCREMENT,
    color VARCHAR(10) NOT NULL,
    hex_code VARCHAR(10) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE INDEX id_UNIQUE (id ASC));";
$createDataBase = doQuery($sql) && $createDataBase;

//Создаем таблицу темы
$sql = "CREATE TABLE topics (
  id INT NOT NULL AUTO_INCREMENT,
  topic VARCHAR(255) NOT NULL,
  color_id INT NOT NULL,
  created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  creator_id INT NOT NULL,
  parent_id INT NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX id_UNIQUE (id ASC),
  INDEX color_id_z_idx (color_id ASC),
  INDEX creator_id_z_idx (creator_id ASC),
  CONSTRAINT color_id_z
    FOREIGN KEY (color_id)
    REFERENCES colors (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT creator_id_z
    FOREIGN KEY (creator_id)
    REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE);";
$createDataBase = doQuery($sql) && $createDataBase;

//Создаем таблицу messages
$sql = "CREATE TABLE messages (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    unread TINYINT(1) NOT NULL,
    topic_id INT NOT NULL,
    sent TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sender_id INT NOT NULL,
    recipient_id INT NOT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX id_UNIQUE (id ASC),
    INDEX topic_id_z_idx (topic_id ASC),
    INDEX sender_id_z_idx (sender_id ASC),
    INDEX recipient_id_z_idx (recipient_id ASC),
    CONSTRAINT topic_id_z
    FOREIGN KEY (topic_id)
    REFERENCES topics (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT sender_id_z
    FOREIGN KEY (sender_id)
    REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT recipient_id_z
    FOREIGN KEY (recipient_id)
    REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE);";
$createDataBase = doQuery($sql) && $createDataBase;

//Добавляем данные в таблицу users
$sql = "INSERT INTO users
    (login, password, email, phone, activity, notification)
    VALUES ('user', '" . password_hash('111', PASSWORD_DEFAULT) . "', 'user@mail.ru', '+79041234561', '1', '1'),
    ('roberto', '" . password_hash('222', PASSWORD_DEFAULT) . "', 'roberto@mail.ru', '+79041234562', '1', '1'),
    ('edmundo', '" . password_hash('333', PASSWORD_DEFAULT) . "', 'edmundo@mail.ru', '+79041234563', '1', '1'),
    ('enrique', '" . password_hash('444', PASSWORD_DEFAULT) . "', 'enrique@mail.ru', '+79041234564', '1', '1'),
    ('jose', '" . password_hash('555', PASSWORD_DEFAULT) . "', 'jose@mail.ru', '+79041234565', '0', '0'),
    ('peppe', '" . password_hash('666', PASSWORD_DEFAULT) . "', 'peppe@mail.ru', '+79041234566', '0', '0');";
$createDataBase = doQuery($sql) && $createDataBase;

//Добавляем данные в таблицу groups
$sql = "INSERT INTO groups
    (name, description)
    VALUES ('admin', 'Управление сайтом'),
    ('user', 'Зарегистрированные пользователи'),
    ('writer', 'Пользователь имеющий право писать сообщения');";
$createDataBase = doQuery($sql) && $createDataBase;

//Добавляем данные в таблицу group_user
$sql = "INSERT INTO group_user
    (group_id, user_id)
    VALUES ('1', '1'),
    ('1', '2'),
    ('1', '3'),
    ('2', '1'),
    ('2', '2'),
    ('2', '3'),
    ('2', '4'),
    ('2', '5'),
    ('3', '1'),
    ('3', '2'),
    ('3', '3'),
    ('3', '4');";
$createDataBase = doQuery($sql) && $createDataBase;

//Добавляем данные в таблицу colors
$sql = "INSERT INTO colors
    (color, hex_code)
    VALUES ('grey', '#777777'),
    ('blue', '#0000ff'),
    ('red', '#ff0000'),
    ('brown', '#a52a2a'),
    ('green', '#008000'),
    ('pink', '#ffc0cb'),
    ('white', '#ffffff'),
    ('chocolate', '#d2691e'),
    ('yello', '#ffff00');";
$createDataBase = doQuery($sql) && $createDataBase;

//Добавляем данные в таблицу topics
$sql = "INSERT INTO topics
    (topic, color_id, creator_id, parent_id)
    VALUES ('Основные', '1', '1', '1'),
    ('Оповещения', '2', '1', '2'),
    ('Спам', '3', '3', '3'),
    ('По работе', '5', '2', '1'),
    ('Личные', '9', '2', '1'),
    ('Форумы', '6', '1', '2'),
    ('Магазины', '7', '1', '2'),
    ('Подписки', '8', '2', '2');";
$createDataBase = doQuery($sql) && $createDataBase;

//Добавляем данные в таблицу messages
$sql = "INSERT INTO messages
    (title, content, unread, topic_id, sender_id, recipient_id)
    VALUES ('про ежа', 'долгая история про путешествие ежа', '1', '1', '1', '2'),
    ('про жужа', 'долгая история про путешествие жужа', '1', '5', '1', '2'),
    ('про бомжа', 'долгая история про путешествие бомжа', '1', '3', '3', '1'),
    ('жа', 'долгая история про жа', '1', '4', '2', '3'),
    ('про ножа', 'долгая история про путешествие ножа', '1', '2', '2', '2'),
    ('про лежа', 'долгая история про путешествие лежа', '1', '7', '1', '1'),
    ('про НН', 'долгая история про путешествие Николая Никонорыча', '1', '8', '3', '2'),
    ('про чижа', 'долгая история про путешествие чижа', '1', '3', '2', '1');";
$createDataBase = doQuery($sql) && $createDataBase;

$infoInstall = 'База данных с таблицами создана.';
if ($createDataBase) {
    file_put_contents($fileDB, $infoInstall);
}
