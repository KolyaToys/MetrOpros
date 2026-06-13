<?php
    $_SESSION = [];
    setcookie('PHPSESSID', null, -1, '/');
    header("Location: http://" . $_SERVER['HTTP_HOST']);
?>