<?php

namespace Sitecare;

add_action('admin_notices', function () {

    $page = basename($_SERVER['REQUEST_URI']);

    if (!str_contains($page, 'sitecare-reports') && !str_contains($page, 'sitecare-score')) {
        return;
    }

    ?>

    <div class="sitecare-banner">

        <h1>
            Sitecare
        </h1>

        <a href="https://sitecare.com/contact/?contact=plugin-support"
           target="_blank" class="btn support-button">
            Support
        </a>

    </div>

    <?php
}, 0);
