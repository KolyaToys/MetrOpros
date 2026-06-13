<?php
    declare(strict_types=1);

    require_once('MC/Model/DatabaseInteractor.php');
    require_once('MC/Model/UserInteraction.php');
    use MVC\Model\DatabaseInteractor;
    use MVC\Model\UserInteraction\User;

    session_start();
    if (!isset($_SESSION['user']))
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
        exit();
    }
    $user = unserialize($_SESSION['user']);

    if (!isset($_GET['id']))
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/polls.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="View/Styles/business_global_styles.css">
    <link rel="stylesheet" href="View/Styles/pollStats.css">
    <title>МетрОпрос - Статистика</title>
</head>
<body>
    <div id="main_contaier">
        <input type="text" value="<iframe src='http://f0823438.xsph.ru/poll.php?id=<?=$_GET['id']?>'>" readonly></h1>
    </div>
</body>
</html>