<?php

namespace Sitecare;

function display_sitecare_score_dashboard()
{

    if (!is_admin()) {
        return;
    }

    display_sitecare_header();

    if (empty($_REQUEST['action'])) {
        display_sitecare_start();
    } else if ($_REQUEST['action'] == 'scan') {
        display_sitecare_scan();
    } else if ($_REQUEST['action'] == 'report') {
        display_sitecare_report();
    }

    display_sitecare_footer();

}
