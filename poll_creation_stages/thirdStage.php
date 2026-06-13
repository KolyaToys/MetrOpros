<?php
    declare(strict_types=1);

    session_start();

    function returnWithError(string $reason) : void
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . "/poll_creation_stages/firstStage.php?reason={$reason}");
        exit();
    }
    
    if (!isset($_SESSION['user']))
    {
        header('Location http://' . $_SERVER['HTTP_HOST'] . '/login.php');
        exit();
    }

    if (empty($_GET['poll_name']))
        returnWithError('poll_name_empty');
    
    foreach($_GET as $attr)
        if (empty($attr))
            returnWithError('no_variants');

    $viewFolderPath = 'http://' . $_SERVER['HTTP_HOST'] . '/View';
    $coloschemeImagePath = 'http://' . $_SERVER['HTTP_HOST'] . '/poll_creation_stages/Poll_Theme_Icons';
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
        <h1 class="centered_horizontally">Третий этап</h1>
        <form method="GET" action="fourthStage.php">
            <?php
                // Я не мог сделать передачу данных предшествующих этапов другим способом
                foreach(array_keys($_GET) as $attr)
                    echo "<input class='hidden' type='text' name='{$attr}' value='{$_GET[$attr]}'>";
            ?>
            <div id="colorscheme_row">
                <label>
                    <input type="radio" value="1" name="Poll_Scheme" checked>
                    <img src="<?=$coloschemeImagePath?>/Cyan_Poll.png">
                </label>
                <label>
                    <input type="radio" value="2" name="Poll_Scheme">
                    <img src="<?=$coloschemeImagePath?>/DarkRed_Poll.png">
                </label>
                <label>
                    <input type="radio" value="3" name="Poll_Scheme">
                    <img src="<?=$coloschemeImagePath?>/Green_Poll.png">
                </label>
                <label>
                    <input type="radio" value="4" name="Poll_Scheme">
                    <img src="<?=$coloschemeImagePath?>/Yellow_Poll.png">
                </label>
            </div>
            <p class="field">
                <input type="submit" value="Выбрать тему">
            </p>
        </form>
    </div>
</body>
</html>