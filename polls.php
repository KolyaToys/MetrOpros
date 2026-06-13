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

    $interactor = DatabaseInteractor::getInstance();
    $user = unserialize($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="View/Styles/business_global_styles.css">
    <link rel="stylesheet" href="View/Styles/polls.css">
    <title>МетрОпрос - Мои Опросы</title>
</head>
<body>
    <?php echo file_get_contents('View/header_business.html'); ?>
    <h1 id="my_polls" class="centered_horizontally">Мои опросы</h1>
    <div id="polls_list" class="centered_horizontally">
        <?php
            $data = $interactor->fetch_array("SELECT `id`, `name` FROM `polls` WHERE `belongs_to_id` = '{$user->id}'", MYSQLI_ASSOC);
            
            if (empty($data))
                echo "
                <div>
                    <img src='Poll_Icon.png'>
                    <span>У вас нет опросов</span>
                </div>
                ";
            else
                foreach ($data as $row)
                {
                    $pollName = $row['name'];
                    echo "
                    <a href='pollStats.php?id={$row['id']}'><div>
                        <img src='Poll_Icon.png'>
                        <span>{$pollName}</span>
                    </div></a>
                    ";
                }
        ?>
        <a href="poll_creation_stages/firstStage.php" id="add_poll">
            <img src="Add_Poll.png">
        </a>
    </div>
</body>
</html>