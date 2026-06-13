<?php
    declare(strict_types=1);

    function returnWithError(string $reason) : void
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . "/poll_creation_stages/firstStage.php?reason={$reason}");
        exit();
    }

    session_start();
    
    if (!isset($_SESSION['user']))
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
        exit();
    }
    
    if (empty($_GET['poll_name']))
        returnWithError('poll_name_empty');

    if (empty($_GET['quantity']))
        returnWithError('quantity_zero');
    else if ($_GET['quantity'] < 0 || $_GET['quantity'] > 10)
        returnWithError('many_variants');

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
        <h1 class="centered_horizontally">Второй этап</h1>
        <form method="GET" action="thirdStage.php">
            <?php
                echo "<input type='text' class='hidden' name='poll_name' value='{$_GET['poll_name']}'>";
                for ($i = 1; $i <= $_GET['quantity']; $i++)
                    echo "
                    <p class='field'>
                        <span>Вариант {$i}</span>
                        <input type='text' name='var_{$i}' autocomplete='off'>
                    </p>";
            ?>
            <p class="field">
                <input type="submit" value="Дальше">
            </p>
        </form>
    </div>
</body>
</html>