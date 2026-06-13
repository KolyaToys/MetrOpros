<?php
    declare(strict_types=1);

    require_once('MC/Model/DatabaseInteractor.php');

    use MVC\Model\DatabaseInteractor;

    $voted = !empty($_COOKIE['polls_where_voted']) && in_array($_GET['id'], explode(' ', $_COOKIE['polls_where_voted']));

    
    $mainColors = array(
        'Cyan' => '#6CD5BA', 
        'DarkRed' => '#330022', 
        'Green' => '#35D518', 
        'Yellow' => '#C2D518'
    );
    $secondaryColors = array(
        'Cyan' => '#8B10FF', 
        'DarkRed' => '#6200E0', 
        'Green' => '#6A10E0', 
        'Yellow' => '#D210E0'
    );
    $tertiaryColors = array(
        'Cyan' => '#FF8AB9', 
        'DarkRed' => '#E30016', 
        'Green' => '#F48A16', 
        'Yellow' => '#FF8A16'
    );
    
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    
    $interactor = DatabaseInteractor::getInstance();
    $sqlCommand = "SELECT `name`, `trusted_domains`, `color_scheme` FROM `polls` WHERE
            `id` = {$id}";
    $pollInfo = $interactor->fetch_hash_table($sqlCommand);
    if (empty($pollInfo))
    die("Опрос не найден.");
    
    $colorScheme = $pollInfo['color_scheme'];

    if ($voted)
        goto if_voted;
    
    // Проверка на доверенный домен
    // Домен считается доверенным если находится в столбце trusted_domains или
    // Является поддоменом находящегося
    $domain = $_SERVER['HTTP_REFERER'];
    $i = -1;
    while (substr($domain, ++$i, 3) != '://');
    $start = $i + 3;
    $end = strpos($domain, '/', $start);
    $domain = substr($domain, $start, $end - $start);

    $domainLevels = explode('.', $domain);
    $domainLevelsLength = count($domainLevels);


    if (empty($pollInfo['trusted_domains']))
        $trusted = true;
    else
    {
        $trusted = false;
        foreach (explode(';', $pollInfo['trusted_domains']) as $trustedDomain)
        {
            $trustedDomainLevels = explode('.', $trustedDomain);
            $trustedDomainLevelsLength = count($trustedDomainLevels);

            if ($trustedDomainLevelsLength > $domainLevelsLength)
                continue;
            
            $broken = false;
            for ($i = 0; $i < $trustedDomainLevelsLength; $i++)
                if ($trustedDomainLevels[$trustedDomainLevelsLength - $i - 1] != $domainLevels[$domainLevelsLength - $i - 1])
                {
                    $broken = true;
                    break;
                }

            if ($broken == true) continue;

            $trusted = true;
        }
    }

    if ($trusted == false)
        die("Домен не входит в список доверенных.");

    $sqlCommand = "SELECT `variant_id`, `variant_text` FROM `polls_variants` WHERE `poll_id` = $id";
    $variants = $interactor->fetch_array($sqlCommand, MYSQLI_ASSOC);
?>

<?php if_voted: ?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="View/Styles/poll.css">
    <title>Document</title>
</head>

<body>
    <style>
        :root
        {
            --main-color: <?=$mainColors[$colorScheme]?>;
            --secondary-color: <?=$secondaryColors[$colorScheme]?>;
            --tertiary-color: <?=$tertiaryColors[$colorScheme]?>;
        }
    </style>
    <div id="poll">
        <form id="main_container" target="_blank" action='MC/Controller/vote.php'>
            <?php
                if ($voted)
                {
                    echo '<h1 id="thankyou">Спасибо!</h1>';
                    exit();
                }
            ?>
            <h1><?=$pollInfo['name']?></h1>
            <div id="radiobuttons">
                <?php
                    foreach ($variants as $variant)
                    {
                        echo "
                        <div>
                            <input type='radio' name='variant' id='{$variant['variant_id']}' value='{$variant['variant_id']}'>
                            <label for='{$variant['variant_id']}'>{$variant['variant_text']}</label>
                        </div>";
                    }
                ?>
            </div>
            <div><input type='submit'></div>
        </form>
    </div>
</body>

</html>