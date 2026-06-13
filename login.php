<?php
    declare(strict_types=1);
    session_start();

    if (isset($_SESSION['user']))
    {
        header('Location: http://' . $_SERVER['HTTP_HOST']);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="View/Styles/global_styles.css">
    <link rel="stylesheet" href="View/Styles/register_and_login.css">
    <title>МетрОпрос - Регистрация</title>
</head>
<body>
    <?php echo file_get_contents('View/header.html'); ?>
    <div id='main_container'>
        <span>Вход</span>
        <form method="POST" action='MC/Controller/login.php'>
            <p>E-mail или Логин</p>
            <p><input type="text" name="email_or_login"></p>
            <p>Пароль</p>
            <p><input type="password" name="password"></p>
            <p><input type="submit" value="Войти"></p>
            <p id="error"><?php if (isset($_GET['authError']) && $_GET['authError'] == true) echo "Неверные данные входа"; ?></p>
        </form>
    </div>
</body>
</html>