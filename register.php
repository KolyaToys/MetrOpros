<?php
    declare(strict_types=1);
    session_start();

    if (isset($_SESSION['user']))
    {
        header('Location: ' . $_SERVER['HTTP_HOST']);
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
        <span>Регистрация</span>
        <form method="POST" action='MC/Controller/register.php'>
            <p>E-mail</p>
            <p><input type="text" name="email"></p>
            <p>Логин</p>
            <p><input type="text" name="login" autocomplete="off"></p>
            <p>Пароль</p>
            <p><input type="password" name="password"></p>
            <p><input type="submit" value="Подтвердить"></p>
            <p id="error">
                <?php
                    $reason = isset($_GET['reason']) ? $_GET['reason'] : null;
                    if (isset($reason))
                    {
                        switch ($reason)
                        {
                            case 'emailBusy':
                                echo "Этот E-mail занят";
                                break;
                            case 'loginBusy':
                                echo "Этот Логин занят";
                                break;
                            case 'emptyInput':
                                echo "Укажите значения на всех полях";
                                break;
                            case 'spaces':
                                echo "На полях не должно быть пробелов";
                                break;
                            default:
                                break;
                        }
                    }
                ?>
            </p>
        </form>
    </div>
</body>
</html>