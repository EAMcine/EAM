<?php

namespace AppBundle\Traits;

include_once __DIR__ . '/userTrait.php';
include_once __DIR__ . '/viewTrait.php';

trait ComponentsTrait {

    use \AppBundle\Traits\UserTrait;
    use \AppBundle\Traits\ViewTrait;

    protected function head() {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="<?= CHARSET; ?>">
        <meta http-equiv="<?= HTTP_EQUIV; ?>" content="<?= HTTP_EQUIV_CONTENT ?>">
        <meta name="<?= NAME_VIEWPORT; ?>" content="<?= CONTENT_VIEWPORT; ?>">
        <title><?= $this->viewTitle() . SEPARATOR . SITE_NAME; ?></title>
        <link id="theme-style" theme="<?= $_SESSION['theme'] ?? 'light'; ?>" rel="stylesheet" href="/css/<?= $_SESSION['theme'] ?? 'light'; ?>-theme.css">
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
        <div class="burger-wrapper">
        <?php
        if ($this->getAvailableLinks()) :
        ?>
            <div class="burger-menu" id="asideOpener">
                <span></span>
            </div>
        <?php endif; ?>
        </div>
        <div class="header-logo"><img class="fill" onclick="location.href = '<?php echo HOME_URL; ?>'" src="/img/logo.png" alt="Logo EAM+"></div>
        <nav class="header-menu">
            <?php if(!$this->getSessionUser()): ?>
            <a id="account" href="/login">Connexion</a>
            <?php else: ?>
            <a id="billing" href="/billing">Facturation</a>
            <a id="logout" href="/logout">Déconnexion</a>
            <?php endif; ?>
        </nav>
        </header>

        <?php
        $this->aside();
        $this->sessionInfo();
    }

    protected function aside() {
        $links = $this->getAvailableLinks();
        if (isset($links)) :
        ?>
        <aside id="aside-menu">
        <?php
            foreach ($links as $name => $route) {
                echo '<a href="' . HOME_URL . $route . '">' . $name . '</a>';
            }            
        ?>
        </aside> 
        <?php
        endif;
    }

    private function getAvailableLinks() : array|null {
        if (!isset($_SESSION['user'])) 
            return null;
        $user = $_SESSION['user'];
        $links = [];
        if ($user) {
            if ($user->can('debug')) {
                $links['Debug'] = "/debug";
            }
            if ($user->can('admin')) {
                $links['Administration'] = "/admin";
            }
            if ($user->can('contributions')) {
                $links['Espace contributeur'] = "/contributor";
            }
        }
        return $links;
    }

    protected function showOptions(array|null $options, string $selected = null) {
        if (isset($options)) {
            foreach ($options as $optionValue => $optionName) {
                echo '<option value="' . $optionValue . '" ' . ($selected == $optionValue ? 'selected' : '') . '>' . $optionName . '</option>';
            }
        }
    }

    protected function postLoader() {
        ?> 
        </div>
        <script src="/js/postloader.js" type="module"></script>
        <?php
    }

    protected function switchTheme() {
        ?>
        <script src="/js/switchTheme.js" type="module"></script>
        <?php
    }

    protected function footer() {
        ?>
        <footer role="footer">
        <p>© <?= date('Y');?> EAM+ ALL RIGHTS RESERVED</p>
        <aside>
            <nav>
            <a href="<?= HOME_URL; ?>/about">À propos</a>
            <a href="<?= HOME_URL; ?>/contact">Contact</a>
            <a id="switchTheme">Thème</a>
            <a href="#body">Haut de page</a>
            </nav>
        </aside>
        </footer>
        <?php
        $this->postLoader();
        $this->switchTheme();
    }
}
