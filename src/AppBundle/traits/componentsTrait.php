<?php

namespace AppBundle\Traits;

trait componentsTrait {

    protected function head() {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="<?= CHARSET; ?>">
        <meta http-equiv="<?= HTTP_EQUIV; ?>" content="<?= HTTP_EQUIV_CONTENT ?>">
        <meta name="<?= NAME_VIEWPORT; ?>" content="<?= CONTENT_VIEWPORT; ?>">
        <title><?= $this->viewTitle() . SEPARATOR . SITE_NAME; ?></title>
        <link id="theme-style" theme="light" rel="stylesheet" href="/css/light-theme.css">
        <link rel="stylesheet" href="/css/loading.css">
        <link rel="stylesheet" href="/css/main.css">
        <script src="/js/jquery.js"></script>
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
        <div id="loader" style="opacity:0;">
        <div class="spinner"></div>
        </div>
        <div id="body" class="loading" style="opacity:1;"> <!-- THIS IS THE BODY OF THE WEBSITE ONCE IT'S FULLY LOADED -->
        <script src="/js/preLoader.js"></script>
        <?php
    }

    protected function header() {
        $this->preLoader();
        ?>
        <header role="header">
        <div class="header-logo"><img class="fill" onclick="location.href = '<?php echo HOME_URL; ?>'" src="/img/logo.png" alt="Logo EAM+"></div>
        <h1 class="header-title"><?= $this->viewTitle(); ?></h1>
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
        <script src="/js/postloader.js" type="module"></script>
        <?php
    }
}
