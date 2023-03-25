
<header role="header">
    <div class="header-logo"><img class="fill" onclick="location.href = '<?php echo HOME_URL; ?>'" src="/img/logo.png" alt="Logo EAM+"></div>
    <nav class="header-main">
        <?php
            foreach ($data['linkedPages'] as $pageName => $pageLink) {
                echo "<a class=\"header-link\" href='".HOME_URL."$pageLink'>$pageName</a>";
            }
        ?>
    </nav>
    <nav class="header-menu">
        <a href="#">Theme</a>
        <a href="#">Compte</a>
    </nav>
</header>
