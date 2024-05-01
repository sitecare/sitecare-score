<?php

namespace Sitecare;

add_action('admin_notices', function () {

    $current_page = isset($_GET['page']) ? $_GET['page'] : '';

    if (('sitecare-reports' != $current_page) && ('sitecare-score' != $current_page)) {
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
