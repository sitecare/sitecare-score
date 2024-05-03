<?php

namespace Sitecare;

function display_sitecare_score_dashboard()
{

    if (!is_admin()) {
        return;
    }

    $action = null;
    if (isset($_REQUEST['_wpnonce'])) {
        if (wp_verify_nonce($_REQUEST['_wpnonce'], 'sitecare_nonce')) {
            $action = $_REQUEST['action'];
        }
    }

    display_sitecare_header();

    if (empty($action)) {
        display_sitecare_start();
    } else if ($action == 'scan') {
        display_sitecare_scan();
    } else if ($action == 'report') {
        display_sitecare_report();
    }

    display_sitecare_footer();

}
