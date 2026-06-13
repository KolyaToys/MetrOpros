<?php
    declare(strict_types=1);
    session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" charset="UTF-8" href="View/Styles/global_styles.css">
    <link rel="stylesheet" charset="UTF-8" href="View/Styles/index.css">
    <title>МетрОпрос: Добро пожаловать</title>
</head>
<body>
    <style>
        <?php
            if (isset($_SESSION['user']))
                echo ".for_unauthorized { display: none }";
            else echo ".for_authorized { display: none }";
        ?>
    </style>
    <?php echo file_get_contents('View/header.html'); ?>
    <section class="center" id="first_section">
        <div>
            <b>Новая система опросов<br></b>
            <b>Может быть у вас на сайте!</b>
        </div>
    </section>
    <hr>
    <section class="center" id="second_section">
        <div class="center" id="simple_poll_text_div">
            <b>Простой конструктор опросов!</b>
        </div>
    </section>
    <hr>
    <section class="center" id="third_section">
        <div>
            <h1 class="centered_horizontally">Начните сейчас!</h1>
            <div id="button_row" class="adaptable_row centered_horizontally">
                <a href="register.php">Регистрация</a>
                <a href="login.php">Вход</a>
            </div>
        </div>
    </section>
    <footer>
        <h1>Сервис не является коммерческим</h1>
    </footer>
</body>
</html>