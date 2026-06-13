<?php
    declare(strict_types=1);
    require_once($_SERVER['DOCUMENT_ROOT'] . '/MC/Model/DatabaseInteractor.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/MC/Model/UserInteraction.php');

    use MVC\Model\DatabaseInteractor;
    use MVC\Model\UserInteraction\AuthorizationFactory;

    // Функция возврата на страницу регистрации на случай если что-то пойдёт не так
    function returnWithError(string $reason) : void
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . "/register.php?reason={$reason}");
        exit();
    }

    session_start();
    // Авторизован ли человек?
    if (isset($_SESSION['user']))
    {
        header('Location: ' . $_SERVER['HTTP_HOST']);
        exit();
    }

    $interactor = DatabaseInteractor::getInstance();

    $email = strip_tags($_POST['email']);
    $login = strip_tags($_POST['login']);
    $password = strip_tags($_POST['password']);

    // Если все поля пустые, то вернуть обратно на страницу регистрации
    if (empty($email) || empty($login) || empty($password))
        returnWithError('emptyInput');
    // Если поля имеют пробелы, то вернуть обратно на страницу регистрации
    if (str_contains($email, ' ') || str_contains($login, ' ') || str_contains($password, ' '))
        returnWithError('spaces');

    // Проверка на уникальность E-Mail
    $emailTry = $interactor->fetch_hash_table(
        "SELECT * FROM `user_login_information` WHERE `email` = '{$email}'"
    );
    if (isset($emailTry)) return returnWithError('emailBusy');

    // Проверка на уникальность логина
    $loginTry = $interactor->fetch_hash_table(
        "SELECT * FROM `user_login_information` WHERE `login` = '{$login}'"
    );
    if (isset($loginTry)) return returnWithError('loginBusy');

    // Регистрация
    $encryptedPassword = md5($password);
    $success = $interactor->query("INSERT INTO `user_login_information` (`email`, `login`, `password`)
        VALUES ('{$email}', '{$login}', '{$encryptedPassword}')");

    if ($success)
    {
        
        AuthorizationFactory::authMethod('login', $login, $password)->authorize();
        header("Location: http://" . $_SERVER['HTTP_HOST'] . '/polls.php');
        exit();
    }
    else
        echo $interactor->error();
?>