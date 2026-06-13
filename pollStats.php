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

    if (isset($_GET['id']))
        $pollId = $_GET['id'];
    else
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/polls.php');
        exit();
    }

    $interactor = DatabaseInteractor::getInstance();

    // Проверка на принадлежность опроса пользователю и на наличие опроса
    $sqlCommand = "SELECT * FROM `polls` WHERE `id` = '{$pollId}'";
    $pollInformation = $interactor->fetch_hash_table($sqlCommand);

    if (empty($pollInformation) || $pollInformation['belongs_to_id'] != $user->id)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/polls.php');
        exit();
    }

    // Получение вариантов ответа
    $sqlCommand = "SELECT `variant_id`, `variant_text`, `votes` FROM `polls_variants` WHERE `poll_id` = {$pollInformation['id']}";
    $variantsOfAnswer = $interactor->fetch_array($sqlCommand, MYSQLI_ASSOC);

    $sum = 0;
    foreach ($variantsOfAnswer as $variant)
        $sum += $variant['votes'];
    
    $colors = array('orangered', 'gold', 'aquamarine', 'yellowgreen', 'red', 'lightgreen', 'wheat', 'darkgray', 'mediumseagreen', 
        'slateblue');
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
    <?=file_get_contents('View/header_business.html')?>
    <div id="main_container" class="centered_horizontally">
        <h1><?=$pollInformation['name']?></h1>
        <div id="diagram">
            <span class="vertical_legend first">
                Процент опрошенных
            </span>
            <div class="numbers">
                <span>100%</span>
                <span>75%</span>
                <span>50%</span>
                <span>25%</span>
                <span>0</span>
            </div>
            <div id="diagram_base">
                <hr>
                <hr>
                <hr>
                <div id="stats">
                    <?php
                        for ($i = 0; $i < count($variantsOfAnswer); $i++)
                        {
                            $percentage = $sum != 0 && $variantsOfAnswer[$i]['votes'] != 0 ? 
                                $variantsOfAnswer[$i]['votes'] / $sum * 100 : 1;
                            echo '<hr style="height: ' . $percentage . 
                                "%; background-color: {$colors[$i]};\">";
                        }
                    ?>
                </div>
            </div>
            <div class="numbers">
                <?php
                    for ($percent = 1; $percent >= 0; $percent -= .25)
                        echo '<span>' . (string) ceil($sum * $percent) . '</span>';
                ?>
            </div>
            <span class="vertical_legend second">
                Количество опрошенных
            </span>
        </div>
        <ul id="variant_legend">
            <?php
                for ($i = 0; $i < count($variantsOfAnswer); $i++)
                {
                    $variantText = $variantsOfAnswer[$i]['variant_text'];
                    $votes = $variantsOfAnswer[$i]['votes'];
                    $percentage = $sum != 0 && $votes != 0 ? 
                        $votes / $sum * 100 : 0;
                    $color = $colors[$i];
                    echo "<li><span style='color: {$color}'>■</span> {$variantText} - 
                        {$percentage}% - {$votes} </li>";
                }
                ?>
        </ul>
        <?="<a id='embed' href='embed.php?id={$pollInformation['id']}'>Встроить</a>"?>
    </div>
</body>
</html>