<?php

namespace Sitecare;

function display_sitecare_report()
{

    if (empty($_REQUEST['report_id'])) {
        return;
    }

    $data = get_sitecare_report($_REQUEST['report_id']);
    $report = json_decode($data['body']);

    ob_start();

    echo $report->html;

    return ob_get_clean();

}
