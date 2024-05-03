<?php

namespace Sitecare;

function display_sitecare_report()
{

    if (empty($_REQUEST['report_id'])) {
        return;
    }

    $data = get_sitecare_report($_REQUEST['report_id']);
    $report = json_decode($data['body']);

    echo $report->html;

}
