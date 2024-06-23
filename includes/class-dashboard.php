<?php

namespace SiteCare;

class Dashboard extends Core
{

    private $start, $scan, $report;

    public function __construct()
    {
        $this->start = new Start();
        $this->scan = new Scan();
        $this->report = new Report();
    }

    public function init(): void
    {

        if (!is_admin()) {
            return;
        }

        $this->display_header();

        switch ($this->get_action()) {

            case 'scan':
                $this->scan->init();
                break;

            case 'report':
                $this->report->init();
                break;

            default:
                $this->start->init();
                break;

        }

        $this->display_footer();

    }


}
