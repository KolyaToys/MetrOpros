<?php
    declare(strict_types=1);

    require_once($_SERVER['DOCUMENT_ROOT'] . '/MC/Model/DatabaseInteractor.php');

    use MVC\Model\DatabaseInteractor;
    
    if (empty($_GET['variant']))
        die("Не существует такого варианта ответа.");
    
    if (!empty($_COOKIE['polls_where_voted']) && in_array($_GET['variant'], explode(' ', $_COOKIE['polls_where_voted'])))
        die("Вы уже проголосовали в этом опросе.");
    
    $interactor = DatabaseInteractor::getInstance();

    $sqlCommand = "UPDATE `polls_variants` SET `votes` = `votes` + 1 WHERE `variant_id` = '{$_GET['variant']}'";
    $interactor->query($sqlCommand);

    if ($interactor->error())
        die("Не существует такого варианта ответа");

    $sqlCommand = "SELECT `poll_id` FROM `polls_variants` WHERE `variant_id` = '{$_GET['variant']}'";
    $pollId = $interactor->fetch_hash_table($sqlCommand)['poll_id'];
    
    setcookie('polls_where_voted', 
        (isset($_COOKIE['polls_where_voted']) ? $_COOKIE['polls_where_voted'] : '') . $pollId . ' ',
        (int) INF,
        '/',
    );

    echo '<script>window.close()</script>';
?>