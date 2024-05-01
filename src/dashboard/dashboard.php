<?php

namespace Sitecare;

function display_sitecare_score_dashboard()
{

    if (!is_admin()) {
        return;
    }

    echo display_sitecare_header();

    if (empty($_REQUEST['action'])) {
        echo display_sitecare_start();
    } else if ($_REQUEST['action'] == 'scan') {
        echo display_sitecare_scan();
    } else if ($_REQUEST['action'] == 'report') {
        echo display_sitecare_report();
    }

    echo display_sitecare_footer();

}
