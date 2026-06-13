<?php
    declare(strict_types=1);

    session_start();
    if (!isset($_SESSION['user']))
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
        exit();
    }
    else
        $user = unserialize($_SESSION['user']);

    require_once($_SERVER['DOCUMENT_ROOT'] . '/MC/Model/DatabaseInteractor.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/MC/Model/UserInteraction.php');

    use MVC\Model\DatabaseInteractor;
    use MVC\Model\UserInteraction\User;

    function returnWithError(string $reason) : void
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . "/poll_creation_stages/firstStage.php?reason={$reason}");
        exit();
    }

    $interactor = DatabaseInteractor::getInstance();
    $user = unserialize($_SESSION['user']);

    $trustedDomains = isset($_GET['all_domains_trusted']) ? '' : str_replace(' ', '', $_GET['trusted_domains']);
    if (!isset($_GET['poll_name']))
    {
        header('Location: http://' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $colorScheme = isset($_GET['Poll_Scheme']) ? $_GET['Poll_Scheme'] : 1;
    if ($colorScheme < 1 || $colorScheme > 4)
        returnWithError('wrong_color_scheme');

    $pollName = trim($_GET['poll_name'], ' ');

    if (empty($pollName))
        returnWithError('poll_name_empty');
    
    // Проверка на уникальность опроса. Формирование альтернативного ключа из полей name и belongs_to_id.
    $sqlCommand = "SELECT * FROM `polls` WHERE `belongs_to_id` = '{$user->id}' AND `name` = '{$pollName}'";
    $result = $interactor->fetch_hash_table($sqlCommand);

    if (!empty($result))
        returnWithError('user_has_this_poll');


    $sqlCommand = "INSERT INTO `polls` (`belongs_to_id`, `name`, `trusted_domains`, `color_scheme`) VALUES
        ('{$user->id}', '{$pollName}', '{$trustedDomains}', {$colorScheme})";

    $interactor->query($sqlCommand);

    $sqlCommand = "SELECT * FROM `polls` WHERE `belongs_to_id` = '{$user->id}' AND `name` = '{$pollName}'";
    $pollId = $interactor->fetch_hash_table($sqlCommand)['id'];

    $sqlCommand = 'INSERT INTO `polls_variants` (`poll_id`, `variant_text`) VALUES ';

    $variantQuantity = 0;
    foreach (array_keys($_GET) as $attr)
        if (substr($attr, 0, 4) == 'var_' && !empty($_GET[$attr]))
        {
            $variant = trim($_GET[$attr], ' ');
            $sqlCommand .= "('{$pollId}', '{$variant}'), ";
            $variantQuantity++;
        }
    
    if ($variantQuantity == 0)
        returnWithError('no_variants');
    else if ($variantQuantity > 10)
        returnWithError('many_variants');
    
    $sqlCommand = substr($sqlCommand, 0, strlen($sqlCommand) - 2);
    $interactor->query($sqlCommand);

    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/polls.php');
    exit();
?>