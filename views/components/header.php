<header>
    <div class="header-logo">
        <a href="<?php echo HOME_URL; ?>"><img src="/logo.png" width="100px" alt="Logo"></a>
    </div>
    <div class="header-menu">
        <ul>
            <?php
                foreach ($linkedPages as $pageName => $pageLink) {
                    echo "<li><a href='".HOME_URL."$pageLink'>$pageName</a></li>";
                }
            ?>
        </ul>
    </div>
</header>