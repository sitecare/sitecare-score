<?php

namespace Sitecare;

add_action('admin_notices', function () {

    $screen = get_current_screen();

    if (!str_contains($screen->id, 'sitecare-history') && !str_contains($screen->id, 'sitecare-score')) {
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
