<?php
    session_start();
    
    if (!isset($_SESSION['user']))
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
        exit();
    }

    $viewFolderPath = 'http://' . $_SERVER['HTTP_HOST'] . '/View';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $viewFolderPath . '/Styles/business_global_styles.css' ?>">
    <link rel="stylesheet" href="<?php echo $viewFolderPath . '/Styles/poll_creation.css' ?>">
    <title>МетрОпрос - Первая Ступень</title>
</head>
<body>
    <?php echo file_get_contents($viewFolderPath . '/header_business.html'); ?>
    <div id="main_container" class="centered_horizontally">
        <h1 class="centered_horizontally">Первый этап</h1>
        <form method="GET" action="secondStage.php">
            <p class="field">
                <span>Название опроса</span>
                <input type="text" name="poll_name" autocomplete="off">
            </p>
            <p class="field">
                <span>Вариантов ответа</span>
                <input type="text" name="quantity" autocomplete="off">
            </p>
            <p class="field">
                <span id="error">
                    <?php
                        if (isset($_GET['reason']))
                            echo match ($_GET['reason'])
                                {
                                    // Для тех, кто ищет уязвимости
                                    'poll_name_empty' => 'Не указано название запроса',
                                    'quantity_zero' => 'Не указано количество варинтов ответа',
                                    'user_has_this_poll' => 'У вас уже есть опрос с таким же именем',
                                    'no_variants' => 'Не указаны варианты ответов',
                                    'no_colorscheme' => 'Не указана цветовая тема',
                                    'many_variants' => 'Можно добавить не более 10 вариантов',
                                    'wrong_color_scheme' => 'Неверно указана цветовая тема',
                                    default => ''
                                };
                    ?>
                </span>
            </p>
            <p class="field">
                <input type="submit" value="Дальше">
            </p>
        </form>
    </div>
</body>
</html>