<?php
    declare(strict_types=1);

    session_start();
    
    if (!isset($_SESSION['user']))
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login.php');
        exit();
    }

    function returnWithError(string $reason) : void
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . "/poll_creation_stages/firstStage.php?reason={$reason}");
        exit();
    }

    if (empty($_GET['poll_name']))
        returnWithError('poll_name_empty');
    
    if (empty($_GET['Poll_Scheme']))
        returnWithError('no_colorscheme');
    
    foreach(array_keys($_GET) as $attr)
        if (empty($_GET[$attr]))
            returnWithError('no_variants');

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
    <title>МетрОпрос - Четвёртая Ступень</title>
</head>
<body>
    <?php echo file_get_contents($viewFolderPath . '/header_business.html'); ?>
    <div id="main_container" class="centered_horizontally">
        <h1 class="centered_horizontally">Четвёртый этап</h1>
        <form method="GET" action="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/MC/Controller/createNewPoll.php' ?>">
            <?php
                foreach(array_keys($_GET) as $attr)
                    echo "<input class='hidden' type='text' name='{$attr}' value='{$_GET[$attr]}'>";
            ?>
            <p class="field" id="trust">
                <span>Доверять всем доменным адресам</span>
                <input type="checkbox" name="all_domains_trusted" value="all_domains_trusted">
            </p>
            <p class="field" id="trust_field">
                <span>Доверенные адреса</span>
                <input type="text" name="trusted_domains" placeholder='Вводите через ";". Пример: "ya.ru"'>
            </p>
            <p class="field">
                <input type="submit" value="Опубликовать">
            </p>
        </form>
    </div>
</body>
</html>