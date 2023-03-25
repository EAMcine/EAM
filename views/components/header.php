
<header role="header">
    <div class="logo">
        <a href="<?php echo HOME_URL; ?>"><img src="/img/logo.png" width="100px" alt="Logo EAM+"></a>
    </div>
    <nav class="header-menu">
        <?php
            foreach ($data['linkedPages'] as $pageName => $pageLink) {
                echo "<a class='header-link' href='".HOME_URL."$pageLink'>$pageName</a>";
            }
        ?>

    </nav>
    <!-- <nav class="menu" role="navigation">
        <div class="m-left">
            <a href="#">1</a>
            <a href="#">2</a>

        </div>

    </nav> -->
</header>
