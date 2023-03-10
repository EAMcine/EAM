<header role="header">
    <div class="header-logo">
        <a href="<?php echo HOME_URL; ?>"><img src="/favicon/favicon.ico" width="100px" alt="Logo"></a>
    </div>
    <div class="header-menu">
        <ul>
            <?php
                foreach ($linkedPages as $pageName => $pageLink) {
                    echo "<li><a href='".HOME_URL."$pageLink'>$pageName</a></li>";
                }
            ?>
        </ul>
        <nav class="menu" role="navigation">
            <div class="m-left">
                <h1 class="logo">EA+</h1>
            </div>
            <div class="m-left">
                <a href="#"></a>
                <a href="#"></a>

            </div>

        </nav>
    </div>
</header>