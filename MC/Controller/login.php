<?php
    declare(strict_types=1);
    require_once($_SERVER['DOCUMENT_ROOT'] . '/MC/Model/UserInteraction.php');

    use MVC\Model\UserInteraction\AuthorizationFactory;

    $emailOrLogin = $_POST['email_or_login'];
    $password = $_POST['password'];

    session_start();
    if (isset($_SESSION['user']))
    {
        header('Location: ' . $_SERVER['HTTP_HOST']);
        exit();
    }

    $success = AuthorizationFactory::authMethod(
        match (str_contains($emailOrLogin, '@'))
        {
            true => 'email',
            false => 'login'
        },
        $emailOrLogin, 
        $password
    )->authorize();

    if ($success)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/polls.php');
        exit();
    }
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php?authError=true');
    exit();
?>