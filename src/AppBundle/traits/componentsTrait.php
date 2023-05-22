<?php

namespace AppBundle\Traits;

trait componentsTrait {

    protected function head() {
        ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="<?php echo CHARSET; ?>">
    <meta http-equiv="<?php echo HTTP_EQUIV; ?>" content="<?php echo HTTP_EQUIV_CONTENT ?>">
    <meta name="<?php echo NAME_VIEWPORT; ?>" content="<?php echo CONTENT_VIEWPORT; ?>">
    <title><?= $this->viewTitle() . SEPARATOR . SITE_NAME; ?></title>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/position.css">
    <link rel="stylesheet" href="/css/light-theme.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
</head>
<body>
        <?php
    }

    protected function preLoader() {
        ?>

<div id="loader">
    <div class="spinner"></div>
</div>
<div id="body" style="opacity:0;"> <!-- THIS IS THE BODY OF THE WEBSITE ONCE IT'S FULLY LOADED -->
        <?php
    }

    protected function header() {
        $this->preLoader();
        ?>

<header role="header">
    <div class="header-logo"><img class="fill" onclick="location.href = '<?php echo HOME_URL; ?>'" src="/img/logo.png" alt="Logo EAM+"></div>
    <nav class="header-main">
        <?php
        if ($this->get('header-links')) :
            foreach ($this->get('header-links') as $link): 
            ?>
            
        <a class="header-link" href="<?= $link['url']; ?>"><?= $link['name']; ?></a>
            <?php
            endforeach; 
        endif;
        ?>

    </nav>
    <nav class="header-menu">
        <a href="#">Theme</a>
        <a href="#">Compte</a>
    </nav>
</header>
        <?php
    }

    protected function postLoader() {
        ?> 
</div>
<script>
    $loader = $('#loader');
    $body = $('#body');
    $(document).ready(function () {
        // Wait for the page to load first + 500ms
        setTimeout(function () {
            $loader.fadeOut(500, function () {
                $body.fadeTo(500, 1);
            });
        }, 500);
    });
</script>
        <?php
    }

}