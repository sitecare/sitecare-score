<?php

namespace Sitecare;

function display_sitecare_score_dashboard()
{

    if (!is_admin()) {
        return;
    }

    display_sitecare_header();

    switch (get_sitecare_action()) {

        case 'scan':
            display_sitecare_scan();
            break;

        case 'report':
            display_sitecare_report();
            break;

        default:
            display_sitecare_start();
            break;

    }

    display_sitecare_footer();

}
