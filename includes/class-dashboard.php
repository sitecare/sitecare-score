<?php

namespace SiteCare;

class Dashboard extends Core
{

    private $start, $scan, $report;

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

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

    public function enqueue_scripts(): void
    {

        if (!is_admin()) {
            return;
        }

        $screen = get_current_screen();

        if (!str_contains($screen->id, 'sitecare-score')) {
            return;
        }

        $this->enqueue_sitecare_styles();

        if (empty($this->get_action())) {

            // Add start script

            $path = plugin_dir_url(__FILE__) . 'assets/sitecare-start.js';

            wp_enqueue_script(
                'sitecare-start',
                $path,
                ['jquery'],
                $this->get_current_plugin_version(),
                true
            );

        }

    }

}
