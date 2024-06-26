<?php

namespace SiteCare;

class Report extends Core
{

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }

    public function init(): void
    {

        if (empty($_REQUEST['report_id'])) {
            return;
        }

        $data = $this->get_report(sanitize_text_field($_REQUEST['report_id']));
        $report = json_decode($data['body']);
        echo wp_kses($report->html, $this->get_allowed_tags());

    }

    public function get_report($hash): array
    {

        $remote_api_url = $this->get_server_url() . '/api/get-report';

        $headers = ['Content-Type' => 'application/json'];

        $data = [
            'site_url' => get_site_url(),
            'report_hash' => $hash,
            'plugin_version' => $this->get_current_plugin_version()
        ];

        $args = [
            'body' => wp_json_encode($data),
            'headers' => $headers,
            'timeout' => 60
        ];

        $response = wp_remote_post($remote_api_url, $args);

        return $response;

    }

    public function admin_enqueue_scripts(): void
    {

        $screen = get_current_screen();

        if (!str_contains($screen->id, 'sitecare-score')) {
            return;
        }

        if ('report' != $this->get_action()) {
            return;
        }

        $js_url = $this->get_server_url() . '/js/sitecare-report.js';

        wp_enqueue_script(
            'sitecare-report',
            $js_url,
            ['jquery'],
            $this->get_current_plugin_version(),
            ['in_footer' => true]
        );

    }

}
